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
            const theme = body?.classList.contains('theme-light') ? 'theme-light' : 'theme-dark';
            sendTheme(theme);
        })
    });
}

function sendTheme(theme) {
    const url = "index.php?ctr=users&act=saveTheme";
    const data = {
        theme: theme
    };

    fetch(
        url,
        {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            // Il n'y a pas de données à récupérer
        })
        .catch(error => {
            console.error('Il y a eu un problème avec la requête fetch:', error);
        });
}

document.addEventListener('DOMContentLoaded', function () {
    initMenuButtons('hamburger');
    initMenuButtons('tiny');

    initSwitchThemeButtons();
})