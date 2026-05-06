// NOTIFICACIONES

// CONTENEDOR TOASTS
function getToastContainer() {
    let c = document.getElementById('edubook-toasts');
    if (!c) {
        c = document.createElement('div');
        c.id = 'edubook-toasts';
        c.className = 'toast-container';
        document.body.appendChild(c);
    }
    return c;
}

// ICONOS
const ICONS = {
    error: `<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`,
    success: `<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>`,
    info: `<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>`,
};

// TOAST
function showToast(message, type = 'error', title = null) {
    const container = getToastContainer();
    const titles = { error: 'Error', success: '¡Éxito!', info: 'Aviso' };
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        ${ICONS[type] || ICONS.info}
        <div class="toast-body">
            <div class="toast-title">${title || titles[type]}</div>
            <div class="toast-msg">${message}</div>
        </div>
        <button class="toast-close" aria-label="Cerrar">✕</button>
    `;

    toast.querySelector('.toast-close').addEventListener('click', () => dismissToast(toast));
    container.appendChild(toast);
    setTimeout(() => dismissToast(toast), 5000);
    return toast;
}

function dismissToast(toast) {
    if (toast.classList.contains('hiding')) return;
    toast.classList.add('hiding');
    toast.addEventListener('animationend', () => toast.remove(), { once: true });
}

// ALERTA FORMULARIO
function showFormAlert(form, message, type = 'error') {
    const prev = form.querySelector('.alerta-form');
    if (prev) prev.remove();

    const div = document.createElement('div');
    div.className = `alerta-form alerta-${type}`;
    div.innerHTML = `${ICONS[type] || ICONS.info}<span>${message}</span>`;
    form.insertBefore(div, form.firstChild);

    setTimeout(() => {
        div.style.transition = 'opacity 0.4s';
        div.style.opacity = '0';
        setTimeout(() => div.remove(), 400);
    }, 6000);
}

// FORMULARIOS
document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const actionFile = form.getAttribute('action');
            if (!actionFile) return;

            // LIMPIAR ALERTA PREVIA
            const prev = form.querySelector('.alerta-form');
            if (prev) prev.remove();

            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn ? submitBtn.innerText : '';

            // VALIDACION CLIENTE (contraseñas)
            if (actionFile.includes('register.php') || actionFile.includes('registro.php')) {
                const pass = form.querySelector('[name="password"]');
                const passConfirm = form.querySelector('[name="password_confirm"]');
                if (pass && passConfirm && pass.value !== passConfirm.value) {
                    showFormAlert(form, 'Las contraseñas no coinciden.', 'error');
                    return;
                }
            }

            // DETECTAR SI EL FORMULARIO TIENE ARCHIVOS
            const hasFiles = form.enctype === 'multipart/form-data';

            try {
                if (submitBtn) {
                    submitBtn.innerText = 'Procesando...';
                    submitBtn.disabled = true;
                }

                let response;

                if (hasFiles) {
                    // FormData real para formularios con archivos (manager con foto)
                    const formData = new FormData(form);
                    response = await fetch(actionFile, {
                        method: 'POST',
                        body: formData   // sin Content-Type: el navegador lo pone con boundary
                    });
                } else {
                    // JSON para formularios sin archivos (login, explorador)
                    const formData = new FormData(form);
                    const data = Object.fromEntries(formData.entries());
                    response = await fetch(actionFile, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    });
                }

                let result;
                const rawText = await response.text();
                try {
                    result = JSON.parse(rawText);
                } catch (_) {
                    showFormAlert(form, 'Error inesperado del servidor. Revisa la consola.', 'error');
                    showToast('El servidor devolvió una respuesta inválida.', 'error', 'Error de servidor');
                    console.error('Respuesta no-JSON del servidor:', rawText);
                    return;
                }

                // EXITO
                if (response.ok && result.status === 'ok') {
                    const esRegistro = actionFile.includes('register');
                    showFormAlert(form, result.message || (esRegistro ? '¡Cuenta creada! Redirigiendo...' : '¡Sesión iniciada! Redirigiendo...'), 'success');
                    showToast(result.message || 'Bienvenido a EduBook', 'success', '¡Acceso concedido!');

                    setTimeout(() => {
                        if (esRegistro) {
                            window.location.href = 'login.php'; // Tras registro → iniciar sesión
                        } else {
                            window.location.href = 'profile.php'; // Tras login → perfil
                        }
                    }, 1200);

                    // ERROR
                } else {
                    const msg = result.message || 'No se pudo completar la operación.';
                    showFormAlert(form, msg, 'error');
                    showToast(msg, 'error');
                }

            } catch (networkError) {
                console.error('Error de red:', networkError);
                const msg = 'No se pudo conectar con el servidor. Comprueba tu conexión.';
                showFormAlert(form, msg, 'error');
                showToast(msg, 'error', 'Error de conexión');
            } finally {
                if (submitBtn) {
                    submitBtn.innerText = originalText;
                    submitBtn.disabled = false;
                }
            }
        });
    });
});