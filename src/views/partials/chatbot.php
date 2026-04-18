<?php
// Prompt del sistema embebido — describe el proyecto EduBook al completo
$CHATBOT_SYSTEM_PROMPT = <<<'PROMPT'
Eres el asistente virtual de EduBook, una plataforma web universitaria de gestión de eventos académicos desarrollada como práctica de la asignatura de Desarrollo Web.

## Sobre el proyecto EduBook

EduBook es una aplicación web que permite a estudiantes universitarios descubrir, guardar y apuntarse a eventos académicos (charlas, hackathons, workshops, jornadas), y a gestores universitarios (Managers) publicar y administrar sus propios eventos.

## Stack tecnológico

- **Frontend:** HTML5 semántico, CSS3 Vanilla en un único archivo `style.css` (dark mode, CSS Custom Properties, Grid y Flexbox), JavaScript ES6+ con Fetch API y FormData para peticiones asíncronas
- **Backend:** PHP 8.x con patrón MVC (Modelo-Vista-Controlador)
- **Autenticación:** n8n (orquestador serverless de workflows) + Supabase (base de datos PostgreSQL en la nube). El propio docente autorizó esta arquitectura como equivalente a MySQLi
- **Persistencia local:** Archivos JSON (`data/events.json`, `data/avatars.json`, `data/roles.json`, `data/users_data.json`)
- **Imágenes:** Sistema de ficheros PHP para almacenar imágenes de eventos y avatares de usuario

## Arquitectura MVC

```
src/
 ├── models/          → User.php, Event.php (acceso a datos)
 ├── controllers/     → UserController.php, rbac.php, auth_protect.php, logout.php
 └── views/           → login.php, register.php, profile.php, index.php, buscar.php...
config/               → webhooks.php (URLs de n8n y constantes)
data/                 → events.json, avatars.json, roles.json, users_data.json
workflows/            → EduBook - Login.json, EduBook - Registro.json (workflows de n8n)
```

## Roles y control de acceso (RBAC)

- **Explorador** (usuario estándar): puede ver y buscar eventos, apuntarse, gestionar su calendario y favoritos. No puede crear ni editar eventos.
- **Manager** (usuario administrador): puede crear, editar y eliminar sus propios eventos, subir imágenes, cambiar su foto de perfil. Tiene acceso al Panel de Gestión.
- Los roles se determinan al registrarse y se persisten en n8n/Supabase + localmente en `roles.json`. El formulario de login no puede cambiar el rol registrado.
- La protección de rutas se hace mediante `auth_protect.php` (requiere sesión) y `rbac.php` (requiere rol concreto).

## Funcionalidades implementadas

1. **Login:** Un único formulario para ambos roles. Valida contra Supabase vía n8n. Redirige al perfil si OK, muestra error descriptivo si falla. Contraseña incorrecta → bloqueado (validación robusta via access_token).
2. **Registro:** Dos formularios en tabs (Explorador / Manager). Manager puede subir foto de perfil al registrarse. Detecta email ya registrado con mensaje en español.
3. **Logout:** Destruye la sesión PHP y redirige al login.
4. **Perfil:** Muestra nombre, email, rol y universidad del usuario. Manager puede cambiar foto; el avatar persiste en avatars.json y se recarga en cada login.
5. **Eventos:** CRUD completo para Managers. Los eventos del sistema (creador_id: "system") son permanentes y no se pueden eliminar.
6. **Buscador:** Filtrado en tiempo real por texto, categoría y modalidad.
7. **Persistencia de datos de usuario:** nombre y universidad guardados en users_data.json al registrarse, cargados al hacer login si n8n no los devuelve.
8. **Chatbot:** Este asistente integrado en la plataforma, conectado a través de un webhook de n8n.

## Clases principales

