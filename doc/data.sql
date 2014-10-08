/*
SQLyog 企业版 - MySQL GUI v8.14 
MySQL - 5.6.20 : Database - jianjia
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`jianjia` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `jianjia`;

/*Table structure for table `member` */

DROP TABLE IF EXISTS `member`;

CREATE TABLE `member` (
  `uid` int(9) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `email` char(40) NOT NULL DEFAULT '' COMMENT '邮箱',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码MD5',
  `avatar` char(32) NOT NULL DEFAULT 'avatar' COMMENT '头像',
  `adminid` tinyint(1) NOT NULL DEFAULT '0' COMMENT '管理组',
  `groupid` tinyint(2) NOT NULL DEFAULT '1' COMMENT '用户组',
  `name` char(24) NOT NULL DEFAULT '' COMMENT '昵称',
  `credits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `regdate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `idx_user_email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='用户表';

/*Data for the table `member` */

insert  into `member`(`uid`,`email`,`password`,`avatar`,`adminid`,`groupid`,`name`,`credits`,`regdate`) values (1,'chao507@vip.qq.com','350448ba0923612e697c575c2d86a1d9','avatar',1,1,'乔豆麻袋',0,1410495269);

/*Table structure for table `node` */

DROP TABLE IF EXISTS `node`;

CREATE TABLE `node` (
  `nid` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `nodename` varchar(24) NOT NULL COMMENT '节点名称',
  `nodealias` varchar(20) NOT NULL COMMENT '别名',
  `nodedesc` text NOT NULL COMMENT '节点描述',
  PRIMARY KEY (`nid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='节点表';

/*Data for the table `node` */

insert  into `node`(`nid`,`nodename`,`nodealias`,`nodedesc`) values (1,'经典短篇','classic-short','聊追一日事，书以为短篇。短篇意为：短小的文章作品或短小的文学作品等。我们说的：散文、诗歌、杂文、论文、故事、语句、短篇小说等都属于短篇！'),(2,'原创美文','original','用笔墨恳谈，以思想交流。文笔不论，且述说自己的灵感！'),(3,'读书笔记','reading-notes','至乐莫如读书。最淡的墨水，也胜过最强的记忆，分享你读到的知识和感悟，同样使你快乐！');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
