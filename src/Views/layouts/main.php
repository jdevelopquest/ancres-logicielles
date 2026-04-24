<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/svg" href="/public/img/al-favicon.svg">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap">
    <link rel="stylesheet" href="/public/css/style.css">

    <title>Ancres Logicielles</title>
</head>
<body class="theme-light">
<div class="container">
    <header>
        <div class="panel">
            <h1 class="text">Ancres Logicielles</h1>
            <button class="tool icon button-hamburger"></button>
        </div>
        <div class="panel">
            <button class="tool icon button-previous-page"></button>
            <h2 class="text">Accueil</h2>
            <button class="tool icon button-admin"></button>
        </div>
    </header>
    <main>
        <aside class="sidebar menu-hamburger">
            <nav>
                <a class="panel tool">
                    <span class="text">Accueil</span>
                    <span class="icon"></span>
                </a>
                <a class="panel tool">
                    <span class="text">Inscription</span>
                    <span class="icon"></span>
                </a>
                <a class="panel tool">
                    <span class="text">Connexion</span>
                    <span class="icon"></span>
                </a>
            </nav>
            <div class="panel toolbox">
                <button class="tool icon button-switch-theme" title="Changer le thème"></button>
            </div>
        </aside>
        <section>
            <nav>
                <a class="panel tool">
                    <span class="text">Blender</span>
                    <span class="icon"></span>
                </a>
            </nav>
        </section>
        <aside class="sidebar menu-admin">
            <nav>
                <a class="panel tool">
                    <span class="text">Ajouter une fiche</span>
                    <span class="icon"></span>
                </a>
            </nav>
        </aside>
    </main>
    <footer>
        <nav>
            <a class="panel tool">
                <span class="text">À propos</span>
                <span class="icon"></span>
            </a>
        </nav>
    </footer>
</div>
<script src="/public/js/main.js" type="module"></script>
</body>
</html>
