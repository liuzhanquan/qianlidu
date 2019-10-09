-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2019 �?10 �?09 �?11:49
-- 服务器版本: 5.5.53
-- PHP 版本: 7.0.12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `qianlidu`
--

-- --------------------------------------------------------

--
-- 表的结构 `cm_admin`
--

CREATE TABLE IF NOT EXISTS `cm_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(100) DEFAULT NULL,
  `password` char(100) DEFAULT NULL,
  `name` char(100) DEFAULT NULL,
  `phone` char(50) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `login_time` int(11) DEFAULT NULL,
  `reg_time` int(11) DEFAULT NULL,
  `loginnum` int(11) DEFAULT NULL,
  `status` tinyint(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `is_sys` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `cm_admin`
--

INSERT INTO `cm_admin` (`id`, `username`, `password`, `name`, `phone`, `remark`, `login_time`, `reg_time`, `loginnum`, `status`, `role_id`, `is_sys`) VALUES
(1, 'admin', '###d11e8f2f44c9512290f778da2df1c60b', '超级管理员', '15817090127', '超级管理员', 1570619530, 1489201095, 71, 1, 1, 1),
(2, 'admins', '###d11e8f2f44c9512290f778da2df1c60b', '测试', '15817090127', '测试', 1570519272, 1520943366, NULL, 1, 2, 0),
(3, 'adminsss', '###bd031957887b1a5a33768341342b9f98', '测试2', '13265964401', '333333', 1529574236, 1529574236, NULL, 1, 2, 0);

-- --------------------------------------------------------

--
-- 表的结构 `cm_admin_group`
--

CREATE TABLE IF NOT EXISTS `cm_admin_group` (
  `gid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(100) DEFAULT NULL,
  `menu_power` text,
  `status` tinyint(11) DEFAULT '1',
  `power` text,
  `is_sys` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `cm_admin_group`
--

INSERT INTO `cm_admin_group` (`gid`, `name`, `menu_power`, `status`, `power`, `is_sys`) VALUES
(1, '超级管理员', NULL, 1, NULL, 1),
(2, '测试级别', '2,14,18,43,44,45,3,4,46,11,12', 1, 'Index/home,System/index,System/upload,System/banner,System/sms,System/sms_log,Goods/index,Goods/search_goods,Order/index,User/index,User/douser,User/level,User/dolevel,User/up,User/doup,News/index,News/category,News/docategory,News/single,Finance/setting', 0);

-- --------------------------------------------------------

--
-- 表的结构 `cm_admin_power`
--

