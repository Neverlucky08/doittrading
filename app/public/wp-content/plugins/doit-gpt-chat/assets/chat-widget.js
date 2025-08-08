/**
 * DoIt Chat Widget - Widget básico (sin estilos inline)
 * Los estilos ahora están en chat-styles.css
 */
(() => {
  /* ---------- UI ---------- */
  console.log('Widget cargado', doitChatCfg);
  const box = document.createElement('div');
  box.id = 'doitChatBox';
  box.innerHTML = `
      <div id="doitChatHeader">💬  Ask Diego – AI assistant</div>
      <div id="doitChatBody"></div>
      <div id="doitMsgsLeft">${doitChatCfg.limit} messages left</div>
      <form id="doitChatForm">
         <input id="doitChatInput" autocomplete="off" placeholder="Write a question…" />
         <button id="doitChatSend">➤</button>
      </form>`;
  document.body.appendChild(box);

  const body   = document.getElementById('doitChatBody');
  const input  = document.getElementById('doitChatInput');
  const left   = document.getElementById('doitMsgsLeft');
  const form   = document.getElementById('doitChatForm');
  const sendBtn = document.getElementById('doitChatSend');

  let remaining = doitChatCfg.limit;
  let waitingConfirmation = false;
  let userHasEmail = false;

  /* ---------- helpers ---------- */
  const add = (cls, txt) => {
     const div = document.createElement('div');
     div.className = cls;
     div.textContent = txt;
     body.appendChild(div);
     body.scrollTop = body.scrollHeight;
  };

  const showTyping = () => {
      const div = document.createElement('div');
      div.id = 'doitTyping';
      div.className = 'bot typing';
      div.innerHTML = 'Diego AI is thinking<span class="typing-indicator"><span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span></span>';
      body.appendChild(div);
      body.appendChild(div);
      body.scrollTop = body.scrollHeight;
   };

   const hideTyping = () => {
      const t = document.getElementById('doitTyping');
      if (t) t.remove();
   };

  const disableChat = () => {
    input.disabled = true;
    sendBtn.disabled = true;
    input.placeholder = "Chat disabled - confirm email first";
  };

  const enableChat = () => {
    input.disabled = false;
    sendBtn.disabled = false;
    input.placeholder = "Write a question…";
  };

  const disableChatUntilTomorrow = () => {
    input.disabled = true;
    sendBtn.disabled = true;
    input.placeholder = "Daily limit reached - try again tomorrow";
  };

  /* ---------- submit ---------- */
  form.addEventListener('submit', async e=>{
      e.preventDefault();
      const q = input.value.trim();
      if(!q || remaining<=0 || waitingConfirmation) return;
      
      add('user', q);
      input.value = '';
      showTyping();                  // typing animation

      const res = await fetch( doitChatCfg.ajax, {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body: new URLSearchParams({
          action:'doit_chat_ask',
          nonce: doitChatCfg.nonce,
          q: q
        })
      }).then(r=>r.json());

      hideTyping();                  // stop typing animation

      if(res.success){
         add('bot', res.data.answer);
         remaining = res.data.remaining;
         userHasEmail = res.data.user_has_email; // Actualizar estado del email
         left.textContent = `${remaining} messages left`;
         
         if(remaining === 0) {
            // SIEMPRE mostrar mensaje de límite diario alcanzado
            add('bot', '⏰ Daily message limit reached. You can ask more questions tomorrow!');
            showDailyLimitReached();
         }
      }else if(res.data.reason === 'limit'){
         // Actualizar remaining si viene en la respuesta
         if(res.data.remaining !== undefined) {
            remaining = res.data.remaining;
            left.textContent = `${remaining} messages left`;
         }
         
         // Actualizar estado del email
         userHasEmail = res.data.user_has_email;
         
         // SIEMPRE mostrar límite diario - no importa si tiene email o no
         add('bot', '⏰ Daily message limit reached. You can ask more questions tomorrow!');
         showDailyLimitReached();
         
      }else{
         add('bot','Error. Try later.');
      }
  });

  /* ---------- modal daily limit ---------- */
  function showDailyLimitReached(){
     const tomorrow = new Date();
     tomorrow.setDate(tomorrow.getDate() + 1);
     const tomorrowStr = tomorrow.toLocaleDateString();
     
     const modal = document.createElement('div');
     modal.id = 'doitModal';
     modal.className = 'modal';
     modal.innerHTML = `
         <div class="modal-content">
           <h3>Daily Limit Reached</h3>
           <p>You've used all your messages for today. Come back tomorrow (${tomorrowStr}) to continue chatting!</p>
           <button id="doitCloseBtn">OK</button>
         </div>`;
     document.body.appendChild(modal);
     
     const closeBtn = document.getElementById('doitCloseBtn');
     closeBtn.onclick = () => {
        modal.remove();
        disableChatUntilTomorrow();
     };
     
     // Deshabilitar el chat
     disableChatUntilTomorrow();
  }

  /* ---------- modal e-mail ---------- */
  function showModal(){
     const modal = document.createElement('div');
     modal.id = 'doitModal';
     modal.className = 'modal';
     modal.innerHTML = `
         <div class="modal-content">
           <h3>Need more messages?</h3>
           <p>Leave your e-mail and get <b>${doitChatCfg.limit_after_email_confirmed} extra</b> questions.</p>
           <input id="doitEmail" type="email" placeholder="you@mail.com">
           <button id="doitEmailBtn">Unlock</button>
           <div id="emailStatus"></div>
         </div>`;
     document.body.appendChild(modal);
     
     const emailBtn = document.getElementById('doitEmailBtn');
     const emailStatus = document.getElementById('emailStatus');
     
     emailBtn.onclick = async ()=>{
        const email = document.getElementById('doitEmail').value.trim();
        if(!email) return alert('Type an e-mail');
        
        emailBtn.disabled = true;
        emailBtn.textContent = 'Sending...';
        
        const res = await fetch( doitChatCfg.ajax, {
           method:'POST',
           headers:{'Content-Type':'application/x-www-form-urlencoded'},
           body: new URLSearchParams({
             action:'doit_chat_email',
             nonce: doitChatCfg.nonce,
             email: email
           })
        }).then(r=>r.json());
        
        if(res.success){
           // Marcar que el usuario ahora tiene email
           userHasEmail = true;
           
           // Verificar si requiere confirmación
           if(res.data.requires_confirmation){
              emailStatus.innerHTML = '<div class="confirmation-notice">Email sent! Check your inbox and confirm subscription to activate chat.</div>';
              emailBtn.textContent = 'Check Confirmation';
              emailBtn.disabled = false;
              emailBtn.onclick = checkConfirmation;
              
              // Marcar que estamos esperando confirmación
              waitingConfirmation = true;
              disableChat();
              
              // Agregar notice en el chat
              add('bot', '📧 Email sent! Please confirm your subscription to continue chatting.');
              
           } else {
              // Activación inmediata
              remaining = doitChatCfg.limit_after_email_confirmed;
              left.textContent = `${remaining} messages left`;
              modal.remove();
              add('bot', `✅ Chat activated! You now have ${doitChatCfg.limit_after_email_confirmed} extra messages.`);
           }
        }else{
           emailStatus.innerHTML = '<div class="status-error">Problem saving email. Try again.</div>';
           emailBtn.disabled = false;
           emailBtn.textContent = 'Unlock';
        }
     };
  }

  /* ---------- verificar confirmación ---------- */
  async function checkConfirmation(){
     const checkBtn = document.getElementById('doitEmailBtn');
     const emailStatus = document.getElementById('emailStatus');
     
     checkBtn.disabled = true;
     checkBtn.textContent = 'Checking...';
     
     try {
        const res = await fetch( doitChatCfg.ajax, {
           method:'POST',
           headers:{'Content-Type':'application/x-www-form-urlencoded'},
           body: new URLSearchParams({
             action:'doit_check_confirmation',
             nonce: doitChatCfg.nonce
           })
        }).then(r=>r.json());
        
        if(res.success){
           remaining = doitChatCfg.limit_after_email_confirmed;
           left.textContent = `${remaining} messages left`;
           waitingConfirmation = false;
           enableChat();
           
           // Cerrar modal
           const modal = document.getElementById('doitModal');
           if(modal) {
              modal.remove();
           }
           
           add('bot', `✅ Email confirmed! Chat activated with ${doitChatCfg.limit_after_email_confirmed} extra messages.`);
        } else {
           emailStatus.innerHTML = '<div class="status-error">Email not confirmed yet. Check your inbox and spam folder.</div>';
           checkBtn.disabled = false;
           checkBtn.textContent = 'Check Again';
        }
     } catch(error) {
        console.error('Error checking confirmation:', error);
        emailStatus.innerHTML = '<div class="status-error">Error checking confirmation. Try again.</div>';
        checkBtn.disabled = false;
        checkBtn.textContent = 'Check Again';
     }
  }

  /* ---------- Inicializar estado al cargar ---------- */
  // Verificar estado inicial al cargar la página
  async function initializeChatState() {
    try {
      const res = await fetch( doitChatCfg.ajax, {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body: new URLSearchParams({
          action:'doit_get_status',
          nonce: doitChatCfg.nonce
        })
      }).then(r=>r.json());
      
      if(res.success) {
        remaining = res.data.remaining;
        userHasEmail = res.data.user_has_email;
        
        // Actualizar UI con el estado real
        if(userHasEmail) {
          left.textContent = `${remaining} messages left (Email confirmed)`;
        } else {
          left.textContent = `${remaining} messages left`;
        }
      }
    } catch(error) {
      console.error('Error initializing chat state:', error);
    }
  }

  // Llamar al inicializar
  initializeChatState();
})();