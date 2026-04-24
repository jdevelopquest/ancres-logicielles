-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Hôte : db.3wa.io
-- Généré le : mer. 10 sep. 2025 à 14:58
-- Version du serveur :  5.7.33-0ubuntu0.18.04.1-log
-- Version de PHP : 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ancres-logicielles`
--

-- --------------------------------------------------------

--
-- Structure de la table `Accounts`
--

CREATE TABLE `Accounts` (
  `idAccount` int(11) NOT NULL,
  `accountUsername` varchar(255) NOT NULL,
  `accountPassword` varchar(255) NOT NULL,
  `accountIsBanned` tinyint(1) DEFAULT '0',
  `accountIsAdmin` tinyint(1) DEFAULT '0',
  `accountIsModerator` tinyint(1) DEFAULT '0',
  `accountIsSuspended` tinyint(1) DEFAULT '0',
  `failedLoginAttempts` int(11) DEFAULT '5',
  `suspensionEndTime` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `Accounts`
--

INSERT INTO `Accounts` (`idAccount`, `accountUsername`, `accountPassword`, `accountIsBanned`, `accountIsAdmin`, `accountIsModerator`, `accountIsSuspended`, `failedLoginAttempts`, `suspensionEndTime`) VALUES
(1, 'Nicolas89', '$2y$10$1OPn.JwmJIeZfRbxpd651uYCeXFmVft/10Nv8Okb.APYS4B0ywAzK', 0, 0, 0, 1, 5, 1741281343),
(2, 'ciTron', '$2y$10$P3oK7qq16MgVv9JJBkBaMecVlCeCQIDPkAEYt6f7MlGkIevirXmr.', 1, 0, 0, 0, 5, NULL),
(3, 'blocnotesA5', '$2y$10$1/cXFtzP4z6TOT3X0lLAauKBNih.pDzh.NLaYkC88jyYLrAJ6Gh86', 0, 1, 1, 0, 5, NULL),
(4, 'admirateur', '$2y$10$sXYtluPy3/UTP2a5cf5zku1NBQ24ieVsyVskMyJvgCT2nFGqw.FGK', 0, 1, 1, 0, 5, NULL),
(6, 'leweb', '$2y$10$yLoxhR4iH3X1UQiefYkJGuyYTVHx/N4RsMNIVnZgu29RJ8AK5TyIO', 0, 1, 1, 1, 0, 1741076948),
(8, 'janedoe', '$2y$10$tuHz1F8GmtHuR15s1ZiUkexjoRY2xyVpF6M03dW/ALepD8ObAKrEu', 0, 0, 0, 0, 5, NULL),
(9, 'clefusb1', '$2y$10$ezH.LYeG27RsixtYPQId3.JTXjnM8JedwR9ZQYBZpc0.86bjkAOt.', 0, 0, 0, 1, 5, NULL),
(10, 'leboncode', '$2y$10$tapRQrTL5.sY8xa2qsqAKOcU2IyMLmoYQrYshZdV51rSHzXpnwG0q', 0, 0, 0, 1, 5, 1741084771),
(11, 'Modo10', '$2y$10$f7UuiJVDkgiCebcxwUiZOuCXJtGXLAeDaDFDa1GCJxCU2w.3Uk5l6', 0, 0, 1, 0, 5, NULL),
(13, 'Comptecourant0', '$2y$10$dR9wO1WMTc6kl4Xabi/svOTwS2vbwA5bPs/8m0trtTNOuu27Xsc4O', 0, 0, 0, 0, 5, NULL),
(14, 'HelloWorld', '$2y$10$V9Ygk3DG5.ypXRGsa1EDU.ebvKXdwdIDUzFxL/iwIK.c5NJGOTEoO', 0, 0, 1, 0, 5, NULL),
(15, 'Coco', '$2y$10$IS04uKGMRJiiF1wKF8ME/u.wFgw8zMR/LiaOl9yOUgCPh0kkp6v6.', 0, 0, 0, 1, 5, 1741046076),
(17, 'leboncode2', '$2y$12$AF5vlImQEnjZNKp7u6rJIO9nhwBxTnMID6XKB.GmkEM0Be5J2XlWO', 0, 0, 0, 0, 5, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `Anchors`
--

CREATE TABLE `Anchors` (
  `idAnchor` int(11) NOT NULL,
  `anchorUrl` varchar(255) DEFAULT NULL,
  `anchorContent` text,
  `idPost` int(11) NOT NULL COMMENT 'Clef du post du lien.',
  `idPostSoftware` int(11) NOT NULL COMMENT 'Clef du post de la fiche du logiciel.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `Anchors`
--

INSERT INTO `Anchors` (`idAnchor`, `anchorUrl`, `anchorContent`, `idPost`, `idPostSoftware`) VALUES
(1, 'https://www.blender.org', 'Site officiel.', 8, 1),
(2, 'https://www.adobe.com/fr/products/photoshop.html', 'Site officiel.', 9, 2),
(3, 'https://www.youtube.com/watch?v=_5Js5pbvFSw', 'MEGA TUTO : Les BASES de BLENDER - 3H de formation gratuite pour dÃ©butant sur Blender 4 en franÃ§ais \r\nBertrand . Tech', 11, 1),
(4, 'https://www.darty.com/nav/achat/petit_electromenager/robots_cuisine/blender/index.html', '\r\nChoix durable\r\nBlender CHAUFFANT LM841810 EASY SOUP Moulinex\r\nAjouter au comparateur\r\nBlender Moulinex CHAUFFANT LM841810 EASY SOUP ', 12, 1),
(5, 'https://www.autodesk.com/fr', 'Site officiel fr.', 13, 3),
(6, 'https://www.bender.fr', 'Site de bender !!!', 16, 1);

-- --------------------------------------------------------

--
-- Structure de la table `Appinfos`
--

CREATE TABLE `Appinfos` (
  `idAppInfo` int(11) NOT NULL,
  `appInfoTag` varchar(255) DEFAULT NULL,
  `appInfoContent` text,
  `idAccount` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `ContactMessages`
--

CREATE TABLE `ContactMessages` (
  `idContactMessage` int(11) NOT NULL,
  `contactMessageIsRead` tinyint(1) DEFAULT '0',
  `contactMessageContent` text,
  `contactResponseContent` text,
  `idAccount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `ContactMessages`
