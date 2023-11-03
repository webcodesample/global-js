--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `fd_main_category` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `category`
--

INSERT INTO `fd_main_category` (`id`, `name`) VALUES
(1, 'Hair Style'),
(2, 'Fashion Outfits'),
(3, 'Fashion Accessories'),
(4, 'Skin Care');