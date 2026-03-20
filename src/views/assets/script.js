document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault(); 

            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerText;
            
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            const actionFile = form.getAttribute('action');

            // Corrección: ahora busca register.php o registro.php
            if (actionFile === 'registro.php' || actionFile === 'register.php') {
                const pass = form.querySelector('#pass').value;
                const passConfirm = form.querySelector('#pass-confirm').value;
                
                if (pass !== passConfirm) {
                    alert('❌ Las contraseñas no coinciden.');
                    return;
                }
            }

            try {
                submitBtn.innerText = 'Procesando...';
                submitBtn.disabled = true;

                const response = await fetch(actionFile, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                // MODO DIAGNÓSTICO: Leemos el texto crudo primero
                const textResponse = await response.text();
                let result;
                
                try {
                    result = JSON.parse(textResponse);
                } catch (parseError) {
                    console.error("🔥 ERROR CRÍTICO DEL SERVIDOR PHP 🔥");
                    console.error(textResponse);
                    alert("El servidor PHP falló. Abre la consola (F12) para ver el error real.");
                    return;
                }

                if (response.ok && (result.status === 'ok' || result.user)) {
                    alert('✅ ' + (result.message || 'Operación exitosa'));
                    window.location.href = 'index.html'; 
                } else {
                    alert('⚠️ ' + (result.message || 'Error en la operación'));
                }

            } catch (error) {
                console.error('Error de red:', error);
                alert('🚫 No se pudo conectar. Revisa tu consola (F12).');
            } finally {
                submitBtn.innerText = originalBtnText;
                submitBtn.disabled = false;
            }
        });
    });
});