- **UserController:** métodos `login()`, `register()`, `logout()`, `saveAvatar()`, `loadAvatar()`, `saveUserData()`, `loadUserData()`, `loadRol()`
- **User (Model):** `loginUser()`, `registerUser()` — comunica con n8n via cURL
- **Event (Model):** `getAll()`, `getRecent()`, `getByCreator()`, `getById()`, `create()`, `update()`, `delete()`

## Validaciones servidor (UserController)

- Formato de email: `filter_var($email, FILTER_VALIDATE_EMAIL)`
- Longitud de contraseña: mínimo 8 caracteres
- Campos obligatorios vacíos
- Rol válido: solo 'manager' o 'explorador'

## Flujo de login (diagrama simplificado)

Usuario → login.php → UserController::login() → User::loginUser() → cURL → n8n webhook → Supabase auth → respuesta con user_metadata (nombre, universidad, rol) → $_SESSION → perfil

## Decisiones de diseño importantes

- Los estilos CSS están consolidados en un único `style.css` sin ningún estilo inline en los PHPs
- El rol al hacer login se resuelve en este orden: n8n/Supabase → roles.json local → selección del formulario (nunca puede subir de rol)
- Los eventos del sistema tienen `creador_id: "system"` y son inmutables
- El chatbot (este asistente) está integrado mediante un widget flotante conectado a n8n

## Cómo responder

- Responde siempre en español de forma clara y concisa
- Si preguntan por tecnologías, menciona el stack exacto
- Si preguntan por la arquitectura, explica MVC con los ficheros reales
- Si preguntan cómo funciona el login, explica el flujo completo
- Si preguntan por la base de datos, explica que se usa Supabase (PostgreSQL) vía n8n, autorizado por el docente
- Sé amable y técnico a la vez — puedes hablar tanto con el profesor como con otros estudiantes
PROMPT;
?>

<!-- CHATBOT WIDGET -->
<div id="chatbot-widget">

    <!-- BOTÓN FLOTANTE -->
    <button id="chatbot-toggle" onclick="toggleChat()" aria-label="Abrir asistente EduBook">
        <svg id="chat-icon-open" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
        </svg>
        <svg id="chat-icon-close" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" style="display:none">
            <line x1="18" y1="6" x2="6" y2="18" />
            <line x1="6" y1="6" x2="18" y2="18" />
        </svg>
        <span id="chatbot-badge" style="display:none">1</span>
    </button>

    <!-- PANEL DEL CHAT -->
    <div id="chatbot-panel" style="display:none">
        <div id="chatbot-header">
            <div style="display:flex;align-items:center;gap:10px">
                <div id="chatbot-avatar">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                    </svg>
                </div>
                <div>
                    <div style="font-weight:700;font-size:0.95rem;color:#fff">Asistente EduBook</div>
                    <div style="font-size:0.75rem;color:#86efac;display:flex;align-items:center;gap:5px">
                        <span
                            style="width:7px;height:7px;background:#22c55e;border-radius:50%;display:inline-block"></span>
                        En línea
                    </div>
                </div>
            </div>
            <button onclick="toggleChat()"
                style="background:none;border:none;color:var(--color-texto-gris);cursor:pointer;font-size:1.2rem;line-height:1">✕</button>
        </div>

        <div id="chatbot-messages">
            <div class="chat-msg bot">
                <div class="chat-bubble">
                    ¡Hola! 👋 Soy el asistente de <strong>EduBook</strong>. Puedo explicarte cómo funciona la
                    plataforma, las tecnologías que usamos, la arquitectura del proyecto o cualquier duda que tengas.
                    ¿En qué te ayudo?
                </div>
            </div>
        </div>

        <div id="chatbot-input-area">
            <input type="text" id="chatbot-input" placeholder="Escribe tu pregunta..." autocomplete="off"
                onkeydown="if(event.key==='Enter')sendChatMessage()">
            <button id="chatbot-send" onclick="sendChatMessage()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="22" y1="2" x2="11" y2="13" />
                    <polygon points="22 2 15 22 11 13 2 9 22 2" />
                </svg>
            </button>
        </div>
    </div>
