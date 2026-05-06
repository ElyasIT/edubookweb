# 8a PRÁCTICA, TAREA 3.5: Interacción con el front-end mediante jQuery

**Fecha:** 4 de Mayo de 2026  
**Módulo:** Diseño de Interfaces Web / Entorno Cliente  
**Integrantes del grupo:** [Nombre y Apellidos 1], [Nombre y Apellidos 2], [Nombre y Apellidos 3]  

---

## 1. Explicación de las diferentes interacciones jQuery desarrolladas

En esta actividad, se han implementado diversas interacciones en la parte frontal (front-end) utilizando la librería **jQuery** para mejorar la experiencia de usuario y cumplir con los requisitos del proyecto. Las funcionalidades se han integrado principalmente en la página de inicio de sesión (`login.php`) y en el panel principal (`index.php`).

### 1.1 Mensaje Modal (Recuperar Contraseña)
Se ha creado un mensaje modal para la acción de "Recuperar contraseña" (enlace "¿Olvidaste tu contraseña?").
- **Funcionamiento:** Al hacer clic sobre el enlace, se evita el comportamiento por defecto y se muestra un contenedor `div` que ocupa toda la pantalla con un fondo oscuro y transparente (`rgba(0,0,0,0.5)`). En el centro, se encuentra el cuadro de diálogo informativo.
- **Cierre:** El modal se oculta utilizando jQuery tanto si se hace clic fuera del cuadro blanco (sobre el fondo transparente) como si se pulsa el botón "De acuerdo".

### 1.2 Tooltip sobre Imagen (Logo)
Se ha añadido un mensaje descriptivo (tooltip) que se muestra al pasar el ratón sobre el logotipo principal de la aplicación.
- **Funcionamiento:** Se hace uso de los eventos `mouseenter`, `mouseleave` y `mousemove` de jQuery aplicados a la clase `.img-logo`.
- Cuando el ratón entra en la imagen, el mensaje se hace visible. Al mover el ratón, la posición `top` y `left` del tooltip se actualiza constantemente para que siga al cursor. Cuando el ratón sale de la imagen, el tooltip se oculta.

### 1.3 Aviso de uso de Cookies
Se ha implementado un control estricto de acceso basado en la aceptación de cookies.
- **Lógica de inicio:** Al cargar la página, se comprueba el estado de aceptación leyendo la variable `cookiesAccepted` de `localStorage`.
- **Estado pendiente:** Si no hay decisión, se oculta el botón de iniciar sesión y se muestra el banner informativo de cookies en la parte inferior de la pantalla.
- **Aceptación:** Si el usuario hace clic en "Aceptar", se guarda el valor `'true'` en `localStorage`, se oculta el banner y se muestra de nuevo el botón de iniciar sesión.
- **Rechazo:** Si el usuario rechaza las cookies, se guarda `'false'` en `localStorage`, se oculta el banner y, en lugar del botón de iniciar sesión, aparece un nuevo botón llamado "Mostrar aviso de cookies". Este botón permite volver a abrir el banner en cualquier momento para que el usuario cambie de decisión. Como resultado, no se puede iniciar sesión si no se aceptan explícitamente.

---

## 2. Explicación de la configuración y utilización de Slick Carousel

Para el apartado de mostrar información de manera circular (sliders), se ha integrado el plugin **Slick Carousel** en la página del panel de usuario (`index.php`).

Se han configurado dos carruseles diferentes, uno para mostrar "Eventos Destacados" y otro para "Organizadores y Promotores", ambos aplicando diseño responsivo para que se adapten a diferentes resoluciones de pantalla (Móvil, Tablet, Escritorio).

### 2.1 Slider de Eventos (Imágenes y Títulos)
Este slider destaca visualmente eventos con una imagen grande y su título.
- **Configuración principal:** Muestra 3 elementos a la vez (`slidesToShow: 3`) y se desplaza de uno en uno (`slidesToScroll: 1`). Incorpora indicadores de navegación por puntos en la parte inferior (`dots: true`).
- **Diseño Responsivo:**
  - En pantallas pequeñas (menos de 1024px): Se muestran 2 elementos.
  - En pantallas móviles (menos de 768px): Se muestra 1 solo elemento.
  - En pantallas muy pequeñas (menos de 480px): Se ocultan las flechas laterales (`arrows: false`) para ahorrar espacio y solo se puede desplazar de forma táctil (swipe).

### 2.2 Slider de Promotores (Información de texto)
Este slider es más dinámico, pensado para mostrar tarjetas informativas de los promotores.
- **Configuración principal:** Se ha configurado con reproducción automática (`autoplay: true`) avanzando cada 2 segundos (`autoplaySpeed: 2000`). En pantallas grandes, muestra 4 tarjetas simultáneamente y avanza de 2 en 2 (`slidesToScroll: 2`), permitiendo una visualización rápida. Se han desactivado los puntos de navegación inferiores para darle un aspecto más limpio.
- **Diseño Responsivo:**
  - Hasta 1024px: Muestra 3 tarjetas y avanza de 1 en 1.
  - Hasta 768px: Muestra 2 tarjetas.
  - Hasta 480px: Muestra solo 1 tarjeta por pantalla, optimizando la lectura.

### 2.3 Utilización de la librería
Para utilizar Slick se ha tenido que incluir:
1. Los archivos CSS del propio plugin desde CDN (tanto `slick.css` como `slick-theme.css`) para dar estilo base y apariencia (flechas, puntos).
2. El archivo de la librería de jQuery antes del código Javascript de Slick.
3. El archivo Javascript de Slick (`slick.min.js`).
4. La llamada al método `.slick({...})` apuntando a los contenedores HTML (`.slider-concerts` y `.slider-promotors`) dentro de la función `$(document).ready()` para asegurarnos de que la estructura HTML esté completamente cargada antes de inicializar el slider.
