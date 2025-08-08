/**
 * DoIt Chat Core - Lógica global compartida
 * Maneja la comunicación con el servidor, estado del chat y funciones comunes
 */
class DoitChatCore {
  constructor(config) {
    this.config = config;
    this.remaining = config.limit;
    this.waitingConfirmation = false;
    this.userHasEmail = false;
    this.messages = [];
    this.listeners = {};
    
    // Inicializar estado
    this.initializeChatState();
  }

  /* ---------- API Communication ---------- */
  async sendMessage(question) {
    if (!question.trim() || this.remaining <= 0 || this.waitingConfirmation) {
      // ✅ Si ya no quedan mensajes, emitir evento también
      if (this.remaining <= 0) {
        this.emit('limit_reached', { remaining: this.remaining });
      }
      return { success: false, reason: 'invalid_state' };
    }

    try {
      const response = await fetch(this.config.ajax, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          action: 'doit_chat_ask',
          nonce: this.config.nonce,
          q: question
        })
      });

      const result = await response.json();
      
      if (result.success) {
        this.remaining = result.data.remaining;
        this.userHasEmail = result.data.user_has_email;
        
        // Agregar mensajes al historial
        this.messages.push({ type: 'user', text: question });
        this.messages.push({ type: 'bot', text: result.data.answer });
        
        // Emitir evento
        // ✅ EMITIR AMBOS eventos
        this.emit('message_sent', { question, answer: result.data.answer });
        this.emit('state_updated', { 
          remaining: this.remaining,
          userHasEmail: this.userHasEmail 
        });
      } else if (result.data.reason === 'limit') {
        this.remaining = result.data.remaining || 0;
        this.userHasEmail = result.data.user_has_email;
        
        // Emitir evento de límite
        this.emit('limit_reached', { remaining: this.remaining });
      }

      return result;
    } catch (error) {
      // console.error('Error sending message:', error);
      return { success: false, reason: 'network_error' };
    }
  }

  async submitEmail(email) {
    if (!email.trim()) {
      return { success: false, reason: 'invalid_email' };
    }

    try {
      const response = await fetch(this.config.ajax, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          action: 'doit_chat_email',
          nonce: this.config.nonce,
          email: email
        })
      });

      const result = await response.json();
      
      if (result.success) {
        this.userHasEmail = true;
        
        if (result.data.requires_confirmation) {
          this.waitingConfirmation = true;
          this.emit('email_confirmation_required', { email });
        } else {
          this.remaining = this.config.limit_after_email_confirmed;
          this.emit('email_confirmed', { remaining: this.remaining });
        }
      }

      return result;
    } catch (error) {
      // console.error('Error submitting email:', error);
      return { success: false, reason: 'network_error' };
    }
  }

  async checkConfirmation() {
    try {
      const response = await fetch(this.config.ajax, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          action: 'doit_check_confirmation',
          nonce: this.config.nonce
        })
      });

      const result = await response.json();
      
      if (result.success) {
        this.remaining = this.config.limit_after_email_confirmed;
        this.waitingConfirmation = false;
        this.emit('email_confirmed', { remaining: this.remaining });
      }

      return result;
    } catch (error) {
      // console.error('Error checking confirmation:', error);
      return { success: false, reason: 'network_error' };
    }
  }

  async initializeChatState() {
    try {
      const response = await fetch(this.config.ajax, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          action: 'doit_get_status',
          nonce: this.config.nonce
        })
      });

      const result = await response.json();
      
      if (result.success) {
        this.remaining = result.data.remaining;
        this.userHasEmail = result.data.user_has_email;
        this.waitingConfirmation = result.data.waiting_confirmation || false;
        
        this.emit('state_initialized', this.getState());
      }

      return result;
    } catch (error) {
      // console.error('Error initializing chat state:', error);
      return { success: false, reason: 'network_error' };
    }
  }

  /* ---------- State Management ---------- */
  getState() {
    return {
      remaining: this.remaining,
      userHasEmail: this.userHasEmail,
      waitingConfirmation: this.waitingConfirmation,
      messages: this.messages,
      canChat: this.remaining > 0 && !this.waitingConfirmation
    };
  }

  getRemainingText() {
    if (this.userHasEmail) {
      return `${this.remaining} messages left (Email confirmed)`;
    }
    return `${this.remaining} messages left`;
  }

  /* ---------- Utility Methods ---------- */
  formatDate(date) {
    return date.toLocaleDateString();
  }

  getTomorrowDate() {
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    return this.formatDate(tomorrow);
  }

  /* ---------- Event System ---------- */
  on(event, callback) {
    if (!this.listeners[event]) {
      this.listeners[event] = [];
    }
    this.listeners[event].push(callback);
  }

  emit(event, data) {
    if (this.listeners[event]) {
      this.listeners[event].forEach(callback => callback(data));
    }
  }

  /* ---------- Generic Modal System ---------- */
  showModal(config) {
    const modal = document.createElement('div');
    modal.className = config.modalClass || 'modal';
    modal.innerHTML = `
      <div class="${config.contentClass || 'modal-content'}">
        <h3>${config.title}</h3>
        <p>${config.message}</p>
        ${config.hasInput ? `<input type="email" id="${config.inputId}" placeholder="${config.inputPlaceholder}" />` : ''}
        <div>
          ${config.buttons.map(btn => `<button id="${btn.id}" class="${btn.class || ''}">${btn.text}</button>`).join('')}
        </div>
        <div id="${config.statusId || 'modalStatus'}"></div>
      </div>
    `;
    document.body.appendChild(modal);
    
    // Configurar event listeners para los botones
    config.buttons.forEach(btn => {
      if (btn.handler) {
        modal.querySelector(`#${btn.id}`).onclick = () => btn.handler(modal);
      }
    });
    
    return modal;
  }

  /* ---------- Generic Email Confirmation Flow ---------- */
  async handleEmailConfirmationFlow(addMessageCallback, updateUICallback) {
    const modal = this.showModal({
      modalClass: 'modal',
      contentClass: 'modal-content',
      title: 'Need more messages?',
      message: `Leave your email and get <b>${this.config.limit_after_email_confirmed} extra</b> questions.`,
      hasInput: true,
      inputId: 'modalEmail',
      inputPlaceholder: 'you@mail.com',
      statusId: 'modalStatus',
      buttons: [
        {
          id: 'modalEmailBtn',
          text: 'Unlock',
          handler: (modal) => this.handleEmailSubmission(modal, addMessageCallback, updateUICallback)
        },
        {
          id: 'modalCancelBtn',
          text: 'Cancel',
          class: 'secondary',
          handler: (modal) => modal.remove()
        }
      ]
    });
    
    // Focus en el input
    const emailInput = modal.querySelector('#modalEmail');
    if (emailInput) emailInput.focus();
    
    return modal;
  }

  async handleEmailSubmission(modal, addMessageCallback, updateUICallback) {
    const emailInput = modal.querySelector('#modalEmail');
    const emailBtn = modal.querySelector('#modalEmailBtn');
    const status = modal.querySelector('#modalStatus');
    
    const email = emailInput.value.trim();
    if (!email) {
      status.innerHTML = '<div class="status-error">Please enter your email address</div>';
      return;
    }

    emailBtn.disabled = true;
    emailBtn.textContent = 'Sending...';

    const result = await this.submitEmail(email);
    
    if (result.success) {
      if (result.data.requires_confirmation) {
        status.innerHTML = '<div class="status-success">Email sent! Check your inbox and confirm subscription.</div>';
        emailBtn.textContent = 'Check Confirmation';
        emailBtn.onclick = () => this.handleEmailConfirmationCheck(modal, addMessageCallback, updateUICallback);
        
        if (addMessageCallback) {
          addMessageCallback('bot', '📧 Email sent! Please confirm your subscription to continue chatting.');
        }
      } else {
        modal.remove();
        if (addMessageCallback) {
          addMessageCallback('bot', `✅ Chat activated! You now have ${this.config.limit_after_email_confirmed} extra messages.`);
        }
      }
    } else {
      status.innerHTML = '<div class="status-error">Problem saving email. Try again.</div>';
      emailBtn.textContent = 'Unlock';
    }
    
    emailBtn.disabled = false;
    if (updateUICallback) updateUICallback();
  }

  async handleEmailConfirmationCheck(modal, addMessageCallback, updateUICallback) {
    const emailBtn = modal.querySelector('#modalEmailBtn');
    const status = modal.querySelector('#modalStatus');
    
    emailBtn.disabled = true;
    emailBtn.textContent = 'Checking...';
    
    const result = await this.checkConfirmation();
    
    if (result.success) {
      modal.remove();
      if (addMessageCallback) {
        addMessageCallback('bot', `✅ Email confirmed! Chat activated with ${this.config.limit_after_email_confirmed} extra messages.`);
      }
      if (updateUICallback) updateUICallback();
    } else {
      status.innerHTML = '<div class="status-error">Email not confirmed yet. Please check your inbox and spam folder.</div>';
      emailBtn.textContent = 'Check Again';
      emailBtn.disabled = false;
    }
  }

  /* ---------- Generic Daily Limit Modal ---------- */
  showDailyLimitModal(config = {}) {
    return this.showModal({
      modalClass: config.modalClass || 'modal',
      contentClass: config.contentClass || 'modal-content',
      title: 'Daily Limit Reached',
      message: `You've used all your messages for today. Come back tomorrow (${this.getTomorrowDate()}) to continue chatting!`,
      hasInput: false,
      buttons: [
        {
          id: 'dailyLimitOkBtn',
          text: 'OK',
          handler: (modal) => modal.remove()
        }
      ]
    });
  }

  /* ---------- Generic Form Handler ---------- */
  setupFormHandler(formElement, inputElement, callbacks) {
    const { addMessage, showTyping, hideTyping, updateUI } = callbacks;
    
    formElement.onsubmit = async (e) => {
      e.preventDefault();
      const question = inputElement.value.trim();
      if (!question) return;

      // Agregar mensaje del usuario
      addMessage('user', question);
      inputElement.value = '';
      showTyping();

      // Enviar mensaje usando el core
      const result = await this.sendMessage(question);
      hideTyping();

      if (result.success) {
        addMessage('bot', result.data.answer);
        updateUI();
        
        // Verificar si se alcanzó el límite después del mensaje exitoso
        if (this.getState().remaining === 0) {
          addMessage('bot', '⏰ Daily message limit reached. You can ask more questions tomorrow!');
          
          if (!result.data.user_has_email) {
            this.handleEmailConfirmationFlow(addMessage, updateUI);
          } else {
            this.showDailyLimitModal();
          }
        }
      } else if (result.data?.reason === 'limit') {
        // Actualizar estado del core
        this.remaining = result.data.remaining || 0;
        this.userHasEmail = result.data.user_has_email;
        
        addMessage('bot', '⏰ Daily message limit reached. You can ask more questions tomorrow!');
        
        if (!result.data.user_has_email) {
          this.handleEmailConfirmationFlow(addMessage, updateUI);
        } else {
          this.showDailyLimitModal();
        }
      } else {
        addMessage('bot', 'Error. Please try again later.');
      }
      
      updateUI();
    };
  }

  // ✅ TAMBIÉN agregar método para configurar auto-inicialización
  setupAutoInitialization(callbacks) {
    const { addMessage, updateUI } = callbacks;
    
    // Configurar listeners para eventos del core
    this.on('state_updated', () => {
      updateUI();
    });

    this.on('limit_reached', () => {
      if (!this.getState().userHasEmail) {
        this.handleEmailConfirmationFlow(addMessage, updateUI);
      }
    });

    this.on('email_confirmation_required', () => {
      updateUI();
    });

    this.on('email_confirmed', () => {
      updateUI();
    });

    // Inicializar estado y mostrar modal de email si es necesario
    this.initializeChatState().then(() => {
      updateUI();
      
      const state = this.getState();
      if (state.remaining <= 0 && !state.userHasEmail) {
        setTimeout(() => {
          addMessage('bot', '⏰ You\'ve reached your daily limit. Enter your email to get more messages!');
          this.handleEmailConfirmationFlow(addMessage, updateUI);
        }, 500);
      }
    });
  }

  /* ---------- Common UI Helpers ---------- */
  static createTypingIndicator() {
    const indicator = document.createElement('span');
    indicator.className = 'typing-indicator';
    indicator.innerHTML = `
      <span class="typing-dot"></span>
      <span class="typing-dot"></span>
      <span class="typing-dot"></span>
    `;
    return indicator;
  }

  // Removed getCommonStyles - styles now in chat-styles.css
}

// Hacer disponible globalmente
window.DoitChatCore = DoitChatCore;