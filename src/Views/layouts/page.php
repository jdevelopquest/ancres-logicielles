<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/svg" href="public/img/al-favicon.svg">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap">
    <link rel="stylesheet" href="public/css/style.css">

    <title><?= $title ?? "Ancres Logicielles" ?></title>
    <meta name="description" content="Organisez, partagez et découvrez des liens web pour vos logiciels favoris.">
    <meta name="keywords" content="gestion de liens, partage de liens, application web collaborative, veille informationnelle, outils collaboratifs, partage de connaissances">
</head>
<body class="size-md theme-light">
<div>
    <header class="menu" id="page-top">
        <div class="menu__submenu menu__submenu-h">
            <h1 class="menu__item prominent__text">Ancres Logicielles</h1>
            <button class="menu__item menu__item-clickable prominent button-menu-hamburger" title="Ouvrir ou fermer le menu hamburger"><span class="prominent__icon"></span></button>
        </div>
        <div class="menu__submenu menu__submenu-h">
            <button class="menu__item menu__item-clickable prominent button-previous-page"><span class="prominent__icon"></span></button>
        </div>
    </header>
    <main>
        {{menu-hamburger}}
        {{content}}
        {{menu-tiny}}
    </main>
    <footer class="menu menu-info" id="page-bot">
            <nav class="menu__submenu">
                <a class="menu__item menu__item-clickable prominent" href="index.php?ctr=supports&act=about"><span class="prominent__text">À propos</span></a>
                <a class="menu__item menu__item-clickable prominent" href="index.php?ctr=supports&act=policies"><span class="prominent__text">Chartes</span></a>
            </nav>
    </footer>
</div>
<script src="public/js/main.js" type="module"></script>
</body>
</html>
