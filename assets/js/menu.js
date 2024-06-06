// Seleccionar el elemento del botón del menú
const menuButton = document.querySelector('.menu-button');
// Seleccionar el elemento del icono de la flecha
const arrowIcon = document.querySelector('#arrow-icon');
// Seleccionar el elemento de la columna izquierda
const sideColumn = document.querySelector('.columna-izquierda');

// Agregar un evento de clic al botón del menú
menuButton.addEventListener('click', () => {
    // Alternar la clase 'open' en la columna izquierda para mostrar u ocultar el menú
    sideColumn.classList.toggle('open');
    // Alternar la clase 'arrow-left' en el icono de la flecha para cambiar su dirección
    arrowIcon.classList.toggle('arrow-left');
});

