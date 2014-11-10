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

/*Table structure for table `authorization` */

DROP TABLE IF EXISTS `authorization`;

CREATE TABLE `authorization` (
  `aid` bigint(20) unsigned NOT NULL COMMENT '主键、二进制',
  `name` varchar(24) NOT NULL COMMENT '权限名称',
  `alias` varchar(20) NOT NULL COMMENT '权限别名',
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='权限表';

/*Data for the table `authorization` */

/*Table structure for table `conments` */

DROP TABLE IF EXISTS `conments`;

CREATE TABLE `conments` (
  `cid` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `tid` int(10) NOT NULL DEFAULT '0' COMMENT '话题id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `username` char(24) NOT NULL DEFAULT '' COMMENT '用户名',
  `comment` text NOT NULL COMMENT '评论内容',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，1-锁定，0-正常',
  `postdate` int(10) NOT NULL COMMENT '发布时间',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='评论表';

/*Data for the table `conments` */

/*Table structure for table `member` */

DROP TABLE IF EXISTS `member`;

CREATE TABLE `member` (
  `uid` int(9) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `email` char(40) NOT NULL DEFAULT '' COMMENT '邮箱',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码MD5',
  `avatar` char(32) NOT NULL DEFAULT 'avatar' COMMENT '头像',
  `adminid` tinyint(1) NOT NULL DEFAULT '0' COMMENT '管理组',
  `groupid` tinyint(2) NOT NULL DEFAULT '0' COMMENT '用户组',
  `username` char(24) NOT NULL DEFAULT '' COMMENT '昵称',
  `signature` text NOT NULL COMMENT '签名',
  `credits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `regdate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `idx_user_email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='用户表';

/*Data for the table `member` */

insert  into `member`(`uid`,`email`,`password`,`avatar`,`adminid`,`groupid`,`username`,`signature`,`credits`,`regdate`) values (1,'chao507@vip.qq.com','350448ba0923612e697c575c2d86a1d9','avatar',1,1,'乔豆麻袋','自从一见桃花后，直至如今更不疑',0,1410495269),(2,'515189259@qq.com','04bb42239f79e62b69033a402c264a92','avatar',0,1,'直至如今更不疑','',0,1410495269);

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

/*Table structure for table `role` */

DROP TABLE IF EXISTS `role`;

CREATE TABLE `role` (
  `rid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(24) NOT NULL COMMENT '角色名',
  `value` bigint(20) NOT NULL COMMENT '权限值',
  PRIMARY KEY (`rid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='角色表';

/*Data for the table `role` */

insert  into `role`(`rid`,`name`,`value`) values (1,'站长',0);

/*Table structure for table `topic` */

DROP TABLE IF EXISTS `topic`;

CREATE TABLE `topic` (
  `tid` int(9) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `nid` tinyint(1) unsigned NOT NULL COMMENT '节点id，索引idx_topic_node',
  `uid` int(9) unsigned NOT NULL COMMENT '用户id',
  `username` char(24) NOT NULL COMMENT '用户名',
  `title` varchar(300) NOT NULL COMMENT '主题',
  `content` mediumtext NOT NULL COMMENT '内容',
  `top` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '置顶贴，默认0-不置顶、1-节点置顶、2-全局置顶索引idx_topic_top',
  `rate` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '精华贴，默认0-非精华、1-精华',
  `commit` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许评论，默认0-允许、1-不允许',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '帖子状态，默认0-正常、1-锁贴，索引idx_topic_status',
  `postdate` int(10) unsigned NOT NULL COMMENT '发表时间，unix时间戳',
  PRIMARY KEY (`tid`),
  KEY `idx_topic_node` (`nid`),
  KEY `idx_topic_user` (`uid`),
  KEY `idx_topic_top` (`top`,`status`),
  KEY `idx_topic_status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='话题表';

/*Data for the table `topic` */

insert  into `topic`(`tid`,`nid`,`uid`,`username`,`title`,`content`,`top`,`rate`,`commit`,`status`,`postdate`) values (2,1,1,'乔豆麻袋','像春天一样','<p>&nbsp; &nbsp; &nbsp; &nbsp;我在街角杂品店前停下来吃早餐。因为有些迟了，便急匆匆地吃了些炸面圈，喝了咖啡后就急步走进地铁站，跑下台阶，赶上了我常搭的那趟列车。我抓住吊带，装作看报，却不停地扫视这些挤在我周围的人们。他们还是我每天看到的人。他们认识我，我也认识他们，我们却没有微笑，像是偶遇的陌生人。&nbsp;</p><p>　　我听他们谈他们的烦忧和朋友，我也希望有人来与我谈天，以打破长长铁骑发出的单调的声音。&nbsp;</p><p>　　地铁快到第175街的时候，我又紧张起来。她通常就在那站上车。她举止文雅，不像其他人那样推推搡搡。她总是挤进一个小地方，紧挨着人们，紧握住一个大概包着她午餐的机关信袋。她从不带一张报纸或一本书；我想要是你撞上这种情况，再想看书看报也是看不进去的。</p><p>　　她身着鲜艳的户外装束，我猜她大概住在新泽西。这些新泽西人到达了那个车站。她的脸蛋很漂亮，擦洗得干干净净，根本不必涂脂抹粉。她除了涂口红外从不化妆。她天然的波浪式头发，呈显协调的浅棕色，就像飘落的白杨树叶的色调。其余她所做的就是抓住车的辕杆，想着她自己的主意。她那双明亮的蓝眼睛温情脉脉。&nbsp;</p><p>　　我总是喜欢看着她，但又得小心翼翼，唯恐她发现我在看她，怕她生气，怕她离我而去，那样我便没有任何朋友了，因为她是我唯一真正的朋友，尽管她好像还不知道。我孤身一人在纽约，我认为我有点怕羞，不容易交朋友。同伴们都有家室，他们要过他们自己的生活，我怎能邀请人家到我的单身房间来呢？因此只好他们走他们的阳关道，我过我的独木桥。&nbsp;</p><p>　　这座城市真使我心烦。它过于庞大，人声嘈杂——对我这个独行者来说人也太多了。我大概适应不了它。我曾习惯于小新罕布什尔农场的宁静，但在那里不会有任何远大前程。后来我从海军退伍，就申请到了银行的这个职位。我料想这是一个好机会，但我却是孤独寂寞。&nbsp;</p><p>　　当坐车前行我身体随车子的运动而摇晃时，我喜欢想象我和她是朋友，甚至有时我被诱惑而对她微笑，很友好而非冒失地说些诸如“早上天气真好，是吗？”之类的话。可是我会惊慌的。她也许会以为我狡猾，会冷淡我，似乎根本没有看到我，仿佛我不存在。于是第二天早晨，她再也不在这儿，我也没有任何人去想了。我一直梦想或许总有一天我要结识她。你知道，要自然而然地。&nbsp;</p><p>　　或许像这样：她从车门进来，有人推着了她，使她擦着了我。她会敏捷地说：“哦，请原谅。”&nbsp;</p><p>　　我就礼貌地举起帽子答道：“一点都没关系”。并向她微笑以示我不在意，于是她会对我回报一笑说：“天气真好，是吗？”那我就说：“像春天一样。”我们大概不再说啥，但当她在第34街准备下车时，大概会朝我轻轻挥手说声“再见”的，我就再次斜帽致意。&nbsp;</p><p>　　第二天早晨，她进来见到我就会说“你好！”或“早上好！”那我也给她打招呼，再说些使她看出我对春天还稍有了解的话。不给她说俏皮话，因为我不愿让她把我看成那种油腔滑调、在地铁里随便结交姑娘的人。&nbsp;</p><p>　　不多久，我们将有些友情，开始谈论天气和新闻等。有一天她会说：“你说滑稽不？我们天天在这儿交谈，却连各自的名字都不知道。”我就站得笔直，倾斜我的帽子说：“我喜欢你认识托马斯·皮尔斯先生。”她也会很认真地说：“您好，皮尔斯先生。我要你认识伊丽莎白·阿尔特梅丝小姐”。她一定是戴着那种姑娘们春天常戴的白手套。我们周围的人会微笑，他们也在分享我俩的欢乐。&nbsp;</p><p>　　“托马斯。”她说，当她试着把我的名字念出声来时。&nbsp;</p><p>　　“干嘛？”我就问。&nbsp;</p><p>　　“我总不能叫你托马斯。”她说：“那太拘谨了。”&nbsp;</p><p>　　“我的朋友管我叫汤米。”我就告诉她。&nbsp;</p><p>　　“我的朋友叫我贝蒂。”&nbsp;</p><p>　　大概就会这样。或许不久后我会提到一部正在音乐大厅上映的好影片的名字，假如她有空，我就建议去看——她会立刻说：“嗬，我也喜欢看！”我就早点完成工作到她工作的地方去接她，一起出去找个地方共进晚餐。进餐时我就与她谈，告诉她新罕布什尔，或许说起我曾多么孤寂，如果那是一个安静舒适的好座位，我还可能告诉她我曾多么怕羞。&nbsp;</p><p>　　她会用闪亮的眼睛盯着我仔细听，双手手指交叉紧握，倚在桌上，让我能闻到她头发的芳香。她会低语：“我也怕羞。”我们背靠背，悄悄地微笑，接着就吃饭，不再说啥。&nbsp;</p><p>　　此后，我们就一起去影院欣赏电影。有时在影片的精彩片段，她的手大概会碰我的手，或许我移动身姿用手偶然摸摸她的手，她不挪开，我就抓住它。我在这里，在上千人中间，再不感到孤独：我和我的女朋友在一起。&nbsp;</p><p>　　然后，我送她回家。她不会要我走完全程的。“我住在新泽西。”她会说：“你送我回家，真是太好了，但我不能要你像这样走很远的路。别担心，我没事儿。”但我会抓住她的胳臂说：“跟我走。我要送你回家。我喜欢新泽西。”我们就乘公共汽车穿过乔治·华盛顿大桥，跨过它下面奔流不息，黑色而又神秘的哈得逊河，就到新泽西了。我们见到了她家院落的灯火，她会邀请我进去，但我就说太迟了，于是她会恳求我：“那么你得答应我这周星期天来吃晚饭。”我就答应，然后……列车慢了下来，因为停车，人们努力使自己站稳。这就是第175街站，一大群人等着上车。我渴望找到她，却到处也看不到。我心绪低落，可正在这时却发现她在另一侧。她戴着一顶新帽子，上面有几朵小花。车门一打开，人们就朝里涌。&nbsp;</p><p>　　她夹在蜂拥的人流中不能动弹，猛地撞到我身上，拼命一把抓住我正握住的吊带不放。&nbsp;</p><p>　　“请原谅。”她气喘吁吁。&nbsp;</p><p>　　我的双手被压着，不能倾斜我的帽子，但我礼貌地答道：“没关系。”&nbsp;</p><p>　　车门关起来，列车开动了。她只好抓住我的吊带，没有其他任何位置了。&nbsp;</p><p>　　“今天天气真好，是吗？”她说。&nbsp;</p><p>　　列车正在转弯，车轮擦着铁轨发出尖锐的声音，就像新罕布什尔的鸟儿歌唱。&nbsp;</p><p>　　我的心疯狂地跳动着。&nbsp;</p><p>　　“象春天一样。”我说。</p>',0,0,0,0,1415348267),(3,3,1,'乔豆麻袋','我听Codeplay那些年','<p><img alt=\\\"e7cd7b899e510fb3b3c86b76d833c895d0430c79.jpg\\\" src=\\\"http://jianjia.club//uploads/201411/adda1176697983d84c8dff405ac37b3f.jpg\\\"><br></p><p><img alt=\\\"997e90529822720ebed929d67bcb0a46f21fab15.jpg\\\" src=\\\"http://jianjia.club//uploads/201411/214c6fe71166e0358e6f18a490d49831.jpg\\\"><br></p>',0,0,0,0,1415350823),(4,3,1,'乔豆麻袋','我听Codeplay那些年','<p><img alt=\\\"e7cd7b899e510fb3b3c86b76d833c895d0430c79.jpg\\\" src=\\\"http://jianjia.club//uploads/201411/adda1176697983d84c8dff405ac37b3f.jpg\\\"><br></p><p><img alt=\\\"997e90529822720ebed929d67bcb0a46f21fab15.jpg\\\" src=\\\"http://jianjia.club//uploads/201411/214c6fe71166e0358e6f18a490d49831.jpg\\\"><br></p>',0,0,0,0,1415350932),(5,3,1,'乔豆麻袋','我听Codeplay那些年','<p><img alt=\\\"e7cd7b899e510fb3b3c86b76d833c895d0430c79.jpg\\\" src=\\\"http://jianjia.club//uploads/201411/adda1176697983d84c8dff405ac37b3f.jpg\\\"><br></p><p><img alt=\\\"997e90529822720ebed929d67bcb0a46f21fab15.jpg\\\" src=\\\"http://jianjia.club//uploads/201411/214c6fe71166e0358e6f18a490d49831.jpg\\\"><br></p>',0,0,0,0,1415350956),(6,3,1,'乔豆麻袋','我听Codeplay那些年','<p><img alt=\\\"e7cd7b899e510fb3b3c86b76d833c895d0430c79.jpg\\\" src=\\\"http://jianjia.club//uploads/201411/adda1176697983d84c8dff405ac37b3f.jpg\\\"><br></p><p><img alt=\\\"997e90529822720ebed929d67bcb0a46f21fab15.jpg\\\" src=\\\"http://jianjia.club//uploads/201411/214c6fe71166e0358e6f18a490d49831.jpg\\\"><br></p>',0,0,0,0,1415351011),(7,1,1,'','阿德所发生的','<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</p><p>			</p><p>					</p><p>		</p>',0,0,0,0,1415610028),(8,1,1,'乔豆麻袋','a第三方','<p>adsf</p><p>					</p>',0,0,0,0,1415610399);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
