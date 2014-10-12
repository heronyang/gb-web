SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- user1, user2 is FB real ID
CREATE TABLE `gb` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `user1` bigint(20) NOT NULL,
  `user1_appid` bigint(20) NOT NULL,
  `user2` bigint(20) NOT NULL,
  `user2_tagid` varchar(512) NOT NULL,
  `content` TEXT NOT NULL,
  `status` tinyint(4) NOT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`gsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
