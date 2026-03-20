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

            // Validacion de coincidencia de contraseñas en el registro
            if (actionFile.includes('register.php') || actionFile.includes('registro.php')) {
                const pass = form.querySelector('#pass').value;
                const passConfirm = form.querySelector('#pass-confirm').value;
                
                if (pass !== passConfirm) {
                    alert('Error: Las contraseñas no coinciden.');
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

                // Lectura de la respuesta en formato texto para diagnóstico
                const textResponse = await response.text();
                let result;
                
                try {
                    result = JSON.parse(textResponse);
                } catch (parseError) {
                    console.error("Error critico del servidor PHP:");
                    console.error(textResponse);
                    alert("Error: El servidor no devolvió un formato JSON válido. Consulte la consola para más detalles.");
                    return;
                }

                if (response.ok && (result.status === 'ok' || result.user)) {
                    alert('Operacion exitosa: ' + (result.message || 'Acceso concedido.'));
                    window.location.href = 'index.html'; 
                } else {
                    alert('Error en la operacion: ' + (result.message || 'No se pudo completar la solicitud.'));
                }

            } catch (error) {
                console.error('Error de red:', error);
                alert('Error: No se pudo establecer conexion con el servidor.');
            } finally {
                submitBtn.innerText = originalBtnText;
                submitBtn.disabled = false;
            }
        });
    });
});