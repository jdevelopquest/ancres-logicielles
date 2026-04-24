<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/svg" href="img/al-favicon.svg">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap">
    <link rel="stylesheet" href="css/style.css">

    <title>Ancres Logicielles</title>
</head>
<body class="size-md theme-light">
<div>
    <header class="menu">
        <div class="menu__submenu menu__submenu-h">
            <h1 class="menu__item prominent__text">Ancres Logicielles</h1>
            <button class="menu__item menu__item-clickable prominent button-menu-hamburger"><span class="prominent__icon"></span></button>
        </div>
        <div class="menu__submenu menu__submenu-h">
            <button class="menu__item menu__item-clickable prominent button-previous-page"><span class="prominent__icon"></span></button>
        </div>
    </header>
    <main>
        <aside class="menu menu-hamburger hide">
            <div class="menu__submenu menu__submenu-v menu__submenu-close">
                <button class="menu__item menu__item-clickable prominent button-close"><span class="prominent__icon"></span></button>
            </div>
            <nav class="menu__submenu menu__submenu-v">
                <a class="menu__item menu__item-clickable prominent"><span class="prominent__text">Accueil</span><span class="prominent__icon"></span></a>
                <a class="menu__item menu__item-clickable prominent"><span class="prominent__text">Inscription</span><span class="prominent__icon"></span></a>
                <a class="menu__item menu__item-clickable prominent"><span class="prominent__text">Connexion</span><span class="prominent__icon"></span></a>
            </nav>
            <div class="menu__submenu menu__submenu-v">
                <button class="menu__item menu__item-clickable prominent button-switch-theme"><span class="prominent__text">Thème</span><span class="prominent__icon"></span></button>
                <button class="menu__item menu__item-clickable prominent button-menu-tiny"><span class="prominent__text">Menu petit</span><span class="prominent__icon"></span></button>
            </div>
        </aside>
        <section class="content">
            <div class="content__subcontent prominent">
                <h2 class="prominent__text">Accueil</h2>
            </div>
            <article class="content__subcontent">
                <div class="prominent">
                    <h3 class="prominent__text">À consulter</h3>
                </div>
                <a class="prominent"><span class="prominent__text">Blender</span><span class="prominent__icon"></span></a>
                <a class="prominent"><span class="prominent__text">Maya</span><span class="prominent__icon"></span></a>
                <a class="prominent"><span class="prominent__text">PhpStorm</span><span class="prominent__icon"></span></a>
            </article>
            <article class="content__subcontent">
                <div class="prominent">
                    <h3 class="prominent__text">Blender</h3>
                    <span class="prominent__icon" title="favoris"></span>
                    <span class="prominent__icon" title="publié"></span>
                </div>
                <div class="regular">
                    <p class="regular__text">Blender est un logiciel libre et open source de création 3D, utilisé pour la modélisation, l'animation, le rendu, la simulation, le compositing et le montage vidéo.</p>
                    <p class="regular__text">Blender est utilisé par des artistes, des studios d'animation, des développeurs de jeux vidéo et des professionnels de l'industrie pour créer des œuvres variées, allant des courts métrages aux jeux vidéo. Sa nature open source permet à quiconque de l'utiliser, de l'étudier et de le modifier.</p>
                </div>
                <aside class="menu">
                    <div class="menu__submenu menu__submenu-h menu__submenu-r">
                        <button class="menu__item menu__item-clickable prominent button-switch-theme"><span class="prominent__icon"></span></button>
                        <button class="menu__item menu__item-clickable prominent button-menu-tiny"><span class="prominent__icon"></span></button>
                        <button class="menu__item menu__item-clickable prominent button-switch-theme"><span class="prominent__icon"></span></button>
                        <button class="menu__item menu__item-clickable prominent button-menu-tiny"><span class="prominent__icon"></span></button>
                    </div>
                </aside>
            </article>
            <div class="content__subcontent prominent">
                <h2 class="prominent__text">Ancres</h2>
            </div>
            <article class="content__subcontent">
                <div class="prominent">
                    <h3 class="prominent__text"><a class="anchor">https://www.blender.org/</a></h3>
                    <span class="prominent__icon" title="favoris"></span>
                    <span class="prominent__icon" title="publié"></span>
                </div>
                <div class="regular">
                    <p class="regular__text">Site officiel.</p>
                </div>
                <aside class="menu">
                    <div class="menu__submenu menu__submenu-h menu__submenu-r">
                        <button class="menu__item menu__item-clickable prominent button-switch-theme"><span class="prominent__icon"></span></button>
                        <button class="menu__item menu__item-clickable prominent button-menu-tiny"><span class="prominent__icon"></span></button>
                        <button class="menu__item menu__item-clickable prominent button-switch-theme"><span class="prominent__icon"></span></button>
                        <button class="menu__item menu__item-clickable prominent button-menu-tiny"><span class="prominent__icon"></span></button>
                    </div>
                </aside>
            </article>
        </section>
        <aside class="menu menu-tiny hide">
            <nav class="menu__submenu menu__submenu-v">
                <a class="menu__item menu__item-clickable prominent"><span class="prominent__icon"></span></a>
                <a class="menu__item menu__item-clickable prominent"><span class="prominent__icon"></span></a>
                <a class="menu__item menu__item-clickable prominent"><span class="prominent__icon"></span></a>
            </nav>
            <div class="menu__submenu menu__submenu-v">
                <button class="menu__item menu__item-clickable prominent button-switch-theme"><span class="prominent__icon"></span></button>
                <button class="menu__item menu__item-clickable prominent button-menu-tiny"><span class="prominent__icon"></span></button>
            </div>
        </aside>
    </main>
    <footer class="menu menu-info">
            <nav class="menu__submenu">
                <a class="menu__item menu__item-clickable prominent"><span class="prominent__text">À propos</span></a>
                <a class="menu__item menu__item-clickable prominent"><span class="prominent__text">Chartes</span></a>
            </nav>
    </footer>
</div>
<script src="js/main.js" type="module"></script>
</body>
</html>
