/**
 * DoIt Chat Bubble - Interfaz flotante tipo bubble (sin estilos inline)
 * Los estilos ahora están en chat-styles.css
 */
(() => {
  if (!window.DoitChatCore || !window.doitChatCfg) {
    // console.error('DoIt Chat Bubble: Core no disponible');
    return;
  }

  const core = new DoitChatCore(window.doitChatCfg);
  let isOpen = false;

  // Crear bubble flotante
  const bubble = document.createElement('div');
  bubble.id = 'doitChatBubble';
  bubble.innerHTML = '💬';

  // Crear ventana de chat
  const chatBox = document.createElement('div');
  chatBox.id = 'doitChatBox';
  chatBox.className = 'bubble-mode';
  chatBox.innerHTML = `
    <div id="doitChatHeader">
      <span>💬 Ask Diego – AI assistant</span>
      <button id="doitChatClose">✕</button>
    </div>
    <div id="doitChatBody"></div>
    <div id="doitChatFooter">
      <div class="remaining">${core.getRemainingText()}</div>
      <form id="doitChatForm">
        <input id="doitChatInput" autocomplete="off" placeholder="Write a question…" />
        <button type="submit" id="doitChatSend">➤</button>
      </form>
    </div>
  `;

  // Agregar elementos al DOM
  document.body.appendChild(bubble);
  document.body.appendChild(chatBox);

  // Referencias a elementos
  const body = document.getElementById('doitChatBody');
  const input = document.getElementById('doitChatInput');
  const send = document.getElementById('doitChatSend');
  const form = document.getElementById('doitChatForm');
  const closeBtn = document.getElementById('doitChatClose');
  const remainingDiv = chatBox.querySelector('.remaining');

  // Funciones de UI
  function openChat() {
    isOpen = true;
    bubble.classList.add('open');
    chatBox.classList.add('open');
    input.focus();
  }

  function closeChat() {
    isOpen = false;
    bubble.classList.remove('open');
    chatBox.classList.remove('open');
  }

  function addMessage(type, text) {
    const msg = document.createElement('div');
    msg.className = `message ${type}`;
    msg.textContent = text;
    body.appendChild(msg);
    body.scrollTop = body.scrollHeight;
  }

  function showTyping() {
    const typing = document.createElement('div');
    typing.className = 'message bot typing';
    typing.innerHTML = 'Typing' + DoitChatCore.createTypingIndicator().outerHTML;
    typing.id = 'typingIndicator';
    body.appendChild(typing);
    body.scrollTop = body.scrollHeight;
  }

  function hideTyping() {
    const typing = document.getElementById('typingIndicator');
    if (typing) typing.remove();
  }

  function updateUI() {
    const state = core.getState();
    remainingDiv.textContent = core.getRemainingText();
    
    if (state.canChat) {
      input.disabled = false;
      send.disabled = false;
      input.placeholder = "Write a question…";
    } else if (state.waitingConfirmation) {
      input.disabled = true;
      send.disabled = true;
      input.placeholder = "Check your email to continue...";
    } else {
      input.disabled = true;
      send.disabled = true;
      input.placeholder = "Daily limit reached";
    }
  }

  // Event handlers
  bubble.onclick = openChat;
  closeBtn.onclick = closeChat;

  // Configurar el manejador del formulario usando el core
  core.setupFormHandler(form, input, {
    addMessage,
    showTyping,
    hideTyping,
    updateUI
  });

  // Configurar auto-inicialización
  core.setupAutoInitialization({
    addMessage,
    updateUI
  });

  // Mensaje de bienvenida
  setTimeout(() => {
    addMessage('bot', '👋 Hi! I\'m Diego, your AI assistant. How can I help you today?');
  }, 500);

  // Cerrar con ESC
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && isOpen) {
      closeChat();
    }
  });
})();