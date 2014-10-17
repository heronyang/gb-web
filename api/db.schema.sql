SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- add use `DB_NAME` on heroku

-- user1, user2 is FB real ID
CREATE TABLE `gb` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `user1` bigint(20) NOT NULL,
  `user1_appid` bigint(20) NOT NULL,
  `user1_email` varchar(255) NOT NULL,
  `user1_name` varchar(255) NOT NULL,
  `user2` bigint(20) NOT NULL,
  `user2_tagid` varchar(512) NOT NULL,
  `user2_tagurl` varchar(512) NOT NULL,
  `user2_name` varchar(255) NOT NULL,
  `content` TEXT NOT NULL,
  `status` tinyint(4) NOT NULL,
  `ctime` timestamp NULL DEFAULT NULL,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `gb_success` (
  `gsid` int(11) NOT NULL AUTO_INCREMENT,
  `user1` bigint(20) NOT NULL,
  `user2` bigint(20) NOT NULL,
  `gid1` int(11) NOT NULL,
  `gid2` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `ctime` timestamp NULL DEFAULT NULL,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`gsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