CREATE TABLE IF NOT EXISTS `cm_admin_power` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(11) DEFAULT NULL,
  `name` char(100) DEFAULT NULL,
  `control` char(100) DEFAULT NULL,
  `action` char(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

--
-- 转存表中的数据 `cm_admin_power`
--

INSERT INTO `cm_admin_power` (`id`, `parent`, `name`, `control`, `action`) VALUES
(1, 37, '浏览', 'Index', 'home'),
(2, 8, '浏览/编辑', 'System', 'index'),
(3, 9, '浏览/编辑', 'System', 'upload'),
(4, 48, '浏览', 'System', 'users'),
(5, 48, '编辑', 'System', 'douser'),
(6, 48, '删除', 'System', 'del/admin'),
(7, 76, '浏览/编辑', 'System', 'banner'),
(8, 78, '浏览', 'System', 'roles'),
(9, 78, '添加/编辑', 'System', 'doroles'),
(10, 78, '删除', 'System', 'del/roles'),
(11, 74, '浏览/编辑', 'System', 'sms'),
(12, 75, '查看', 'System', 'sms_log'),
(13, 43, '浏览', 'Goods', 'index'),
(14, 43, '添加/编辑', 'Goods', 'dogoods'),
(15, 43, '搜索', 'Goods', 'search_goods'),
(16, 43, '删除', 'Goods', 'del/goods'),
(17, 43, '批量删除', 'Goods', 'batch/goodsdel'),
(18, 63, '浏览', 'Order', 'index'),
(19, 63, '查看/编辑', 'Order', 'doorder'),
(20, 63, '删除', 'Order', 'del/order'),
(21, 67, '浏览', 'User', 'index'),
(22, 67, '添加/编辑', 'User', 'douser'),
(23, 67, '搜索', 'User', 'search_user'),
(24, 67, '审核', 'User', 'option'),
(25, 67, '调整级别', 'User', 'userlevel'),
(26, 68, '浏览', 'User', 'level'),
(27, 68, '添加/编辑', 'User', 'dolevel'),
(28, 67, '删除', 'User', 'del/user'),
(29, 68, '删除', 'User', 'del/level'),
(30, 77, '浏览', 'User', 'up'),
(31, 77, '处理申请', 'User', 'doup'),
(32, 77, '删除', 'User', 'del/user_up'),
(33, 55, '浏览', 'News', 'index'),
(34, 55, '添加/编辑', 'News', 'donews'),
(35, 55, '删除', 'News', 'del/news'),
(36, 55, '批量删除', 'News', 'batch/news'),
(37, 56, '浏览', 'News', 'category'),
(38, 56, '添加/编辑', 'News', 'docategory'),
(39, 56, '删除', 'News', 'del/category'),
(40, 57, '浏览', 'News', 'single'),
(41, 57, '添加/编辑', 'News', 'dosingle'),
(42, 57, '删除', 'News', 'del/category'),
(43, 71, '浏览', 'Finance', 'index'),
(44, 71, '审核', 'Finance', 'option'),
(45, 71, '删除', 'Finance', 'del/cash'),
(46, 72, '浏览/编辑', 'Finance', 'setting'),
(47, 18, '浏览', 'User', 'up');

-- --------------------------------------------------------

--
-- 表的结构 `cm_agent`
--

CREATE TABLE IF NOT EXISTS `cm_agent` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `name` varchar(50) DEFAULT NULL COMMENT '代理商名称',
  `level` int(11) NOT NULL COMMENT '代理商等级',
  `business` tinyint(4) DEFAULT '0' COMMENT '是否商家',
  `card_img` varchar(100) DEFAULT NULL COMMENT '身份证照片',
  `money_img` varchar(100) DEFAULT NULL COMMENT '打款截图',
  `card_num` varchar(20) DEFAULT NULL COMMENT '身份证号',
  `province` varchar(30) DEFAULT NULL COMMENT '省',
  `city` varchar(30) DEFAULT NULL COMMENT '市',
  `area` varchar(30) DEFAULT NULL COMMENT '区',
  `address` varchar(50) DEFAULT NULL COMMENT '详细地址',
  `start_time` int(11) NOT NULL COMMENT '权限时间 开始',
  `stop_time` int(11) NOT NULL COMMENT '权限时间 结束',
  `content` varchar(255) DEFAULT NULL COMMENT '备注',
  `add_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `status` tinyint(11) DEFAULT '1' COMMENT '是否启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='代理商、商家表' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `cm_agent`
--

INSERT INTO `cm_agent` (`id`, `uid`, `name`, `level`, `business`, `card_img`, `money_img`, `card_num`, `province`, `city`, `area`, `address`, `start_time`, `stop_time`, `content`, `add_time`, `update_time`, `status`) VALUES
(00000000001, 85, 'cddd', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1569736845, 1579642165, NULL, 0, 1570614567, 1),
(00000000002, 90, '广州小代', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1569430845, 1579651225, NULL, 0, 1570608850, 1);

-- --------------------------------------------------------

--
-- 表的结构 `cm_agent_card`
--

CREATE TABLE IF NOT EXISTS `cm_agent_card` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `card_num` bigint(20) NOT NULL COMMENT '卡号',
  `password` varchar(50) NOT NULL COMMENT '卡密码',
  `card_type` int(11) NOT NULL DEFAULT '0' COMMENT '卡片类型',
  `card_state` tinyint(4) DEFAULT '0' COMMENT '是否激活',
  `card_style` tinyint(4) NOT NULL COMMENT '是否实体卡',
  `print_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否印刷',
  `up_num` tinyint(4) DEFAULT '0' COMMENT '提交次数限制',
  `charge_time` int(11) NOT NULL COMMENT '充值时间',
  `gid` int(11) NOT NULL COMMENT '代理人ID',
  `start_time` int(11) NOT NULL COMMENT '使用时间 开始',
  `stop_time` int(11) NOT NULL COMMENT '使用时间 结束',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `add_time` int(11) NOT NULL COMMENT '创建时间',
  `state` tinyint(4) DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='代理商卡片' AUTO_INCREMENT=92 ;

--
-- 转存表中的数据 `cm_agent_card`
--

INSERT INTO `cm_agent_card` (`id`, `card_num`, `password`, `card_type`, `card_state`, `card_style`, `print_status`, `up_num`, `charge_time`, `gid`, `start_time`, `stop_time`, `update_time`, `add_time`, `state`) VALUES
(0000000001, 190928114251075, '20708586', 2, 0, 2, 1, 0, 259200, 2, 1569600000, 1572451200, 1570609723, 1569642165, 1),
(0000000002, 190928114232538, '68159004', 1, 0, 1, 0, 0, 86400, 2, 1569600000, 1572451200, 1569642165, 1569642165, 1),
(0000000003, 190928114218541, '52246899', 1, 0, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1569809285, 1569642165, 1),
(0000000004, 190928114273142, '66614072', 1, 0, 1, 0, 1, 86400, 1, 1569600000, 1572451199, 1567642165, 1569642165, 1),
(0000000005, 190928114289410, '57530893', 1, 0, 1, 0, 1, 86400, 1, 1569600000, 1572451198, 1569666838, 1569642165, 1),
(0000000006, 190928114226756, '81017001', 1, 0, 1, 0, 0, 86400, 1, 1569600000, 1571451200, 1569642165, 1569642165, 1),
(0000000007, 190928114214164, '38201598', 1, 1, 1, 0, 1, 86400, 1, 1569600000, 1572451200, 1570602848, 1569642165, 1),
(0000000008, 190928114262509, '74823170', 1, 1, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1570614796, 1569642165, 3),
(0000000009, 190928114253989, '89684364', 1, 0, 1, 0, 1, 86400, 1, 1569600000, 1572451200, 1569667825, 1569642165, 1),
(0000000010, 190928114250407, '78852006', 1, 0, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1569812898, 1569642165, 1),
(0000000011, 190928114234189, '47339413', 1, 0, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1569813192, 1569642165, 1),
(0000000012, 190928114296466, '75443726', 1, 0, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1569813251, 1569642165, 1),
(0000000013, 190928114292852, '48210059', 1, 0, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1569642165, 1569642165, 1),
(0000000014, 190928114243475, '60277139', 1, 0, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1569642165, 1569642165, 1),
(0000000015, 190928114285482, '72143821', 1, 0, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1569642165, 1569642165, 1),
(0000000016, 190928114282065, '96373639', 1, 0, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1569642165, 1569642165, 1),
(0000000017, 190928114285534, '15077705', 1, 0, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1569642165, 1569642165, 1),
(0000000018, 190928114238258, '52100627', 1, 0, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1569642165, 1569642165, 1),
(0000000019, 190928114263267, '32913418', 1, 0, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1569642165, 1569642165, 1),
(0000000020, 190928114277863, '17419710', 1, 0, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1569642165, 1569642165, 1),
(0000000021, 190928114354232, '25744205', 2, 0, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1569642211, 1569642211, 1),
(0000000022, 190928114338891, '86880902', 2, 0, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1569642211, 1569642211, 1),
(0000000023, 190928114372847, '62496296', 2, 0, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1569642211, 1569642211, 1),
(0000000024, 190928114391293, '18856229', 2, 0, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1569642211, 1569642211, 1),
(0000000025, 190928114391451, '58174376', 2, 0, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1569642211, 1569642211, 1),
(0000000026, 190928114348836, '13954285', 2, 0, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1569642211, 1569642211, 1),
(0000000027, 190928114323400, '77241926', 2, 0, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1569642211, 1569642211, 1),
(0000000028, 190928114396403, '51057323', 2, 0, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1569642211, 1569642211, 1),
(0000000029, 190928114315049, '96472745', 2, 0, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1569642211, 1569642211, 1),
(0000000030, 190928114313514, '47443009', 2, 0, 1, 0, 0, 86400, 1, 1569600000, 1572451200, 1569642211, 1569642211, 1),
(0000000031, 190928114324005, '94549661', 2, 0, 1, 0, 0, 0, 1, 1569600000, 1572451200, 1569642211, 1569642211, 1),
(0000000032, 190928114385601, '95248557', 2, 0, 1, 0, 0, 0, 1, 1569600000, 1572451200, 1569642211, 1569642211, 1),
(0000000033, 190928114378128, '29753707', 2, 0, 1, 0, 0, 0, 1, 1569600000, 1572451200, 1569642211, 1569642211, 1),
(0000000034, 190928114311441, '65488858', 2, 0, 1, 0, 0, 0, 1, 1569600000, 1572451200, 1569642211, 1569642211, 1),
(0000000035, 190928114365699, '84739647', 2, 0, 1, 0, 0, 0, 1, 1569600000, 1572451200, 1569642211, 1569642211, 1),
(0000000036, 190928114326879, '39394678', 2, 0, 1, 0, 0, 0, 1, 1569600000, 1572451200, 1569642211, 1569642211, 1),
(0000000037, 190928114319246, '13067917', 2, 0, 1, 0, 0, 0, 1, 1569600000, 1572451200, 1569642211, 1569642211, 1),
(0000000038, 190928114385250, '80793136', 2, 0, 1, 0, 0, 0, 1, 1569600000, 1572451200, 1569642211, 1569642211, 1),
(0000000039, 190928114492999, '93224454', 2, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642245, 1569642245, 1),
(0000000040, 190928114451341, '78187073', 2, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642245, 1569642245, 1),
(0000000041, 190928114465212, '94589948', 2, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642245, 1569642245, 1),
(0000000042, 190928114442907, '74025712', 2, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642245, 1569642245, 1),
(0000000043, 190928114418132, '60250220', 2, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642245, 1569642245, 1),
(0000000044, 190928114425752, '48892383', 2, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642245, 1569642245, 1),
(0000000045, 190928114466178, '12597625', 2, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642245, 1569642245, 1),
(0000000046, 190928114458989, '87097319', 2, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642245, 1569642245, 1),
(0000000047, 190928114447944, '34761030', 2, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642245, 1569642245, 1),
(0000000048, 190928114492515, '72096126', 2, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642245, 1569642245, 1),
(0000000049, 190928114480561, '28483350', 2, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642245, 1569642245, 1),
(0000000050, 190928114490797, '86272626', 2, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642245, 1569642245, 1),
(0000000051, 190928114460895, '55952569', 2, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642245, 1569642245, 1),
(0000000052, 190928114419875, '88623653', 2, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642245, 1569642245, 1),
(0000000053, 190928114411807, '25444684', 2, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642245, 1569642245, 1),
(0000000054, 190928114475536, '20141489', 2, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642245, 1569642245, 1),
(0000000055, 190928114496541, '25457758', 2, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642254, 1569642254, 1),
(0000000056, 190928114414781, '96060256', 2, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642254, 1569642254, 1),
(0000000057, 190928114471151, '46371832', 2, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642254, 1569642254, 1),
(0000000058, 190928114424623, '61295991', 2, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642254, 1569642254, 1),
(0000000059, 190928114475956, '84658241', 2, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642254, 1569642254, 1),
(0000000060, 190928114451423, '57606127', 2, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642254, 1569642254, 1),
(0000000061, 190928114449623, '22580877', 2, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642254, 1569642254, 1),
(0000000062, 190928114475113, '56694125', 2, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642254, 1569642254, 1),
(0000000063, 190928114433509, '60690858', 2, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642254, 1569642254, 1),
(0000000064, 190928114455349, '74937388', 2, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642254, 1569642254, 1),
(0000000065, 190928114416984, '50027641', 2, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642254, 1569642254, 1),
(0000000066, 190928114428716, '23152493', 2, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642254, 1569642254, 1),
(0000000067, 190928114446931, '78079402', 2, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642254, 1569642254, 1),
(0000000068, 190928114460377, '92240180', 1, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642265, 1569642265, 1),
(0000000069, 190928114461051, '67131036', 1, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642265, 1569642265, 1),
(0000000070, 190928114433365, '80658226', 1, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642265, 1569642265, 1),
(0000000071, 190928114494953, '49385025', 1, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642265, 1569642265, 1),
(0000000072, 190928114412407, '68023743', 1, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642265, 1569642265, 1),
(0000000073, 190928114420172, '55084954', 1, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642265, 1569642265, 1),
(0000000074, 190928114465521, '57731068', 1, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642265, 1569642265, 1),
(0000000075, 190928114442481, '42674458', 1, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642265, 1569642265, 1),
(0000000076, 190928114496356, '66165503', 1, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642265, 1569642265, 1),
(0000000077, 190928114423508, '22732016', 1, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642265, 1569642265, 1),
(0000000078, 190928114417030, '68219754', 1, 0, 3, 0, 0, 0, 1, 1569600000, 1572451200, 1569642265, 1569642265, 1),
(0000000079, 190928114455573, '90997522', 1, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642277, 1569642277, 1),
(0000000080, 190928114445169, '41171507', 1, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642277, 1569642277, 1),
(0000000081, 190928114473103, '29252535', 1, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642277, 1569642277, 1),
(0000000082, 190928114499441, '48065123', 1, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642277, 1569642277, 1),
(0000000083, 190928114431986, '23650367', 1, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642277, 1569642277, 1),
(0000000084, 190928114418855, '27248301', 1, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642277, 1569642277, 1),
(0000000085, 190928114464115, '92614333', 1, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642277, 1569642277, 1),
(0000000086, 190928114473993, '43603202', 1, 0, 2, 0, 0, 0, 1, 1569600000, 1572451200, 1569642277, 1569642277, 1),
(0000000087, 191009171213205, '16722431', 1, 0, 2, 0, 0, 93312000, 0, 1570525769, 1571735371, 1570612320, 1570612320, 1),
(0000000088, 191009171299087, '23473362', 1, 0, 2, 0, 0, 93312000, 0, 1570525769, 1571735371, 1570612320, 1570612320, 1),
(0000000089, 191009171210580, '18722362', 1, 0, 2, 0, 0, 93312000, 0, 1570525769, 1571735371, 1570612320, 1570612320, 1),
(0000000090, 191009171218019, '87066469', 1, 0, 2, 0, 0, 93312000, 0, 1570525769, 1571735371, 1570612320, 1570612320, 1),
(0000000091, 191009171247205, '43524056', 1, 0, 2, 0, 0, 93312000, 2, 1570525769, 1571735371, 1570612624, 1570612320, 1);

-- --------------------------------------------------------

--
-- 表的结构 `cm_card_type`
--

CREATE TABLE IF NOT EXISTS `cm_card_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '卡类名称',
  `title` varchar(100) DEFAULT NULL COMMENT '备注',
  `add_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='激活卡规格配置' AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `cm_card_type`
--

INSERT INTO `cm_card_type` (`id`, `name`, `title`, `add_time`, `update_time`) VALUES
(1, 'A', '一类客户', 1570602100, 1570602100),
(2, 'B', '二类客户', 1570602100, 1570602134),
(3, 'C', '三类客户', 1570602152, 1570602152);

-- --------------------------------------------------------

--
-- 表的结构 `cm_card_user`
--

CREATE TABLE IF NOT EXISTS `cm_card_user` (
  `cid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `add_time` int(11) NOT NULL,
  `status` int(11) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户激活卡绑定表';

--
-- 转存表中的数据 `cm_card_user`
--

INSERT INTO `cm_card_user` (`cid`, `uid`, `add_time`, `status`) VALUES
(7, 85, 1570602848, 1),
(8, 85, 1569813411, 1);

-- --------------------------------------------------------

--
-- 表的结构 `cm_comment_weixin`
--

CREATE TABLE IF NOT EXISTS `cm_comment_weixin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL COMMENT '名称',
  `wx` varchar(30) NOT NULL COMMENT '微信号',
  `team` varchar(30) DEFAULT NULL COMMENT '所属团队',
  `status` tinyint(4) DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='客服微信表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `cm_comment_weixin`
--

INSERT INTO `cm_comment_weixin` (`id`, `name`, `wx`, `team`, `status`) VALUES
(1, 'first', 'ffffff', '一队', 1);

-- --------------------------------------------------------

--
-- 表的结构 `cm_config`
--

CREATE TABLE IF NOT EXISTS `cm_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='系统配置' AUTO_INCREMENT=44 ;

--
-- 转存表中的数据 `cm_config`
--

INSERT INTO `cm_config` (`id`, `name`, `value`) VALUES
(1, 'sitename', '千里度'),
(23, 'jsondata', '{"title":"代理后台","range":[{"pid":"54","cid":["62","64","65","79"]},{"pid":"55","cid":["75","76","77","78"]},{"pid":"56","cid":["67","68","69","70"]},{"pid":"57","cid":["72","73","74","81"]},{"pid":"80","cid":["82","66","71"]}]}'),
(5, 'siteurl', 'http://app.taoyuantoday.com/'),
(10, 'wechat', 'a:7:{s:5:"appid";s:18:"wx8536222ab28f7ba5";s:9:"appsecret";s:32:"f16da509993efa6eda52051271eec182";s:5:"token";s:8:"guangmai";s:10:"server_url";s:49:"http://long.guangzhoubaidu.com/index/wechat/index";s:7:"keycode";s:43:"SyURacDOrpmuix9RgJqccg0S801MCOz5GSHqLGbKnLy";s:5:"mchid";s:10:"1501914311";s:6:"mchkey";s:32:"guangmaikeji2018guangmaikeji2018";}'),
(12, 'tpl', 'default'),
(24, 'brand_name', '泷刺里'),
(25, 'brand_number', '000001'),
(26, 'brand_prefix', 'QLD'),
(27, 'bank', NULL),
(28, 'brand_long', '12'),
(29, 'brand_logo', '/uploads/20180927\\d84bcc78fcc5a45a20d328dd431add89.jpg'),
(30, 'team', 'a:1:{s:6:"reward";a:2:{i:6;a:3:{i:1;a:3:{s:11:"start_money";s:1:"1";s:9:"end_money";s:5:"60000";s:5:"ratio";s:1:"5";}i:2;a:3:{s:11:"start_money";s:5:"60000";s:9:"end_money";s:6:"120000";s:5:"ratio";s:1:"7";}i:3;a:3:{s:11:"start_money";s:6:"120000";s:9:"end_money";s:2:"-1";s:5:"ratio";s:1:"9";}}i:7;a:3:{i:1;a:3:{s:11:"start_money";s:1:"0";s:9:"end_money";s:5:"30000";s:5:"ratio";s:1:"5";}i:2;a:3:{s:11:"start_money";s:5:"30000";s:9:"end_money";s:5:"60000";s:5:"ratio";s:1:"7";}i:3;a:3:{s:11:"start_money";s:5:"60000";s:9:"end_money";s:2:"-1";s:5:"ratio";s:1:"9";}}}}'),
(38, 'same', 'a:4:{i:6;a:3:{s:5:"level";s:1:"6";s:3:"one";s:1:"6";s:3:"two";s:1:"4";}i:7;a:3:{s:5:"level";s:1:"7";s:3:"one";s:1:"6";s:3:"two";s:1:"4";}i:8;a:3:{s:5:"level";s:1:"8";s:3:"one";s:2:"15";s:3:"two";s:1:"0";}i:9;a:3:{s:5:"level";s:1:"0";s:3:"one";s:1:"0";s:3:"two";s:1:"0";}}'),
(36, 'upgrade', 'a:4:{i:6;a:2:{s:3:"num";s:1:"0";s:5:"level";s:1:"0";}i:7;a:2:{s:3:"num";s:6:"240000";s:5:"level";s:1:"6";}i:8;a:2:{s:3:"num";s:5:"46800";s:5:"level";s:1:"7";}i:9;a:2:{s:3:"num";s:5:"15232";s:5:"level";s:1:"8";}}'),
(37, 'recommend', 'a:4:{i:6;a:2:{s:2:"id";s:1:"0";s:5:"money";s:1:"0";}i:7;a:2:{s:2:"id";s:1:"0";s:5:"money";s:1:"0";}i:8;a:2:{s:2:"id";s:1:"8";s:5:"money";s:2:"15";}i:9;a:2:{s:2:"id";s:1:"9";s:5:"money";s:2:"15";}}'),
(41, 'copyright', '版权所有©2019 千里度 All Rights Reserved. '),
(40, 'tel_phone', '400-123-1231'),
(42, 'copyright_num', '粤ICP备17133572号'),
(43, 'copyright_technology', '由yikucompany提供技术支持');

-- --------------------------------------------------------

--
-- 表的结构 `cm_goods`
--

CREATE TABLE IF NOT EXISTS `cm_goods` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `title` char(100) DEFAULT NULL COMMENT '标题',
  `title_list` varchar(255) DEFAULT NULL COMMENT '副标题',
  `agent` int(11) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `goods_tag` varchar(200) DEFAULT NULL COMMENT '线路标签',
  `rid` int(11) DEFAULT '0' COMMENT '推荐标签',
  `content` text COMMENT '路线详情',
  `tips` text COMMENT '提示  出发前准备',
  `show_banner` tinyint(4) DEFAULT '0' COMMENT '是否banner展示',
  `start_time` int(11) DEFAULT NULL COMMENT '旅途开始时间',
  `stop_time` int(11) DEFAULT NULL COMMENT '旅途结束时间',
  `total_time` varchar(50) NOT NULL COMMENT '备注  旅途时间',
  `show_time` int(11) DEFAULT NULL COMMENT '活动展示时间',
  `hide_time` int(11) DEFAULT NULL COMMENT '活动关闭时间',
  `add_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `timestamp` datetime DEFAULT NULL,
  `rank_num` int(11) DEFAULT '0' COMMENT '排序',
  `collect_num` int(11) DEFAULT '0' COMMENT '收藏次数',
  `status` tinyint(1) DEFAULT NULL COMMENT '是否启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `cm_goods`
--

INSERT INTO `cm_goods` (`id`, `title`, `title_list`, `agent`, `photo`, `goods_tag`, `rid`, `content`, `tips`, `show_banner`, `start_time`, `stop_time`, `total_time`, `show_time`, `hide_time`, `add_time`, `update_time`, `timestamp`, `rank_num`, `collect_num`, `status`) VALUES
(0000000001, '泰山2', '登顶2', 1, '/uploads/20191008\\1136a13f91e1c2499d52b5b2bf59f323.jpg', '3', 3, '<p>旅游大巴<img src="/uploads//20180906/5a6ea1025d5c2bd8102498e424e44a88.jpg" alt="5a6ea1025d5c2bd8102498e424e44a88.jpg"/></p><p>2312313123</p>', '天气寒冷；注意防寒', 1, 1558474304, 1578474304, '6天6夜', 1558464304, 1578464304, 0, 1570517884, NULL, 2, 0, 1),
(0000000002, '黄山', '下棋', 0, '/uploads/20180927\\286131d54b259796358953c602dd22c0.jpg', NULL, 1, '飞机', '天气炎热，注意防晒', 1, 0, 0, '5天4夜', 1558474304, 1578474304, 0, 0, NULL, 0, 0, 0),
(0000000004, '庐山', '云雾', 0, '/uploads/20180910\\0ca66a23643bc82030b386c135b03c40.jpg', NULL, 2, '火车', '高山带上爬山装备', 0, 0, 0, '3天两夜', 1558474304, 1578474304, 0, 0, NULL, 0, 0, 0),
(0000000005, '珠海', '水族馆', 1, '/uploads/20190926\\7402d39586c3ff7172b7122ec5e4647e.jpg', '3', 1, '<p>火车</p>', '高原', 0, 0, 0, '1星期', 1558474304, 1578474304, 0, 1570518024, NULL, 3, 0, 1),
(0000000006, '西藏神山', '西藏神山圣湖一日游', 0, '/uploads/20190926\\7402d39586c3ff7172b7122ec5e4647e.jpg', NULL, 0, '<p>天天天天团</p>', '高原缺氧', 1, 0, 0, '2星期', 1558474304, 1578474304, 0, 1570518294, NULL, 0, 0, 1),
(0000000007, '西藏神山圣湖', '西藏神山圣湖一日游', 0, '/uploads/20190926\\7402d39586c3ff7172b7122ec5e4647e.jpg', NULL, 2, '飞机', '高原缺氧', 0, 0, 0, '2星期', 1558474304, 1578474304, 0, 0, NULL, 2, 0, 1),
(0000000008, '西藏神山圣湖', '西藏神山圣湖一日游', 0, '/uploads/20190926\\7402d39586c3ff7172b7122ec5e4647e.jpg', NULL, 3, '天天天天团', '高原缺氧', 0, 0, 0, '1个月', 1558474304, 1578474304, 0, 0, NULL, 0, 0, 1),
(0000000010, 'fdsa', '1321', 1, '/uploads/20191008\\56ed07ac5aaed742675a2c5fb9baae72.jpg', NULL, 2, '<p>agffdsgfdsfdsafa</p>', NULL, 1, 1184817600, 1743469200, '6天6夜', 1376611200, 1672534800, 0, 1570522791, NULL, 2, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `cm_goods_collect`
--

CREATE TABLE IF NOT EXISTS `cm_goods_collect` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL COMMENT '商品ID',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `add_time` int(11) NOT NULL COMMENT '收藏时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户收藏记录表' AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `cm_goods_collect`
--

INSERT INTO `cm_goods_collect` (`id`, `gid`, `uid`, `add_time`) VALUES
(0000000001, 2, 85, 0),
(0000000002, 2, 90, 0),
(0000000003, 2, 91, 0),
(0000000004, 2, 92, 0),
(0000000005, 2, 93, 0);

-- --------------------------------------------------------

--
-- 表的结构 `cm_goods_comment`
--

CREATE TABLE IF NOT EXISTS `cm_goods_comment` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL COMMENT '商品ID',
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '评论父id',
  `content` text NOT NULL COMMENT '品论主要内容',
  `score` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `add_time` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户评论表' AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `cm_goods_comment`
--

INSERT INTO `cm_goods_comment` (`id`, `gid`, `uid`, `pid`, `content`, `score`, `update_time`, `add_time`, `status`) VALUES
(0000000001, 1, 85, 0, '阿凡达发大厦双方都阿凡达洒答复发送发发呆发大水', 0, 0, 0, 1),
(0000000002, 1, 85, 0, '范德萨范德萨发呆洒发大水啊', 0, 0, 0, 1),
(0000000003, 2, 90, 0, '阿凡达发大厦双方都阿凡达洒答复发送发发呆发大水', 0, 0, 0, 1),
(0000000004, 2, 85, 0, '范德萨范德萨发呆洒发大水啊', 0, 0, 0, 1),
(0000000005, 2, 85, 0, '阿凡达发大厦双方都阿凡达洒答复发送发发呆发大水', 0, 0, 0, 1),
(0000000006, 1, 85, 0, '范德萨范德萨发呆洒发大水啊', 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `cm_goods_com_photo`
--

CREATE TABLE IF NOT EXISTS `cm_goods_com_photo` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `gcid` int(11) NOT NULL COMMENT '评论Id',
  `photo` varchar(100) NOT NULL COMMENT '图片路径',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='评论图片上传' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `cm_goods_com_photo`
--

INSERT INTO `cm_goods_com_photo` (`id`, `gcid`, `photo`, `status`) VALUES
(0000000001, 1, '/uploads/20180906/81fb4f2e274b093ef3df2e579da93958.jpg', 1),
(0000000002, 1, '/uploads/20180906/81fb4f2e274b093ef3df2e579da93958.jpg', 1);

-- --------------------------------------------------------

--
-- 表的结构 `cm_goods_photo`
--

CREATE TABLE IF NOT EXISTS `cm_goods_photo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL COMMENT '商品ID',
  `photo` varchar(100) NOT NULL COMMENT '图片路径',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否展示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='商品多图列表' AUTO_INCREMENT=64 ;

--
-- 转存表中的数据 `cm_goods_photo`
--

INSERT INTO `cm_goods_photo` (`id`, `gid`, `photo`, `status`) VALUES
(59, 1, '/uploads//20190926/59e29e9cd1c9f03997247fe2f5690af8.jpg', 1),
(58, 1, '/uploads//20190930/4b8ec1d1b976b89fcbb791c8ce6e5b02.jpg', 1),
(57, 1, '/uploads//20190930/59ed474275f5033088d7b5248b1551da.jpg', 1),
(56, 1, '/uploads//20190930/62e94281cb64669bfdaa51a021ae19f4.jpg', 1),
(54, 1, '/uploads/20180906/81fb4f2e274b093ef3df2e579da93958.jpg', 1),
(55, 1, '/uploads//20180906/5a6ea1025d5c2bd8102498e424e44a88.jpg', 1),
(53, 1, '/uploads/20180906/81fb4f2e274b093ef3df2e579da93958.jpg', 1),
(63, 10, '/uploads//20180909/5f4c83389553100dea37df737963d49f.jpg', 1),
(62, 10, '/uploads//20180909/66e14b143b2bf5cce9fd82a193a3c2f5.jpg', 1);

-- --------------------------------------------------------

--
-- 表的结构 `cm_goods_recommed`
--

CREATE TABLE IF NOT EXISTS `cm_goods_recommed` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT '名称',
  `add_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='推荐标签名' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `cm_goods_recommed`
--

INSERT INTO `cm_goods_recommed` (`id`, `name`, `add_time`) VALUES
(1, '好评专线', 0),
(2, '近期热门', 0),
(3, '大众推荐', 0);

-- --------------------------------------------------------

--
-- 表的结构 `cm_goods_t`
--

CREATE TABLE IF NOT EXISTS `cm_goods_t` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL COMMENT '商品id',
  `tid` int(11) NOT NULL COMMENT '标签id',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='商品与标签关系表' AUTO_INCREMENT=25 ;

--
-- 转存表中的数据 `cm_goods_t`
--

INSERT INTO `cm_goods_t` (`id`, `gid`, `tid`, `add_time`) VALUES
(00000000001, 4, 1, 0),
(00000000002, 4, 2, 0),
(00000000003, 2, 1, 0),
(00000000004, 2, 3, 0),
(00000000023, 10, 2, 1570522791),
(00000000017, 5, 4, 1570518024),
(00000000016, 5, 3, 1570518024),
(00000000013, 1, 3, 1570517884),
(00000000012, 1, 1, 1570517884),
(00000000022, 10, 1, 1570522791),
(00000000024, 10, 4, 1570522791);

-- --------------------------------------------------------

--
-- 表的结构 `cm_goods_tag`
--

CREATE TABLE IF NOT EXISTS `cm_goods_tag` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '标签名称',
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='商品标签' AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `cm_goods_tag`
--

INSERT INTO `cm_goods_tag` (`id`, `name`, `add_time`) VALUES
(0000000001, '摄影旅拍', 0),
(0000000002, '稻城亚丁', 0),
(0000000003, '世外桃源', 0),
(0000000004, '人间仙境', 0);

-- --------------------------------------------------------

--
-- 表的结构 `cm_order`
--

CREATE TABLE IF NOT EXISTS `cm_order` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `order_num` bigint(20) NOT NULL COMMENT '订单编号',
  `uid` int(11) NOT NULL COMMENT '下单用户ID',
  `gid` int(11) NOT NULL COMMENT '商品ID',
  `aduly` int(11) NOT NULL COMMENT '成人人数',
  `baby` int(11) NOT NULL COMMENT '小孩人数',
  `phone` bigint(20) DEFAULT NULL COMMENT '联系电话',
  `addr_p` varchar(20) DEFAULT '0' COMMENT '省',
  `addr_c` varchar(20) DEFAULT '0' COMMENT '市',
  `addr_a` varchar(20) DEFAULT '0' COMMENT '区',
  `addr_list` varchar(200) NOT NULL COMMENT '详细地址',
  `plane` int(11) DEFAULT NULL COMMENT '机票',
  `car` int(11) DEFAULT NULL COMMENT '接机服务',
  `butlers` int(11) DEFAULT NULL COMMENT '管家',
  `add_time` int(11) NOT NULL COMMENT '下单时间',
  `start_time` int(11) NOT NULL COMMENT '出行时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `verify` tinyint(4) DEFAULT '0' COMMENT '审核状态',
  `status` tinyint(4) DEFAULT '0' COMMENT '订单状态',
  `show` tinyint(4) DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='订单表' AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `cm_order`
--

INSERT INTO `cm_order` (`id`, `order_num`, `uid`, `gid`, `aduly`, `baby`, `phone`, `addr_p`, `addr_c`, `addr_a`, `addr_list`, `plane`, `car`, `butlers`, `add_time`, `start_time`, `update_time`, `verify`, `status`, `show`) VALUES
(0000000001, 1909271637445655, 85, 1, 2, 2, NULL, '0', '0', '0', '白云区天河村天河镇', NULL, NULL, NULL, 1569573464, 1571713600, 1569573464, 0, 1, 1),
(0000000002, 1909271637565210, 85, 1, 2, 2, NULL, '0', '0', '0', '天河区3天河村天河镇', NULL, NULL, NULL, 1569573476, 1571813600, 1569573476, 0, 0, 1),
(0000000003, 1909271930094953, 85, 1, 2, 2, NULL, '0', '0', '0', '天河区1天河村天河镇', NULL, NULL, NULL, 1569583809, 1579613600, 1569583809, 0, 1, 1),
(0000000004, 1909271934541014, 85, 1, 2, 2, NULL, '0', '0', '0', '天河区2天河村天河镇', NULL, NULL, NULL, 1569584094, 1579813600, 1569584094, 0, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `cm_sms`
--

CREATE TABLE IF NOT EXISTS `cm_sms` (
  `phone` bigint(20) NOT NULL,
  `code` int(11) NOT NULL,
  `add_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cm_sms`
--

INSERT INTO `cm_sms` (`phone`, `code`, `add_time`) VALUES
(15118921185, 227154, 1570602839);

-- --------------------------------------------------------

--
-- 表的结构 `cm_sys_menu`
--

CREATE TABLE IF NOT EXISTS `cm_sys_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(11) DEFAULT NULL,
  `name` char(100) DEFAULT '',
  `model` char(40) DEFAULT '',
  `url` char(100) DEFAULT '',
  `icon` char(100) DEFAULT NULL,
  `extend` text,
  `sort` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

--
-- 转存表中的数据 `cm_sys_menu`
--

INSERT INTO `cm_sys_menu` (`id`, `parent`, `name`, `model`, `url`, `icon`, `extend`, `sort`) VALUES
(2, 0, '概况', 'Index', 'index', 'gakuang', NULL, 1),
(3, 0, '系统设置', 'System', '', 'sz', NULL, 7),
(4, 3, '基础设置', 'System', 'index', 'sz', 'sethome,menu,domenu', 1),
(6, 4, '基础设置', 'System', 'index', NULL, NULL, 1),
(7, 4, '用户管理', 'System', 'users', NULL, NULL, 2),
(8, 4, '角色管理', 'System', 'roles', NULL, NULL, 3),
(52, 51, '会员列表', 'User', 'index', 'fanli', NULL, 5),
(51, 0, '会员管理', 'User', '', 'fanli', NULL, 5),
(11, 3, '修改资料', 'System', 'passwd', NULL, NULL, 2),
(12, 3, '子账号管理', 'System', 'account', NULL, 'doaccount,role', 3),
(14, 0, '路线管理', 'Product', '', 'chanpin', '', 2),
(15, 0, '代理管理', 'Agents', '', 'huiyuan', NULL, 3),
(18, 14, '路线管理', 'Product', 'index', 'index', 'index,recycle,productedit', 0),
(19, 15, '身份管理', 'Agents', 'index', NULL, 'douser', 1),
(44, 43, '预约列表', 'Order', 'index', NULL, NULL, 1),
(43, 0, '预约管理', 'Order', '', 'dingdan', NULL, 4),
(50, 47, '规格配置', 'Agentcard', 'cardtype', '', 'typeedit', 7),
(41, 15, '会员卡统计', 'Agents', 'count', NULL, 'city,agent_list', 7),
(48, 47, '会员卡创建', 'Agentcard', 'add', '', NULL, 6),
(49, 47, '会员卡列表', 'Agentcard', 'index', '', 'list', 7),
(47, 0, '会员卡管理', 'Agentcard', '', 'fanli', NULL, 6),
(37, 17, '充值申请管理', 'Wages', 'recharge', NULL, NULL, 5),
(46, 3, '后台菜单设置', 'System', 'sysmenu', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- 表的结构 `cm_user`
--

CREATE TABLE IF NOT EXISTS `cm_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT '0',
  `top_parent` int(11) DEFAULT '0' COMMENT '更高级上级',
  `parent_path` text,
  `is_area` tinyint(1) DEFAULT '0' COMMENT '是否为第一个市代或省代',
  `level` int(11) DEFAULT '0',
  `nickname` varchar(255) DEFAULT NULL,
  `sex` int(2) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `openid` char(100) DEFAULT NULL,
  `wxinfo` text,
  `money` decimal(10,2) DEFAULT '0.00',
  `reward_money` decimal(10,2) DEFAULT '0.00' COMMENT '奖金',
  `phone` char(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `card_id` varchar(20) DEFAULT NULL COMMENT '身份证号',
  `status` tinyint(1) DEFAULT '0',
  `code` char(20) DEFAULT NULL,
  `charge_time` int(11) DEFAULT NULL COMMENT '会员到期时间',
  `reg_time` datetime DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `only_code` char(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=96 ;

--
-- 转存表中的数据 `cm_user`
--

INSERT INTO `cm_user` (`id`, `parent_id`, `top_parent`, `parent_path`, `is_area`, `level`, `nickname`, `sex`, `avatar`, `openid`, `wxinfo`, `money`, `reward_money`, `phone`, `password`, `card_id`, `status`, `code`, `charge_time`, `reg_time`, `login_time`, `only_code`) VALUES
(85, 0, 0, '0_99', 0, 1, '连长', NULL, 'http://thirdwx.qlogo.cn/mmopen/QqibNTticr6tVh8ltgQ5syQTVgZlvNZYNWRs2S5HtkWbV5M43GmAh41Srn3iaXcxsLofLWp9nYQqMHEGezTNjaXDky4bMQyzKCT/132', 'otKSH1D8jjZEzVNveDrlYfFo45PI', NULL, '289920.00', '0.00', '13859815201', NULL, '441324199909090909', 1, '72A963B1', 1570689248, '2018-09-30 06:05:12', '2018-09-30 06:05:12', 'BE46'),
(90, 85, 0, '0_99', 0, 1, 'Azaz', NULL, 'http://thirdwx.qlogo.cn/mmopen/5WQJs7LcPT2b9xKAWmiaCJPvLqlAdIGFCsXOanPxyX5EAvkYJUbrpb7VB3SHyVWMvxK3AezKQzU39dsexiaib0OgKXhUObtw9h1/132', 'otKSH1Ng76Sym2DCjsrZK9ezlJz4', NULL, '133120.00', '0.00', '13265985660', NULL, NULL, 1, 'C6037121', NULL, '2018-09-30 07:10:27', '2018-09-30 07:10:27', '1352'),
(91, 90, 85, '1352_1,0_99', 0, 0, 'つError', NULL, 'http://thirdwx.qlogo.cn/mmopen/ajNVdqHZLLC9QQiaus5PGCm3CD9jgwYVkblRmwEXI86FhBy0oSj0ascp9U0afiaAyun88WsbR29Ciak4AEUWGYZfA/132', 'otKSH1ImluBdFK35ABETLilw93zY', NULL, '42400.00', '2880.00', '13256448468', NULL, NULL, 1, '298E37DC', NULL, '2018-09-30 07:08:58', '2018-09-30 07:08:58', '2D0C'),
(92, 90, 85, '2D0C_2,1352_1,0_99', 0, 0, 'ょAzaz.L', NULL, 'http://thirdwx.qlogo.cn/mmopen/nXpsNZlupjvWgC7kYicjoKkecqIcSC0IKOBTOQM3icUzL9cwnZz3UXST5lVO1zD47doxB2QCln0PgY5XoHzOa6zeOQgLmzfUzT/132', 'otKSH1ElKakHWDgZrQek44XlzqlI', NULL, '42400.00', '0.00', '13562355586', NULL, NULL, 1, 'EFACDC26', NULL, '2018-09-30 07:10:16', '2018-09-30 07:10:16', '4CD6'),
(93, 85, 0, '2D0C_2,1352_1,0_99', 0, 0, 'ょAzaz.L', NULL, 'http://thirdwx.qlogo.cn/mmopen/nXpsNZlupjvWgC7kYicjoKkecqIcSC0IKOBTOQM3icUzL9cwnZz3UXST5lVO1zD47doxB2QCln0PgY5XoHzOa6zeOQgLmzfUzT/132', 'otKSH1ElKakHWDgZrQek44XlzqlI1', NULL, '42400.00', '0.00', '13562355586', NULL, NULL, 1, 'EFACDC26', NULL, '2018-09-30 07:10:16', '2018-09-30 07:10:16', '4CD6'),
(94, 85, 0, '2D0C_2,1352_1,0_99', 0, 0, 'ょAzaz.L', NULL, 'http://thirdwx.qlogo.cn/mmopen/nXpsNZlupjvWgC7kYicjoKkecqIcSC0IKOBTOQM3icUzL9cwnZz3UXST5lVO1zD47doxB2QCln0PgY5XoHzOa6zeOQgLmzfUzT/132', 'otKSH1ElKakHWDgZrQek44XlzqlI2', NULL, '42400.00', '0.00', '13562355586', NULL, NULL, 1, 'EFACDC26', NULL, '2018-09-30 07:10:16', '2018-09-30 07:10:16', '4CD6'),
(95, 85, 0, '2D0C_2,1352_1,0_99', 0, 0, 'ょAzaz.L', NULL, 'http://thirdwx.qlogo.cn/mmopen/nXpsNZlupjvWgC7kYicjoKkecqIcSC0IKOBTOQM3icUzL9cwnZz3UXST5lVO1zD47doxB2QCln0PgY5XoHzOa6zeOQgLmzfUzT/132', 'otKSH1ElKakHWDgZrQek44XlzqlI3', NULL, '42400.00', '0.00', '13562355586', NULL, NULL, 1, 'EFACDC26', NULL, '2018-09-30 07:10:16', '2018-09-30 07:10:16', '4CD6');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
