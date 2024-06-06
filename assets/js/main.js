//Metemos todo el código en una función para que se ejecute cuando la página esté cargada
document.addEventListener('DOMContentLoaded', function () {
    // Barra de navegacion
    document.querySelectorAll('.playlist-card .options').forEach(item => {
        item.addEventListener('click', event => {
            event.stopPropagation(); // Evita que el clic se propague a elementos superiores
            item.querySelector('.dropdown-menu').classList.toggle('show'); // Muestra u oculta el menú
        });
    });

    // Cierra el menú desplegable si se hace clic fuera de él
    window.addEventListener('click', event => {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (!menu.contains(event.target)) {
                menu.classList.remove('show');
            }
        });
    });

});