<?php
$al_hamburger_icon = "<svg viewBox=\"0 0 64 64\" xmlns=\"http://www.w3.org/2000/svg\"> <path d=\"M 0,0 V 16 H 64 V 0 Z M 0,24 V 40 H 64 V 24 Z M 0,48 V 64 H 64 V 48 Z\"/></svg>";
$al_switch_theme_icon = "<svg viewBox=\"0 0 64 64\" xmlns=\"http://www.w3.org/2000/svg\"> <path d=\"m 0,0 v 64 h 64 v -64 z m 32,4 h 28 v 56 h -28 z\"/></svg>";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/svg" href="img/al-favicon.svg"/>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap">
    <link rel="stylesheet" href="css/style.css">
    <title>Ancres Logicielles</title>
</head>
<body class="body light-theme" id="body">
<div class="container">
    <header class="header">
        <div class="panel panel-secondary">
            <span class="logo logo-part-0">A</span>
            <span class="logo logo-part-1">ncres</span>
            <button class="button button-hamburger" id="button-hamburger">
                <?= $al_hamburger_icon ?>
            </button>
        </div>
        <div class="panel panel-primary">
            <span class="logo logo-part-2">Logicielles</span>
        </div>
        <div class="menu menu-hamburger" id="menu-hamburger">
            <nav class="menu-hamburger__navbar" id="navbar">
                <ul>
                    <li class="panel panel-secondary"><a class="button" href="#home">Accueil</a></li>
                    <li class="panel panel-secondary"><a class="button" href="#signin">Inscription</a></li>
                    <li class="panel panel-secondary"><a class="button" href="#login">Connexion</a></li>
                </ul>
            </nav>
            <div class="menu-hamburger__action">
                <ul>
                    <li class="panel panel-secondary">
                        <span>Thème</span>
                        <button class="button button-switch-theme" id="button-switch-theme">
                            <?= $al_switch_theme_icon ?>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <main class="content">
        {{content}}
    </main>
    <script src="js/main.js" type="module"></script>
    <footer class="footer"></footer>
</div>
</body>
</html>
