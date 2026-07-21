/*
    Base de données : `ancres-logicielles`
 */

/*
    Structure de la table `Accounts`
 */
CREATE TABLE `Accounts`
(
    `idAccount`          int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `accountUsername`    varchar(255) NOT NULL UNIQUE,
    `accountPassword`    varchar(255) NOT NULL,
    `accountIsAdmin`     tinyint(1) DEFAULT '0',
    `accountIsModerator` tinyint(1) DEFAULT '0',
    `accountIsBanned`    tinyint(1) DEFAULT '0',
    `accountIsSuspended` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*
    Structure de la table `Posts`
*/
CREATE TABLE `Posts`
(
    `idPost`          int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `postIsBanned`    tinyint(1) DEFAULT '0',
    `postIsPublished` tinyint(1) DEFAULT '0',
    `idAccount`       int(11) DEFAULT NULL,
    FOREIGN KEY (`idAccount`) REFERENCES `Accounts` (`idAccount`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*
    Structure de la table `Anchors`
 */
CREATE TABLE `Anchors`
(
    `idAnchor`       int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `anchorUrl`      varchar(255) NOT NULL UNIQUE,
    `anchorContent`  text         NOT NULL,
    `idPost`         int(11) NOT NULL COMMENT 'Clef du post du lien.',
    `idPostSoftware` int(11) NOT NULL COMMENT 'Clef du post de la fiche logiciel.',
    FOREIGN KEY (`idPost`) REFERENCES `Posts` (`idPost`) ON DELETE CASCADE,
    FOREIGN KEY (`idPostSoftware`) REFERENCES `Posts` (`idPost`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*
    Structure de la table `ContactMessages`
 */
CREATE TABLE `ContactMessages`
(
    `idContactMessage`       int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `contactMessageIsRead`   tinyint(1) DEFAULT '0',
    `contactMessageContent`  text NOT NULL,
    `contactResponseContent` text,
    `idAccount`              int(11) NOT NULL,
    FOREIGN KEY (`idAccount`) REFERENCES `Accounts` (`idAccount`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*
    Structure de la table `Favorites`
*/
CREATE TABLE `Favorites`
(
    `idFavorite` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `idPost`     int(11) NOT NULL,
    `idAccount`  int(11) NOT NULL,
    FOREIGN KEY (`idPost`) REFERENCES `Posts` (`idPost`) ON DELETE CASCADE,
    FOREIGN KEY (`idAccount`) REFERENCES `Accounts` (`idAccount`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*
    Structure de la table `Grades`
*/
CREATE TABLE `Grades`
(
    `idGrade`       int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `gradeUp`       int(11) DEFAULT '0',
    `gradeDown`     int(11) DEFAULT '0',
    `gradeReported` int(11) DEFAULT '0',
    `idPost`        int(11) NOT NULL,
    `idAccount`     int(11) NOT NULL,
    FOREIGN KEY (`idPost`) REFERENCES `Posts` (`idPost`) ON DELETE CASCADE,
    FOREIGN KEY (`idAccount`) REFERENCES `Accounts` (`idAccount`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*
    Structure de la table `SoftwareComments`
*/
CREATE TABLE `SoftwareComments`
(
    `idSoftwareComment` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `softwareComment`   text NOT NULL,
    `idPost`            int(11) NOT NULL COMMENT 'Clef du post du commentaire.',
    `idPostSoftware`    int(11) NOT NULL COMMENT 'Clef du post de la description du logiciel.',
    FOREIGN KEY (`idPost`) REFERENCES `Posts` (`idPost`) ON DELETE CASCADE,
    FOREIGN KEY (`idPostSoftware`) REFERENCES `Posts` (`idPost`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*
    Structure de la table `Softwares`
*/
CREATE TABLE `Softwares`
(
    `idSoftware`      int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `softwareName`    varchar(255) NOT NULL,
    `softwareAvatar`  varchar(255) DEFAULT NULL,
    `softwareSummary` text         NOT NULL,
    `idPost`          int(11) NOT NULL,
    FOREIGN KEY (`idPost`) REFERENCES `Posts` (`idPost`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*
    Données initiales pour la table `Accounts`
 */
INSERT INTO `Accounts` (`idAccount`, `accountUsername`, `accountPassword`, `accountIsAdmin`, `accountIsModerator`, `accountIsBanned`, `accountIsSuspended`) VALUES
(1, 'admin', '$2y$12$W9e2KVUAq9G7WSqlYmJXF.cJapPtBbsVq0SyaxEgePnQ790/VBzTK', 1, 1, 0, 0),
(2, 'regularuser',  '$2y$12$Fw8StMwoPYfz6bVrEBCyS.KQXN/XZBvao2vtl2r0YuTdjggrKVt72', 0, 0, 0, 0);

/*
    Données initiales pour la table `Posts`
 */
INSERT INTO `Posts` (`idPost`, `postIsBanned`, `postIsPublished`, `idAccount`) VALUES
(1, 0, 1, 1),
(2, 0, 1, 1),
(3, 0, 0, 2),
(4, 0, 0, 2);

/*
    Données initiales pour la table `Softwares`
 */
INSERT INTO `Softwares` (`idSoftware`, `softwareName`, `softwareAvatar`, `softwareSummary`, `idPost`) VALUES
(1, 'Blender', NULL, 'Blender est une suite de création 3D gratuite et open source. Elle prend en charge l''ensemble du pipeline 3D : modélisation, rigging, animation, simulation, rendu, compositing et suivi de mouvement, ainsi que le montage vidéo et la création d''actifs pour les jeux. Les utilisateurs avancés utilisent l''API de Blender pour le scripting en Python afin de personnaliser l''application et d''écrire des outils spécialisés ; souvent, ceux-ci sont inclus dans les futures versions de Blender. Blender est bien adapté aux particuliers et aux petits studios qui bénéficient de son pipeline unifié et de son processus de développement réactif. Des exemples de nombreux projets basés sur Blender sont disponibles dans la vitrine. Blender est multiplateforme et fonctionne également bien sur les ordinateurs Linux, Windows et Macintosh. Son interface utilise OpenGL pour offrir une expérience cohérente. Pour confirmer la compatibilité spécifique, la liste des plateformes prises en charge indique celles régulièrement testées par l''équipe de développement.', 1),
(2, 'GIMP', NULL, 'GIMP (GNU Image Manipulation Program) est un logiciel libre de retouche d''images et de création graphique. Il est souvent utilisé pour la retouche photo, la composition d''images et la création d''éléments graphiques. GIMP offre une large gamme d''outils et de fonctionnalités, y compris des calques, des filtres, des pinceaux personnalisables et la prise en charge de nombreux formats de fichiers. Il est disponible sur plusieurs plateformes, notamment Linux, Windows et macOS.', 4);

/*
    Données initiales pour la table `Anchors`
 */
INSERT INTO `Anchors` (`idAnchor`, `anchorUrl`, `anchorContent`, `idPost`, `idPostSoftware`) VALUES
(1,'https://www.blender.org/', 'Site officiel.',2,1),
(2,'https://www.bender.de/fr/', 'Site officiel.',3,1);
