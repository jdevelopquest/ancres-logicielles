function initMenuButtons(menuTag) {
    const buttonSelector = '.button-menu-' + menuTag;
    const menuSelector = '.menu-' + menuTag;
    document.querySelectorAll(buttonSelector)?.forEach((button) => {
        button.addEventListener('click', function () {
            const menu = document.querySelector(menuSelector);
            menu?.classList.toggle('hide');
            if (menuTag === 'tiny') {
                setTinyMenuVisibility(!menu?.classList.contains('hide'));
            }
            menu?.querySelectorAll('.button-close')?.forEach((button) => {
                button?.addEventListener('click', function () {
                    menu?.classList.add('hide');
                    if (menuTag === 'tiny') {
                        setTinyMenuVisibility(false);
                    }
                });
            });
        })
    });
}

function setTinyMenuVisibility(visible = false) {
    localStorage.setItem('menu-tiny-visible', visible ? 'true' : 'false');
}

function getTinyMenuVisibility() {
    return localStorage.getItem('menu-tiny-visible') ?? false;
}

function applyTinyMenuVisibility() {
    const menuSelector = '.menu-tiny';
    const menu = document.querySelector(menuSelector);
    if (getTinyMenuVisibility() === 'true') {
        menu?.classList.remove('hide');
    } else {
        menu?.classList.add('hide');
    }
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
            getUpdatePostboxModTool(idPost).then(r => getUpdateSoftwareStatus(idPost));
        })
        .catch(error => {
            // console.error('Il y a eu un problème avec la requête fetch:', error);
        });
}

async function getUpdatePostboxModTool(idPost) {
    const body = {
        'idPost': idPost,
    };
    const url = `index.php?ctr=posts&act=updatePostboxModTool`;
    await fetch(
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

async function getUpdateSoftwareStatus(idPost) {
    const body = {
        'idPost': idPost,
    };
    const url = `index.php?ctr=posts&act=updateSoftwareStatus`;
    await fetch(
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
            setTheme(theme);
        })
    });
}

function setTheme(theme = 'theme-light') {
    localStorage.setItem('theme', theme);
}

function getTheme() {
    return localStorage.getItem('theme') ?? 'theme-light';
}

function applyTheme() {
    const body = document.querySelector('body');
    if (getTheme() === 'theme-dark') {
        body?.classList.remove('theme-light');
        body?.classList.add('theme-dark');
    } else {
        body?.classList.add('theme-light');
        body?.classList.remove('theme-dark');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    initMenuButtons('hamburger');
    initMenuButtons('tiny');
    initPostboxMod();
    initSwitchThemeButtons();
    applyTheme();
    applyTinyMenuVisibility();
})