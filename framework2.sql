SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `gym_id` int(11) NOT NULL,
  `first_name` varchar(200) NOT NULL,
  `last_name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(250) NOT NULL,
  `ci` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(500) NOT NULL,
  `photo` varchar(250) NOT NULL,
  `role` enum('sadmin','admin','client') NOT NULL DEFAULT 'admin',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','inactive','deleted','blocked') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `gym_id` (`gym_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `user` (`id`, `user_id`, `gym_id`, `first_name`, `last_name`, `email`, `password`, `ci`, `phone`, `address`, `photo`, `role`, `date_added`, `status`) VALUES
(1, 0, 0, 'Super', 'Admin', 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3', '', '', '', '', 'sadmin', '2014-08-12 01:32:46', 'active');