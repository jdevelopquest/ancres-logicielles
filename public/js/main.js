document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('.button-hamburger')?.addEventListener('click', function () {
        const menu = document.querySelector('.menu-hamburger');
        if (menu.style.display === 'block') {
            menu.style.display = 'none'; // Fermer le menu
        } else {
            menu.style.display = 'block'; // Ouvrir le menu
        }
    });

    document.querySelector('.button-admin')?.addEventListener('click', function () {
        const menu = document.querySelector('.menu-admin');
        if (menu.style.display === 'block') {
            menu.style.display = 'none'; // Fermer le menu
        } else {
            menu.style.display = 'block'; // Ouvrir le menu
        }
    });

    document.querySelector('.button-switch-theme')?.addEventListener('click', function () {
        const body = document.querySelector('body');
        body.classList.toggle('theme-light');
        body.classList.toggle('theme-dark');
    });
})