</div>

<style>
    /* CHATBOT */
    #chatbot-widget {
        position: fixed;
        bottom: 28px;
        right: 28px;
        z-index: 9999;
        font-family: 'Segoe UI', sans-serif;
    }

    #chatbot-toggle {
        width: 58px;
        height: 58px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--color-acento), #c4b46a);
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0f2d3c;
        box-shadow: 0 6px 24px rgba(166, 155, 93, 0.45);
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
    }

    #chatbot-toggle:hover {
        transform: scale(1.08);
        box-shadow: 0 8px 30px rgba(166, 155, 93, 0.6);
    }

    #chatbot-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #ef4444;
        color: #fff;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #chatbot-panel {
        position: absolute;
        bottom: 72px;
        right: 0;
        width: 360px;
        border-radius: 16px;
        background: #0e2535;
        border: 1px solid var(--color-borde);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.55);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        animation: chatSlideIn 0.25s ease;
    }

    @keyframes chatSlideIn {
        from {
            opacity: 0;
            transform: translateY(10px) scale(0.97);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    #chatbot-header {
        background: linear-gradient(135deg, #0a1f29, #133240);
        padding: 16px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--color-borde);
    }

    #chatbot-avatar {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, var(--color-acento), #c4b46a);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0f2d3c;
    }

    #chatbot-messages {
        flex: 1;
        padding: 18px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 12px;
        max-height: 360px;
        min-height: 200px;
    }

    #chatbot-messages::-webkit-scrollbar {
        width: 4px;
    }

    #chatbot-messages::-webkit-scrollbar-thumb {
        background: var(--color-borde);
        border-radius: 4px;
    }

    .chat-msg {
        display: flex;
        align-items: flex-end;
        gap: 8px;
    }

    .chat-msg.user {
        flex-direction: row-reverse;
    }

    .chat-bubble {
        max-width: 82%;
        padding: 10px 14px;
        border-radius: 14px;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .chat-msg.bot .chat-bubble {
        background: #1a3a4a;
        color: #e2e8f0;
        border-bottom-left-radius: 4px;
    }

    .chat-msg.user .chat-bubble {
        background: linear-gradient(135deg, var(--color-acento), #c4b46a);
        color: #0f2d3c;
        font-weight: 600;
        border-bottom-right-radius: 4px;
    }

    .chat-typing {
        display: flex;
        gap: 5px;
        padding: 10px 14px;
        background: #1a3a4a;
        border-radius: 14px;
        border-bottom-left-radius: 4px;
        width: fit-content;
    }

    .chat-typing span {
        width: 7px;
        height: 7px;
        background: var(--color-acento);
        border-radius: 50%;
        animation: typingBounce 1.2s infinite;
    }

    .chat-typing span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .chat-typing span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typingBounce {

        0%,
        60%,
        100% {
            transform: translateY(0);
        }

        30% {
            transform: translateY(-6px);
        }
    }

    #chatbot-input-area {
        display: flex;
        gap: 8px;
        padding: 14px 16px;
        border-top: 1px solid var(--color-borde);
        background: #0a1f29;
    }

    #chatbot-input {
        flex: 1;
        background: #1a3a4a;
        border: 1px solid var(--color-borde);
        border-radius: 8px;
        padding: 10px 14px;
        color: #fff;
        font-size: 0.875rem;
        outline: none;
        transition: border-color 0.2s;
    }

    #chatbot-input:focus {
        border-color: var(--color-acento);
    }

    #chatbot-input::placeholder {
        color: var(--color-texto-gris);
    }

    #chatbot-send {
        width: 42px;
        height: 42px;
        border-radius: 8px;
        background: var(--color-acento);
        border: none;
        color: #0f2d3c;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: opacity 0.2s;
    }

    #chatbot-send:hover {
        opacity: 0.85;
    }

    #chatbot-send:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    @media (max-width: 480px) {
        #chatbot-widget {
            bottom: 85px;
            right: 16px;
        }

        #chatbot-panel {
            width: calc(100vw - 32px);
            right: -16px;
        }
    }