--

INSERT INTO `ContactMessages` (`idContactMessage`, `contactMessageIsRead`, `contactMessageContent`, `contactResponseContent`, `idAccount`) VALUES
(1, 0, 'Y a pas de logiciels ! ðŸ¤¬', NULL, 3),
(2, 0, 'Comment on fait des liens ?????', NULL, 10),
(3, 0, 'Je n\'ai pas accÃ¨s Ã  mon espace personnel !!!!', NULL, 3),
(4, 0, 'Pourquoi il n\'y a aucune image ?', NULL, 9),
(5, 0, '?Ce message n\'est pas valide.?', NULL, 9),
(6, 0, 'Est ce que je peux Ãªtre mod ?', NULL, 11);

-- --------------------------------------------------------

--
-- Structure de la table `Favorites`
--

CREATE TABLE `Favorites` (
  `idFavorite` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `idAccount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `Grades`
--

CREATE TABLE `Grades` (
  `idGrade` int(11) NOT NULL,
  `gradeUp` int(11) DEFAULT '0',
  `gradeDown` int(11) DEFAULT '0',
  `gradeReported` int(11) DEFAULT '0',
  `idPost` int(11) NOT NULL,
  `idAccount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `Posts`
--

CREATE TABLE `Posts` (
  `idPost` int(11) NOT NULL,
  `postIsBanned` tinyint(1) DEFAULT '0',
  `postIsPublished` tinyint(1) DEFAULT '0',
  `idAccount` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `Posts`
--

INSERT INTO `Posts` (`idPost`, `postIsBanned`, `postIsPublished`, `idAccount`) VALUES
(1, 0, 1, 11),
(2, 0, 1, 11),
(3, 0, 1, 11),
(4, 0, 1, 11),
(5, 0, 1, 11),
(6, 0, 1, NULL),
(7, 0, 0, NULL),
(8, 0, 1, 4),
(9, 0, 0, 4),
(10, 0, 0, 11),
(11, 0, 1, 13),
(12, 1, 0, 14),
(13, 0, 0, 4),
(14, 1, 0, 4),
(15, 0, 0, 4),
(16, 1, 0, NULL),
(17, 1, 0, 4),
(18, 0, 0, 4),
(19, 0, 0, 4),
(20, 0, 0, 4),
(21, 0, 1, 4),
(22, 0, 0, 4);

-- --------------------------------------------------------

--
-- Structure de la table `SoftwareComments`
--

CREATE TABLE `SoftwareComments` (
  `idSoftwareComment` int(11) NOT NULL,
  `softwareComment` text,
  `idPost` int(11) NOT NULL COMMENT 'Clef du post du commentaire.',
  `idPostSoftware` int(11) NOT NULL COMMENT 'Clef du post de la description du logiciel.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `Softwares`
--

CREATE TABLE `Softwares` (
  `idSoftware` int(11) NOT NULL,
  `softwareName` varchar(255) NOT NULL,
  `softwareAvatar` varchar(255) DEFAULT NULL,
  `softwareSummary` text,
  `idPost` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `Softwares`
--

INSERT INTO `Softwares` (`idSoftware`, `softwareName`, `softwareAvatar`, `softwareSummary`, `idPost`) VALUES
(1, 'Blender', NULL, 'Blender est un logiciel libre et open source de crÃ©ation 3D, largement utilisÃ© dans les domaines de l\'animation, de la modÃ©lisation, du rendu, de la simulation, du compositing et du montage vidÃ©o. Il offre une suite complÃ¨te d\'outils permettant aux artistes et aux dÃ©veloppeurs de crÃ©er des contenus 3D de haute qualitÃ©.', 1),
(2, 'Adobe Photoshop', NULL, 'Adobe Photoshop est un logiciel de retouche d\'images et de crÃ©ation graphique, largement utilisÃ© par les professionnels du design, de la photographie et de l\'illustration. Il offre une vaste gamme d\'outils et de fonctionnalitÃ©s permettant aux utilisateurs de manipuler des images, de crÃ©er des compositions complexes et d\'appliquer des effets visuels variÃ©s. GrÃ¢ce Ã  son interface intuitive et Ã  ses capacitÃ©s avancÃ©es de traitement d\'image, Photoshop est devenu un standard de l\'industrie pour la crÃ©ation visuelle.', 2),
(3, 'Autodesk Maya', NULL, 'Autodesk Maya est un logiciel de modÃ©lisation, d\'animation et de rendu 3D, utilisÃ© principalement dans l\'industrie du cinÃ©ma, des jeux vidÃ©o et de l\'animation. Il propose des outils puissants pour la crÃ©ation de personnages, d\'environnements et d\'effets visuels, permettant aux artistes de donner vie Ã  leurs idÃ©es crÃ©atives. Avec ses fonctionnalitÃ©s avancÃ©es de simulation et d\'animation, Maya est reconnu pour sa flexibilitÃ© et sa capacitÃ© Ã  gÃ©rer des projets complexes.', 3),
(4, 'Microsoft Excel', NULL, 'Microsoft Excel est un logiciel de tableur largement utilisÃ© pour la gestion de donnÃ©es, l\'analyse et la visualisation. Il permet aux utilisateurs de crÃ©er des feuilles de calcul, d\'effectuer des calculs complexes et de gÃ©nÃ©rer des graphiques pour reprÃ©senter visuellement les informations. GrÃ¢ce Ã  ses fonctionnalitÃ©s avancÃ©es de manipulation de donnÃ©es et de crÃ©ation de formules, Excel est un outil essentiel pour les professionnels dans divers domaines, allant de la finance Ã  la recherche.', 4),
(5, 'Final Cut Pro', NULL, 'Final Cut Pro est un logiciel de montage vidÃ©o professionnel dÃ©veloppÃ© par Apple, utilisÃ© par les cinÃ©astes et les crÃ©ateurs de contenu pour produire des films, des documentaires et des vidÃ©os en ligne. Il offre une interface intuitive et des outils puissants pour le montage non linÃ©aire, permettant aux utilisateurs de manipuler facilement des sÃ©quences vidÃ©o, d\'ajouter des effets spÃ©ciaux et de travailler avec des pistes audio. GrÃ¢ce Ã  ses fonctionnalitÃ©s avancÃ©es de colorimÃ©trie et de gestion des mÃ©dias, Final Cut Pro est un choix privilÃ©giÃ© pour les professionnels de l\'Ã©dition vidÃ©o.', 5),
(6, 'Unity', NULL, 'Unity est un moteur de jeu et une plateforme de dÃ©veloppement largement utilisÃ©e pour crÃ©er des jeux vidÃ©o et des expÃ©riences interactives en 2D et en 3D. Il offre une interface conviviale et une vaste bibliothÃ¨que d\'outils et de ressources, permettant aux dÃ©veloppeurs de concevoir des environnements immersifs et des mÃ©caniques de jeu complexes. Avec son support multiplateforme, Unity permet aux crÃ©ateurs de dÃ©ployer leurs projets sur divers appareils, allant des consoles de jeux aux smartphones.', 6),
(7, 'AutoCAD', NULL, 'AutoCAD est un logiciel de conception assistÃ©e par ordinateur (CAO) utilisÃ© principalement dans les domaines de l\'architecture, de l\'ingÃ©nierie et de la construction. Il permet aux utilisateurs de crÃ©er des dessins techniques prÃ©cis et des modÃ¨les 2D et 3D, facilitant ainsi la planification et la visualisation de projets. GrÃ¢ce Ã  ses outils de dessin avancÃ©s et Ã  sa capacitÃ© Ã  gÃ©rer des fichiers de grande taille, AutoCAD est un outil essentiel pour les professionnels qui travaillent sur des projets de conception complexes.', 7),
(8, 'Troll Software', NULL, 'Le logiciel des trolleurs.', 10),
(9, 'Mdr', NULL, 'Salut ! \"Mdr\" signifie \"mort de rire\" en franÃ§ais. C\'est une expression utilisÃ©e pour indiquer que quelque chose est trÃ¨s drÃ´le, un peu comme \"lol\" en anglais.', 14),
(10, 'Visual Code Studio', NULL, 'Visual Studio Code (VSCode) est un Ã©diteur de code source dÃ©veloppÃ© par Microsoft, apprÃ©ciÃ© pour sa lÃ©gÃ¨retÃ© et sa flexibilitÃ©. Il prend en charge de nombreux langages de programmation grÃ¢ce Ã  des extensions, offrant des fonctionnalitÃ©s telles que la coloration syntaxique, l\'autocomplÃ©tion, et le dÃ©bogage intÃ©grÃ©. Son interface utilisateur est personnalisable, permettant aux dÃ©veloppeurs d\'adapter l\'environnement de travail Ã  leurs besoins. De plus, VSCode intÃ¨gre des outils de collaboration et de gestion de version, ce qui en fait un choix populaire parmi les dÃ©veloppeurs de tous niveaux.', 15),
(11, 'Logic Lundi', NULL, 'bjdfhnivbuhnfeiuvbfeiub uhvuirezhbvuyirevreyui', 17),
(12, 'Dolphin', NULL, 'Gestionnaire de fichiers.', 18),
(13, 'Chromium Web Brower', NULL, 'Chromium est un projet de navigateur web gratuit et open source dÃ©veloppÃ© par Google, qui sert de base Ã  Google Chrome et Ã  d\'autres navigateurs. Il est connu pour ses caractÃ©ristiques de vitesse et de sÃ©curitÃ©, mais manque de certaines fonctionnalitÃ©s propriÃ©taires trouvÃ©es dans Chrome, telles que les mises Ã  jour automatiques et certains codecs multimÃ©dias.', 19),
(14, 'Micro Robert', NULL, 'Dictionnaire du franÃ§ais primordial.', 20),
(15, 'Discord', NULL, 'Discord est une plateforme de VoIP et de messagerie instantanÃ©e.', 21),
(16, 'VLC', NULL, 'VLC est un logiciel multimedia libre et open-source qui lit presque tous les formats de fichiers sans codecs externes.', 22);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Accounts`
--
ALTER TABLE `Accounts`
  ADD PRIMARY KEY (`idAccount`),
  ADD UNIQUE KEY `accountUsername` (`accountUsername`);

--
-- Index pour la table `Anchors`
--
ALTER TABLE `Anchors`
  ADD PRIMARY KEY (`idAnchor`),
  ADD KEY `idPost` (`idPost`),
  ADD KEY `idPostSoftware` (`idPostSoftware`);

--
-- Index pour la table `Appinfos`
--
ALTER TABLE `Appinfos`
  ADD PRIMARY KEY (`idAppInfo`),
  ADD KEY `idAccount` (`idAccount`);

--
-- Index pour la table `ContactMessages`
--
ALTER TABLE `ContactMessages`
  ADD PRIMARY KEY (`idContactMessage`),
  ADD KEY `idAccount` (`idAccount`);

--
-- Index pour la table `Favorites`
--
ALTER TABLE `Favorites`
  ADD PRIMARY KEY (`idFavorite`),
  ADD KEY `idAccount` (`idAccount`),
  ADD KEY `idPost` (`idPost`);

--
-- Index pour la table `Grades`
--
ALTER TABLE `Grades`
  ADD PRIMARY KEY (`idGrade`),
  ADD KEY `idAccount` (`idAccount`),
  ADD KEY `idPost` (`idPost`);

--
-- Index pour la table `Posts`
--
ALTER TABLE `Posts`
  ADD PRIMARY KEY (`idPost`),
  ADD KEY `idAccount` (`idAccount`);

--
-- Index pour la table `SoftwareComments`
--
ALTER TABLE `SoftwareComments`
  ADD PRIMARY KEY (`idSoftwareComment`),
  ADD KEY `idPost` (`idPost`),
  ADD KEY `idPostSoftware` (`idPostSoftware`);

--
-- Index pour la table `Softwares`
--
ALTER TABLE `Softwares`
  ADD PRIMARY KEY (`idSoftware`),
  ADD KEY `idPost` (`idPost`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Accounts`
--
ALTER TABLE `Accounts`
  MODIFY `idAccount` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `Anchors`
--
ALTER TABLE `Anchors`
  MODIFY `idAnchor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `Appinfos`
--
ALTER TABLE `Appinfos`
  MODIFY `idAppInfo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ContactMessages`
--
ALTER TABLE `ContactMessages`
  MODIFY `idContactMessage` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `Favorites`
--
ALTER TABLE `Favorites`
  MODIFY `idFavorite` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Grades`
--
ALTER TABLE `Grades`
  MODIFY `idGrade` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Posts`
--
ALTER TABLE `Posts`
  MODIFY `idPost` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `SoftwareComments`
--
ALTER TABLE `SoftwareComments`
  MODIFY `idSoftwareComment` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Softwares`
--
ALTER TABLE `Softwares`
  MODIFY `idSoftware` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Anchors`
--
ALTER TABLE `Anchors`
  ADD CONSTRAINT `Anchors_ibfk_1` FOREIGN KEY (`idPost`) REFERENCES `Posts` (`idPost`) ON DELETE CASCADE,
  ADD CONSTRAINT `Anchors_ibfk_2` FOREIGN KEY (`idPostSoftware`) REFERENCES `Posts` (`idPost`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Appinfos`
--
ALTER TABLE `Appinfos`
  ADD CONSTRAINT `Appinfos_ibfk_1` FOREIGN KEY (`idAccount`) REFERENCES `Accounts` (`idAccount`) ON DELETE SET NULL;

--
-- Contraintes pour la table `ContactMessages`
--
ALTER TABLE `ContactMessages`
  ADD CONSTRAINT `ContactMessages_ibfk_1` FOREIGN KEY (`idAccount`) REFERENCES `Accounts` (`idAccount`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Favorites`
--
ALTER TABLE `Favorites`
  ADD CONSTRAINT `Favorites_ibfk_1` FOREIGN KEY (`idAccount`) REFERENCES `Accounts` (`idAccount`) ON DELETE CASCADE,
  ADD CONSTRAINT `Favorites_ibfk_2` FOREIGN KEY (`idPost`) REFERENCES `Posts` (`idPost`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Grades`
--
ALTER TABLE `Grades`
  ADD CONSTRAINT `Grades_ibfk_1` FOREIGN KEY (`idAccount`) REFERENCES `Accounts` (`idAccount`) ON DELETE CASCADE,
  ADD CONSTRAINT `Grades_ibfk_2` FOREIGN KEY (`idPost`) REFERENCES `Posts` (`idPost`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Posts`
--
ALTER TABLE `Posts`
  ADD CONSTRAINT `Posts_ibfk_1` FOREIGN KEY (`idAccount`) REFERENCES `Accounts` (`idAccount`) ON DELETE SET NULL;

--
-- Contraintes pour la table `SoftwareComments`
--
ALTER TABLE `SoftwareComments`
  ADD CONSTRAINT `SoftwareComments_ibfk_1` FOREIGN KEY (`idPost`) REFERENCES `Posts` (`idPost`) ON DELETE CASCADE,
  ADD CONSTRAINT `SoftwareComments_ibfk_2` FOREIGN KEY (`idPostSoftware`) REFERENCES `Posts` (`idPost`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Softwares`
--
ALTER TABLE `Softwares`
  ADD CONSTRAINT `Softwares_ibfk_1` FOREIGN KEY (`idPost`) REFERENCES `Posts` (`idPost`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
