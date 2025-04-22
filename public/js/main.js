document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('button-hamburger').addEventListener('click', function () {
        const menu = document.getElementById('menu-hamburger');
        if (menu.style.display === 'block') {
            menu.style.display = 'none'; // Fermer le menu
        } else {
            menu.style.display = 'block'; // Ouvrir le menu
        }
    });

    document.getElementById('button-switch-theme').addEventListener('click', function () {
        const body = document.getElementById('body');
        body.classList.toggle('dark-theme');
        body.classList.toggle('light-theme');
    });
})