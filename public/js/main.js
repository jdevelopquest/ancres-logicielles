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

function initPostboxModButtons(buttonAction) {
    const buttonSelector = '.button-post-' + buttonAction;
    document.querySelectorAll(buttonSelector)?.forEach((button) => {
        button.addEventListener('click', function () {
            const params = {
                idPost: button.getAttribute('data-id-post'),
                action: buttonAction,
            }
            sendPostModAction(params);
        })
    });
}

function initPostboxMod() {
    initPostboxModButtons('publish');
    initPostboxModButtons('unpublish');
    initPostboxModButtons('ban');
    initPostboxModButtons('unban');
}

function sendPostModAction(params) {
    const action = params.action;
    const idPost = params.idPost;
    const data = {
        'idPost': idPost,
    };
    const url = `index.php?ctr=posts&act=${action}`;
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
                throw new Error(response.statusText);
            }
            // Il n'y a pas de données à récupérer,
            // mettre à jour les status et la barre d'outils
            getUpdatePostboxModTool(idPost);
            // Ajout d'un temps d'attente pour mettre à jour le cookie token parce que l'enchaîne des actions ne permet pas de mettre à jour le cookie token par le navigateur
            // todo : régler le problème de mise à jour du cookie token
            setTimeout(() => {
                getUpdateSoftwareStatus(idPost);
            }, 100);
        })
        .catch(error => {
            // console.error('Il y a eu un problème avec la requête fetch:', error);
        });
}

function getUpdatePostboxModTool(idPost) {
    const body = {
        'idPost': idPost,
    };
    const url = `index.php?ctr=posts&act=updatePostboxModTool`;
    fetch(
        url,
        {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(body)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(response.statusText);
            }
            return response.json();
        })
        .then(data => {
            updatePostboxModTool(idPost, data);
        })
        .catch(error => {
            // console.error(error);
        });
}

function getUpdateSoftwareStatus(idPost) {
    const body = {
        'idPost': idPost,
    };
    const url = `index.php?ctr=posts&act=updateSoftwareStatus`;
    fetch(
        url,
        {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(body)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(response.statusText);
            }
            return response.json();
        })
        .then(data => {
            updateSoftwareStatus(idPost, data);
        })
        .catch(error => {
            // console.error(error);
        });
}

function updatePostboxModTool(idPost, data) {
    const postboxSelector = `.postbox-mod-tools-${idPost}`;
    document.querySelectorAll(postboxSelector)?.forEach(postbox => {
        // todo attention à la sécurité
        postbox.innerHTML = data;
    })
    initPostboxMod();
}

function updateSoftwareStatus(idPost, data) {
    const postboxStatusSelector = `.postbox-status-${idPost}`;
    document.querySelectorAll(postboxStatusSelector)?.forEach(postbox => {
        // todo attention à la sécurité
        postbox.innerHTML = data;
    })
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
                // throw new Error(response.statusText);
            }
            // Il n'y a pas de données à récupérer
        })
        .catch(error => {
            // console.error('Il y a eu un problème avec la requête fetch:', error);
        });
}

document.addEventListener('DOMContentLoaded', function () {
    initMenuButtons('hamburger');
    initMenuButtons('tiny');
    initPostboxMod();
    initSwitchThemeButtons();
})