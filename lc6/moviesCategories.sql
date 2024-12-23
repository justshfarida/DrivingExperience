

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `idCategory` tinyint(4) NOT NULL AUTO_INCREMENT,
  `category` varchar(20) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  `validCategory` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idCategory`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`idCategory`, `category`, `validCategory`) VALUES
(1, 'Comedy', 1),
(2, 'Fantastic', 1),
(3, 'Thriller', 1),
(4, 'Drama', 1),
(5, 'Historical', 1),
(6, 'Science Fiction', 1),
(7, 'Romance', 1);

-- --------------------------------------------------------

--
-- Structure de la table `movies`
--

DROP TABLE IF EXISTS `movies`;
CREATE TABLE IF NOT EXISTS `movies` (
  `idMovie` int(11) NOT NULL AUTO_INCREMENT,
  `titleMovie` varchar(100) NOT NULL,
  `idCategory` tinyint(4) NOT NULL,
  `validMovie` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idMovie`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `movies`
--

INSERT INTO `movies` (`idMovie`, `titleMovie`, `idCategory`, `validMovie`) VALUES
(1, 'Le dîner de cons', 1, 1),
(2, 'Les bronzés font du ski', 1, 1),
(3, 'Taxi driver', 4, 1),
(4, 'Titanic', 4, 1),
(6, 'Cinquième élément', 2, 1),
(7, 'Cinquième élément', 2, 1),
(8, 'Men in black', 2, 1),
(9, 'Oceans\'s twelve', 3, 1),
(10, 'Shining', 3, 1),
(11, 'Orange mécanique', 2, 1),
(12, 'Le parrain', 3, 1),
(13, 'Gladiator', 5, 1),
(14, 'Le jour le plus long', 5, 1),
(15, 'Les affranchis', 3, 1),
(16, 'Les oiseaux', 4, 1),
(17, 'Jumanji', 2, 1),
(18, 'Bienvenue à Gattaca', 2, 1),
(19, 'Erin Brokovich', 4, 1),
(20, 'Talons aiguilles', 4, 1),
(21, 'Radin', 1, 1),
(24, 'Star Wars', 6, 1),
(25, 'La vie est un long fleuve traquille', 1, 1),
(26, 'Star wars', 6, 1),
(27, 'E.T. \"The extra terrestrial\"', 6, 1),
(28, 'Back to the future', 6, 1);

