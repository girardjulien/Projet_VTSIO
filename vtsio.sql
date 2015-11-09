-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Lun 09 Novembre 2015 à 21:42
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `vtsio`
--

-- --------------------------------------------------------

--
-- Structure de la table `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `idLink` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(500) DEFAULT NULL,
  `title` varchar(500) DEFAULT NULL,
  `source` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`idLink`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=124 ;

--
-- Contenu de la table `links`
--

INSERT INTO `links` (`idLink`, `link`, `title`, `source`) VALUES
(114, 'http://java.developpez.com/', 'Club francophone des développeurs Java : actualités, forums avec sondages et débats, cours, faq, blogs, critiques de livres', 'java.developpez'),
(115, 'http://php.developpez.com/', 'Club des développeurs Web PHP : actualités, cours, tutoriels, programmation, codes sources, livres, outils et forums', 'php.developpez'),
(116, 'http://python.developpez.com/', 'Club des développeurs Python : actualités, cours, tutoriels, faq, sources, forum', 'python.developpez'),
(117, 'http://windows.developpez.com/', 'Windows : Actualités, cours, tutoriels, programmation, FAQ, forum...', 'windows.developpez'),
(118, 'http://www.silicon.fr/nouvelle-build-de-windows-10-satisfera-t-entreprises-130962.html', 'La nouvelle Build de Windows 10 satisfera-t-elle l&#039;IT ?', 'silicon.fr'),
(119, 'http://www.jython.org/', 'The Jython Project', 'jython'),
(120, 'http://www.developpez.net/forums/d1343780/php/scripts/lancer-script-python-php/', ' comment lancer un script python depuis php', 'developpez'),
(121, 'http://boraklerouge.free.fr/PHP-AideMemoire/', 'Aide-MÃ©moire PHP', 'boraklerouge.free'),
(122, 'http://www.silicon.fr/faille-java-danger-jboss-jenkins-weblogic-websphere-131022.html', 'Faille Java : JBoss, Jenkins, Weblogic et Websphere en danger', 'silicon.fr'),
(123, 'http://www.sitepoint.com/how-to-install-php-on-windows/', 'How to Install PHP on Windows', 'sitepoint');

-- --------------------------------------------------------

--
-- Structure de la table `links_has_tags`
--

CREATE TABLE IF NOT EXISTS `links_has_tags` (
  `Links_idLink` int(11) NOT NULL,
  `Tags_idTag` int(11) NOT NULL,
  PRIMARY KEY (`Links_idLink`,`Tags_idTag`),
  KEY `fk_Links_has_Tags_Tags1_idx` (`Tags_idTag`),
  KEY `fk_Links_has_Tags_Links_idx` (`Links_idLink`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `links_has_tags`
--

INSERT INTO `links_has_tags` (`Links_idLink`, `Tags_idTag`) VALUES
(114, 1),
(119, 1),
(122, 1),
(116, 2),
(119, 2),
(120, 2),
(115, 3),
(120, 3),
(121, 3),
(123, 3),
(117, 4),
(118, 4),
(123, 4);

-- --------------------------------------------------------

--
-- Structure de la table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `idTag` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idTag`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `tags`
--

INSERT INTO `tags` (`idTag`, `tag`) VALUES
(1, 'Java'),
(2, 'Python'),
(3, 'PHP'),
(4, 'Windows');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `links_has_tags`
--
ALTER TABLE `links_has_tags`
  ADD CONSTRAINT `fk_Links_has_Tags_Tags1` FOREIGN KEY (`Tags_idTag`) REFERENCES `tags` (`idTag`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Links_has_Tags_Links` FOREIGN KEY (`Links_idLink`) REFERENCES `links` (`idLink`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
