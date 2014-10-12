SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `gb` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `user1` bigint(20) NOT NULL,
  `user2` bigint(20) NOT NULL,
  `content` TEXT NOT NULL,
  `status` tinyint(4) NOT NULL,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ctime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `gb_success` (
  `gsid` int(11) NOT NULL AUTO_INCREMENT,
  `user1` bigint(20) NOT NULL,
  `user2` bigint(20) NOT NULL,
  `gid1` int(11) NOT NULL,
  `gid2` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ctime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`gsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