</style>

<script>
    // CHATBOT
    const CHATBOT_ENDPOINT = '<?php echo defined("N8N_CHATBOT_URL") ? N8N_CHATBOT_URL : "https://n8n.kairasystems.com/webhook/edubook-chatbot/chat"; ?>';
    const SYSTEM_PROMPT = <?php echo json_encode($CHATBOT_SYSTEM_PROMPT); ?>;

    let chatOpen = false;
    let chatSessionId = 'session_' + Math.random().toString(36).substr(2, 9);

    function toggleChat() {
        chatOpen = !chatOpen;
        const panel = document.getElementById('chatbot-panel');
        const iconO = document.getElementById('chat-icon-open');
        const iconC = document.getElementById('chat-icon-close');
        const badge = document.getElementById('chatbot-badge');

        panel.style.display = chatOpen ? 'flex' : 'none';
        iconO.style.display = chatOpen ? 'none' : 'block';
        iconC.style.display = chatOpen ? 'block' : 'none';
        badge.style.display = 'none';

        if (chatOpen) {
            setTimeout(() => document.getElementById('chatbot-input').focus(), 100);
            scrollChatBottom();
        }
    }

    function appendMessage(text, role) {
        const msgs = document.getElementById('chatbot-messages');
        const div = document.createElement('div');
        div.className = `chat-msg ${role}`;
        div.innerHTML = `<div class="chat-bubble">${text}</div>`;
        msgs.appendChild(div);
        scrollChatBottom();
        return div;
    }

    function showTyping() {
        const msgs = document.getElementById('chatbot-messages');
        const div = document.createElement('div');
        div.className = 'chat-msg bot';
        div.id = 'chat-typing-indicator';
        div.innerHTML = `<div class="chat-typing"><span></span><span></span><span></span></div>`;
        msgs.appendChild(div);
        scrollChatBottom();
    }

    function removeTyping() {
        const el = document.getElementById('chat-typing-indicator');
        if (el) el.remove();
    }

    function scrollChatBottom() {
        const msgs = document.getElementById('chatbot-messages');
        msgs.scrollTop = msgs.scrollHeight;
    }

    async function sendChatMessage() {
        const input = document.getElementById('chatbot-input');
        const sendBtn = document.getElementById('chatbot-send');
        const message = input.value.trim();
        if (!message) return;

        input.value = '';
        input.disabled = true;
        sendBtn.disabled = true;

        appendMessage(message, 'user');
        showTyping();

        try {
            const response = await fetch(CHATBOT_ENDPOINT, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    chatInput: message,
                    sessionId: chatSessionId,
                    systemPrompt: SYSTEM_PROMPT
                })
            });

            removeTyping();

            if (!response.ok) throw new Error('Error en la respuesta del servidor');

            const data = await response.json();

            // n8n puede devolver la respuesta en distintos campos
            const reply =
                data.output ||
                data.text ||
                data.message ||
                data.response ||
                data.chatResponse ||
                (Array.isArray(data) && (data[0]?.output || data[0]?.text)) ||
                'No pude obtener una respuesta. Inténtalo de nuevo.';

            appendMessage(reply.replace(/\n/g, '<br>'), 'bot');

            // Si el chat está cerrado, mostrar badge
            if (!chatOpen) {
                document.getElementById('chatbot-badge').style.display = 'flex';
            }

        } catch (err) {
            removeTyping();
            appendMessage('⚠️ Error al conectar con el asistente. Comprueba tu conexión.', 'bot');
            console.error('Chatbot error:', err);
        } finally {
            input.disabled = false;
            sendBtn.disabled = false;
            input.focus();
        }
    }
</script>