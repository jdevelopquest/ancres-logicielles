function initMenu(menuTag) {
    const buttonSelector = '.button-menu-' + menuTag;
    const menuSelector = '.menu-' + menuTag;
    document.querySelector(buttonSelector)?.addEventListener('click', function () {
        const menu = document.querySelector(menuSelector);
        menu?.classList.toggle('hide');
        const close = menu?.querySelector('.button-close');
        close?.addEventListener('click', function () {
            menu?.classList.add('hide');
        });
    });
}

document.addEventListener('DOMContentLoaded', function () {
    initMenu('hamburger');
    initMenu('tiny');

    document.querySelector('.button-switch-theme')?.addEventListener('click', function () {
        const body = document.querySelector('body');
        body?.classList.toggle('theme-light');
        body?.classList.toggle('theme-dark');
    });
})