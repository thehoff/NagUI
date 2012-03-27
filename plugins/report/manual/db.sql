
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


CREATE TABLE IF NOT EXISTS `log` (
  `event_time` datetime NOT NULL,
  `host` varchar(64) NOT NULL,
  `service` varchar(64) NOT NULL,
  `status` varchar(64) NOT NULL,
  `status_type` varchar(10) NOT NULL,
  `count` int(11) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


ALTER TABLE `log` ADD UNIQUE (
`event_time` ,
`host` ,
`service`
)