DROP TABLE IF EXISTS `#__skroutzeasy`;

CREATE TABLE IF NOT EXISTS `#__skroutzeasy` (
  `sid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` varchar(200) NOT NULL,
  `client_secret` varchar(200) NOT NULL,
  `redirect_uri` varchar(200) NOT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;