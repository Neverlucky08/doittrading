/**
 * DoIt Chat Inline - Interfaz integrada (sin estilos inline)
 * Los estilos ahora están en chat-styles.css
 */
(() => {
  if (!window.DoitChatCore || !window.doitChatCfg) {
    // console.error('DoIt Chat Inline: Core no disponible');
    return;
  }

  // Buscar el contenedor del shortcode
  const container = document.getElementById('doit-chat-wrapper');
  if (!container) {
    // console.error('DoIt Chat Inline: Contenedor no encontrado');
    return;
  }

  const core = new DoitChatCore(window.doitChatCfg);

  // Crear interfaz inline
  container.innerHTML = `
    <div id="doitChatInline">
      <div id="doitChatInlineHeader">
        <h1>AI Trading Assistant</h1>
        <p>Ask me anything about trading, indicators, or Expert Advisors</p>
      </div>
      <div id="doitChatInlineBody"></div>
      <div id="doitChatInlineFooter">
        <div id="doitChatInlineStatus">
          <span>${core.getRemainingText()}</span>
        </div>
        <form id="doitChatInlineForm">
          <input 
            id="doitChatInlineInput" 
            type="text" 
            placeholder="Type your question here..."
            autocomplete="off"
          />
          <button type="submit" id="doitChatInlineSend">Send</button>
        </form>
      </div>
    </div>
  `;

  // Referencias a elementos
  const body = document.getElementById('doitChatInlineBody');
  const input = document.getElementById('doitChatInlineInput');
  const send = document.getElementById('doitChatInlineSend');
  const form = document.getElementById('doitChatInlineForm');
  const status = document.getElementById('doitChatInlineStatus');

  // Funciones de UI
  function addMessage(type, text) {
    const msgWrapper = document.createElement('div');
    msgWrapper.className = `message ${type}`;
    
    const msgContent = document.createElement('div');
    msgContent.className = 'message-content';
    msgContent.textContent = text;
    
    msgWrapper.appendChild(msgContent);
    body.appendChild(msgWrapper);
    body.scrollTop = body.scrollHeight;
  }

  function showTyping() {
    const typing = document.createElement('div');
    typing.className = 'typing';
    typing.id = 'typingIndicator';
    typing.innerHTML = 'AI is thinking' + DoitChatCore.createTypingIndicator().outerHTML;
    body.appendChild(typing);
    body.scrollTop = body.scrollHeight;
  }

  function hideTyping() {
    const typing = document.getElementById('typingIndicator');
    if (typing) typing.remove();
  }

  function updateUI() {
    const state = core.getState();
    status.innerHTML = `<span>${core.getRemainingText()}</span>`;
    
    if (state.canChat) {
      input.disabled = false;
      send.disabled = false;
      input.placeholder = "Type your question here...";
    } else if (state.waitingConfirmation) {
      input.disabled = true;
      send.disabled = true;
      input.placeholder = "Please check your email to continue...";
    } else {
      input.disabled = true;
      send.disabled = true;
      input.placeholder = "Daily limit reached. Come back tomorrow!";
    }
  }

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

  // Focus inicial
  input.focus();
})();