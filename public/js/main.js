function initMenuButtons(menuTag) {
    const buttonSelector = '.button-menu-' + menuTag;
    const menuSelector = '.menu-' + menuTag;
    document.querySelectorAll(buttonSelector)?.forEach((button) => {
        button.addEventListener('click', function () {
            const menu = document.querySelector(menuSelector);
            menu?.classList.toggle('hide');
            const close = menu?.querySelector('.button-close');
            close?.addEventListener('click', function () {
                menu?.classList.add('hide');
            });
        })
    });
}

function initSwitchThemeButtons() {
    document.querySelectorAll('.button-switch-theme')?.forEach(button => {
        button.addEventListener('click', function () {
            const body = document.querySelector('body');
            body?.classList.toggle('theme-light');
            body?.classList.toggle('theme-dark');
        })
    });
}

document.addEventListener('DOMContentLoaded', function () {
    initMenuButtons('hamburger');
    initMenuButtons('tiny');

    initSwitchThemeButtons();
})