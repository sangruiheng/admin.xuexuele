/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50721
Source Host           : 127.0.0.1:3306
Source Database       : basecms

Target Server Type    : MYSQL
Target Server Version : 50721
File Encoding         : 65001

Date: 2018-08-28 13:24:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for abnormal
-- ----------------------------
DROP TABLE IF EXISTS `abnormal`;
CREATE TABLE `abnormal` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `message` varchar(800) NOT NULL COMMENT '错误信息',
  `app` varchar(10) NOT NULL COMMENT ' 应用',
  `file` varchar(800) NOT NULL COMMENT '文件',
  `line` varchar(800) NOT NULL COMMENT '行数',
  `send_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推送状态：0已推送，1未推送',
  `create_date` datetime NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `code` (`app`),
  KEY `send_status` (`send_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='异常监控表';

-- ----------------------------
-- Records of abnormal
-- ----------------------------

-- ----------------------------
-- Table structure for admins
-- ----------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) unsigned DEFAULT '1' COMMENT '授权组ID',
  `realname` varchar(15) NOT NULL,
  `username` varchar(20) NOT NULL COMMENT '账号',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '性别：1男，2女',
  `avator` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '管理员状态：1正常，2冻结，3删除',
  `remark` varchar(360) NOT NULL COMMENT '管理员备注',
  `create_date` datetime NOT NULL,
  `update_date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='后台管理员表';

-- ----------------------------
-- Records of admins
-- ----------------------------
INSERT INTO `admins` VALUES ('1', '1', '超级管理员', 'admin@huimor.com', '1', 'http://www.basecms.com/images/default.png', '$2y$10$1uOK/By14/hXTJKOsK5YjOQE0lfGED/4F7Py328LyQQJNHQzrdkQK', '1', 'super', '2018-08-28 09:44:08', '2018-08-28 11:30:32');
INSERT INTO `admins` VALUES ('2', '2', '古靖', 'gujing', '1', 'http://www.basecms.com/images/default.png', '$2y$10$RWvC6fAfSEaYb4Dpv7.NyOKmQOKpcPSynfGPbRpYx7l1Vclh77Ks2', '1', 'super', '2018-08-28 10:35:34', '2018-08-28 13:09:27');
INSERT INTO `admins` VALUES ('3', '3', '唐志超', 'tangzhichao', '1', 'http://www.basecms.com/images/default.png', '$2y$10$hX1RPc3BLtsNMmOu.F2wbO518F38hqL4ierzvnYQRCzTh.Yhd9Gsm', '1', 'super', '2018-08-28 13:08:56', '2018-08-28 13:08:56');

-- ----------------------------
-- Table structure for admin_logs
-- ----------------------------
DROP TABLE IF EXISTS `admin_logs`;
CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT '用户姓名',
  `roles` varchar(255) NOT NULL COMMENT '用户所属组',
  `country` varchar(30) NOT NULL COMMENT '国家',
  `city` varchar(30) NOT NULL COMMENT '城市',
  `ip` varchar(30) NOT NULL COMMENT 'ip地址',
  `content` varchar(255) NOT NULL COMMENT '操作内容',
  `create_date` datetime NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `create_date` (`create_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台管理员操作日志';

-- ----------------------------
-- Records of admin_logs
-- ----------------------------

-- ----------------------------
-- Table structure for admin_menus
-- ----------------------------
DROP TABLE IF EXISTS `admin_menus`;
CREATE TABLE `admin_menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menu_label` varchar(15) NOT NULL COMMENT '菜单唯一标识',
  `title` varchar(255) NOT NULL COMMENT '菜单名称',
  `url` varchar(255) NOT NULL COMMENT '菜单地址',
  `target` varchar(10) NOT NULL DEFAULT '_self' COMMENT '打开方式',
  `icon_class` char(12) NOT NULL COMMENT '菜单图标',
  `parent_id` int(11) DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示：1是，0否',
  `create_date` datetime NOT NULL COMMENT '添加时间',
  `update_date` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '最后修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `is_show` (`is_show`),
  KEY `label` (`menu_label`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_menus
-- ----------------------------
INSERT INTO `admin_menus` VALUES ('1', '4bfd0e375396a', '后台主页', '/', '_self', '&amp;#xe601;', '0', '1', '1', '2018-08-28 09:59:31', '2018-08-28 09:59:31');
INSERT INTO `admin_menus` VALUES ('2', '4bfd0e375396b', '系统管理', '#system', '_self', '&amp;#xe626;', '0', '1', '1', '2018-08-28 09:59:31', '2018-08-28 09:59:31');
INSERT INTO `admin_menus` VALUES ('3', '4bfd0e375396d', '管理员管理', '/admins', '_self', '&amp;#xe603;', '2', '1', '1', '2018-08-28 09:59:31', '2018-08-28 09:59:31');
INSERT INTO `admin_menus` VALUES ('4', '4bfd0e375396e', '角色管理', '/roles', '_self', '&amp;#xe67e;', '2', '1', '1', '2018-08-28 09:59:31', '2018-08-28 09:59:31');
INSERT INTO `admin_menus` VALUES ('5', '4bfd0e375396f', '系统日志', '/logs', '_self', '&amp;#xe602;', '2', '1', '1', '2018-08-28 09:59:31', '2018-08-28 09:59:31');
INSERT INTO `admin_menus` VALUES ('6', '4bfd0e375396j', '菜单管理', '/menus', '_self', '&amp;#xe61b;', '9', '1', '1', '2018-08-28 09:59:31', '2018-08-28 09:59:31');
INSERT INTO `admin_menus` VALUES ('7', '4bfd0e375396h', '单页管理', '/pages', '_self', '&amp;#xe645;', '2', '1', '1', '2018-08-28 09:59:31', '2018-08-28 09:59:31');
INSERT INTO `admin_menus` VALUES ('8', '4bfd0e375396i', '插件市场', '/plugs', '_self', '&amp;#xe690;', '9', '1', '1', '2018-08-28 09:59:31', '2018-08-28 09:59:31');
INSERT INTO `admin_menus` VALUES ('9', '4bfd0e375396g', '开发者工具', '#tools', '_self', '&amp;#xe6c5;', '0', '1', '1', '2018-08-28 09:59:31', '2018-08-28 09:59:31');
INSERT INTO `admin_menus` VALUES ('10', '5b191e15455db', '异常监控', '/abnormal', '_self', '&amp;#xe613;', '9', '1', '1', '2018-08-28 09:59:31', '2018-08-28 09:59:31');
INSERT INTO `admin_menus` VALUES ('11', '5b1fafeba98c3', '模块创建', '/create', '_self', '&amp;#xe62c;', '9', '1', '1', '2018-08-28 09:59:31', '2018-08-28 09:59:31');

-- ----------------------------
-- Table structure for admin_menu_actions
-- ----------------------------
DROP TABLE IF EXISTS `admin_menu_actions`;
CREATE TABLE `admin_menu_actions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '操作名称',
  `key` varchar(30) NOT NULL COMMENT '授权识别',
  `path` varchar(100) NOT NULL COMMENT '请求路径',
  `menu_id` int(11) unsigned NOT NULL COMMENT '菜单ID',
  `create_date` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`),
  KEY `key` (`key`),
  KEY `path` (`path`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='菜单下操作表';

-- ----------------------------
-- Records of admin_menu_actions
-- ----------------------------
INSERT INTO `admin_menu_actions` VALUES ('1', '添加管理员', 'add_admins', '/admins/add', '3', '2018-08-28 10:03:29');
INSERT INTO `admin_menu_actions` VALUES ('2', '编辑管理员', 'edit_admins', '/admins/edit', '3', '2018-08-28 10:03:29');
INSERT INTO `admin_menu_actions` VALUES ('3', '删除管理员', 'del_admins', '/admins/del', '3', '2018-08-28 10:03:29');
INSERT INTO `admin_menu_actions` VALUES ('4', '添加角色', 'add_roles', '/roles/add', '4', '2018-08-28 10:03:29');
INSERT INTO `admin_menu_actions` VALUES ('5', '编辑角色', 'edit_roles', '/roles/edit', '4', '2018-08-28 10:03:29');
INSERT INTO `admin_menu_actions` VALUES ('6', '删除角色', 'del_roles', '/roles/delete', '4', '2018-08-28 10:03:29');
INSERT INTO `admin_menu_actions` VALUES ('7', '授权', 'auth_roles', '/roles/auth', '4', '2018-08-28 10:03:29');
INSERT INTO `admin_menu_actions` VALUES ('8', '添加菜单', 'add_menus', '/menus/add', '6', '2018-08-28 10:03:29');
INSERT INTO `admin_menu_actions` VALUES ('9', '更新排序', 'sort_menus', '/menus/sort', '6', '2018-08-28 10:03:29');
INSERT INTO `admin_menu_actions` VALUES ('10', '操作管理', 'action_menus', '/menus/action', '6', '2018-08-28 10:03:29');
INSERT INTO `admin_menu_actions` VALUES ('11', '编辑菜单', 'edit_menus', '/menus/edit', '6', '2018-08-28 10:03:29');
INSERT INTO `admin_menu_actions` VALUES ('12', '删除菜单', 'del_menus', '/menus/delete', '6', '2018-08-28 10:03:29');
INSERT INTO `admin_menu_actions` VALUES ('13', '查看详情', 'view_pages', '/pages/view', '7', '2018-08-28 10:03:29');
INSERT INTO `admin_menu_actions` VALUES ('14', '编辑单页', 'eidt_pages', '/pages/edit', '7', '2018-08-28 10:03:29');

-- ----------------------------
-- Table structure for admin_permissions
-- ----------------------------
DROP TABLE IF EXISTS `admin_permissions`;
CREATE TABLE `admin_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) unsigned NOT NULL COMMENT '菜单ID',
  `action_ids` varchar(255) NOT NULL COMMENT '授权操作ID',
  `role_id` int(10) unsigned NOT NULL COMMENT '授权组ID',
  PRIMARY KEY (`id`),
  KEY `permissions_key_index` (`menu_id`),
  KEY `action_id` (`action_ids`),
  KEY `menu_id` (`menu_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='权限表';

-- ----------------------------
-- Records of admin_permissions
-- ----------------------------
INSERT INTO `admin_permissions` VALUES ('1', '1', '[]', '1');
INSERT INTO `admin_permissions` VALUES ('2', '3', '[\"1\",\"2\",\"3\"]', '1');
INSERT INTO `admin_permissions` VALUES ('3', '4', '[\"4\",\"5\",\"6\",\"7\"]', '1');
INSERT INTO `admin_permissions` VALUES ('4', '5', '[]', '1');
INSERT INTO `admin_permissions` VALUES ('5', '7', '[\"13\",\"14\"]', '1');
INSERT INTO `admin_permissions` VALUES ('6', '6', '[\"8\",\"9\",\"10\",\"11\",\"12\"]', '1');
INSERT INTO `admin_permissions` VALUES ('7', '8', '[]', '1');
INSERT INTO `admin_permissions` VALUES ('8', '10', '[]', '1');
INSERT INTO `admin_permissions` VALUES ('9', '11', '[]', '1');
INSERT INTO `admin_permissions` VALUES ('10', '1', '[]', '2');
INSERT INTO `admin_permissions` VALUES ('11', '3', '[\"1\",\"2\",\"3\"]', '2');
INSERT INTO `admin_permissions` VALUES ('12', '4', '[\"4\",\"5\",\"6\",\"7\"]', '2');
INSERT INTO `admin_permissions` VALUES ('13', '5', '[]', '2');
INSERT INTO `admin_permissions` VALUES ('14', '7', '[\"13\",\"14\"]', '2');
INSERT INTO `admin_permissions` VALUES ('15', '6', '[\"8\",\"9\",\"10\",\"11\",\"12\"]', '2');
INSERT INTO `admin_permissions` VALUES ('16', '8', '[]', '2');
INSERT INTO `admin_permissions` VALUES ('17', '10', '[]', '2');
INSERT INTO `admin_permissions` VALUES ('18', '11', '[]', '2');
INSERT INTO `admin_permissions` VALUES ('19', '1', '[]', '3');
INSERT INTO `admin_permissions` VALUES ('20', '3', '[\"1\",\"2\",\"3\"]', '3');
INSERT INTO `admin_permissions` VALUES ('21', '4', '[\"4\",\"5\",\"6\",\"7\"]', '3');
INSERT INTO `admin_permissions` VALUES ('22', '5', '[]', '3');
INSERT INTO `admin_permissions` VALUES ('23', '7', '[\"13\",\"14\"]', '3');
INSERT INTO `admin_permissions` VALUES ('24', '6', '[\"8\",\"9\",\"10\",\"11\",\"12\"]', '3');
INSERT INTO `admin_permissions` VALUES ('25', '8', '[]', '3');
INSERT INTO `admin_permissions` VALUES ('26', '10', '[]', '3');
INSERT INTO `admin_permissions` VALUES ('27', '11', '[]', '3');

-- ----------------------------
-- Table structure for admin_roles
-- ----------------------------
DROP TABLE IF EXISTS `admin_roles`;
CREATE TABLE `admin_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT '管理员组名称',
  `remark` varchar(360) NOT NULL COMMENT '备注',
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='管理员组';

-- ----------------------------
-- Records of admin_roles
-- ----------------------------
INSERT INTO `admin_roles` VALUES ('1', '超级管理员', 'super', '2018-08-28 09:45:19', '2018-08-28 09:45:19');
INSERT INTO `admin_roles` VALUES ('2', '管理员', '管理员', '2018-08-28 10:36:18', '2018-08-28 10:36:18');
INSERT INTO `admin_roles` VALUES ('3', '普通管理员', '普通管理员', '2018-08-28 10:53:02', '2018-08-28 10:53:02');

-- ----------------------------
-- Table structure for log_api_requests
-- ----------------------------
DROP TABLE IF EXISTS `log_api_requests`;
CREATE TABLE `log_api_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typename` varchar(100) NOT NULL COMMENT '请求名称',
  `ip` varchar(39) NOT NULL DEFAULT '0.0.0.0' COMMENT '请求IP',
  `api_url` varchar(255) NOT NULL DEFAULT '""' COMMENT '请求接口完整地址',
  `content` longtext NOT NULL COMMENT '请求内容',
  `response` longtext NOT NULL COMMENT '响应结果',
  `create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '请求时间',
  `response_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '响应时间',
  `time_used` double(10,4) NOT NULL COMMENT '接口响应时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `api_url` (`api_url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='api请求日志';

-- ----------------------------
-- Records of log_api_requests
-- ----------------------------

-- ----------------------------
-- Table structure for pages
-- ----------------------------
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) NOT NULL,
  `topic` varchar(255) DEFAULT NULL COMMENT '缩略图',
  `content` text NOT NULL,
  `video` varchar(255) DEFAULT NULL COMMENT '视频地址',
  `create_date` datetime NOT NULL COMMENT '添加时间',
  `update_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `create_date` (`create_date`),
  KEY `video` (`video`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='单页管理';

-- ----------------------------
-- Records of pages
-- ----------------------------
INSERT INTO `pages` VALUES ('1', '公司介绍', null, '请输入内容...', null, '2018-08-28 09:42:50', '2018-08-28 09:42:50');
INSERT INTO `pages` VALUES ('2', '联系我们', null, '请输入内容...', null, '2018-08-28 09:42:50', '2018-08-28 09:42:50');
INSERT INTO `pages` VALUES ('3', '免责条款', null, '请输入内容...', null, '2018-08-28 09:42:50', '2018-08-28 09:42:50');
INSERT INTO `pages` VALUES ('4', '法律声明', null, '请输入内容...', null, '2018-08-28 09:42:50', '2018-08-28 09:42:50');
INSERT INTO `pages` VALUES ('5', '隐私保护', null, '请输入内容...', null, '2018-08-28 09:42:50', '2018-08-28 09:42:50');
INSERT INTO `pages` VALUES ('6', '服务流程', null, '请输入内容...', '123.mp4', '2018-08-28 09:42:50', '2018-08-28 09:42:50');

-- ----------------------------
-- Table structure for plug_installs
-- ----------------------------
DROP TABLE IF EXISTS `plug_installs`;
CREATE TABLE `plug_installs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL COMMENT '发布者ID',
  `name` varchar(30) NOT NULL COMMENT '插件名称',
  `menu_label` varchar(15) NOT NULL COMMENT '菜单唯一标识',
  `plug_key` varchar(100) NOT NULL COMMENT '插件索引（用户识别是否已经安装）',
  `icon` varchar(255) NOT NULL COMMENT '插件图片地址',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '插件类型',
  `framework` varchar(255) NOT NULL COMMENT '适用框架',
  `author` varchar(30) NOT NULL COMMENT '插件作者',
  `version` varchar(30) NOT NULL COMMENT '版本号',
  `plug_url` varchar(255) NOT NULL COMMENT '插件发布版本地址',
  `description` varchar(255) NOT NULL COMMENT '插件描述',
  `details` text NOT NULL COMMENT '插件详情',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发布状态：0待审核，1已发布，2审核失败',
  `create_date` datetime NOT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='已安装插件列表';

-- ----------------------------
-- Records of plug_installs
-- ----------------------------
INSERT INTO `plug_installs` VALUES ('1', '1', '用户管理', '4bfd0e375396s', 'user', '', '1', '', '超级管理员', '1.0.0', '/2018/05/201805070749315af0050bcde6b.zip', '此模块为用户管理模块，包含用户列表，用户基本信息显示等信息，可根据不同项目在此基本上进行扩展', '<p>\r\n	此模块为用户管理模块，包含用户列表，用户基本信息显示等信息，可根据不同项目在此基本上进行扩展，此用户管理包含3张表，分别为：\r\n</p>\r\n<p>\r\n	1、users（用户基础表）\r\n</p>\r\n<p>\r\n	2、user_details （用户详情表）\r\n</p>\r\n<p>\r\n	3、user_login_logs（用户登录日志表）\r\n</p>\r\n<p>\r\n	4、user_third（第三方登录表）\r\n</p>\r\n<p>\r\n	安装完成后，需要手动配置路由：\r\n</p>\r\n<p>\r\n<pre class=\"prettyprint lang-php\">    //用户管理\r\n    Route::group([\'prefix\'=&gt;\'users\',\'namespace\' =&gt;\'User\\Controllers\'],function(){\r\n        Route::get(\'/\',\'UserViewController@index\')-&gt;name(\"index\");\r\n        Route::post(\'add\',\'UserController@create\')-&gt;name(\"add\");\r\n        Route::get(\'lists\',\'UserController@lists\')-&gt;name(\"lists\");\r\n    });</pre>\r\n</p>\r\n<p>\r\n</p>', '1', '2018-05-07 07:42:45');
INSERT INTO `plug_installs` VALUES ('2', '1', '模块创建', '5b1fafeba98c3', 'create', '', '1', '', '唐志超', '1.0.0', 'plugs/2018/06/201806131757175b20ea7da8af8.zip', '模块创建操作，主要用户后台自动创建Controller、model、view、js、css文件，省去单个文件创建的繁琐。', '<p>\r\n	1、安装说明\r\n</p>\r\n<p>\r\n	通过插件市场进行安装\r\n</p>\r\n<p>\r\n	2、配置路由信息\r\n</p>\r\n<p>\r\n<pre class=\"prettyprint lang-php\">    //模块创建\r\n    Route::group([\'prefix\'=&gt;\'create\',\'namespace\'=&gt;\'Create\\Controllers\'],function(){\r\n        Route::get(\'/\',\'CreateController@index\')-&gt;name(\"index\");\r\n        Route::post(\'/\',\'CreateController@create\')-&gt;name(\"index\");\r\n    });</pre>\r\n3、配置文件系统（/config/filesystems.php），修改配置信息如下\r\n</p>\r\n<p>\r\n<pre class=\"prettyprint lang-php\">\'disks\' =&gt; [\r\n\r\n        \'local\' =&gt; [\r\n            \'driver\' =&gt; \'local\',\r\n            \'root\' =&gt; public_path(\'/\'),\r\n        ],\r\n\r\n        \'public\' =&gt; [\r\n            \'driver\' =&gt; \'local\',\r\n            \'root\' =&gt; storage_path(\'app/public\'),\r\n            \'url\' =&gt; env(\'APP_URL\').\'/storage\',\r\n            \'visibility\' =&gt; \'public\',\r\n        ],\r\n\r\n        \'s3\' =&gt; [\r\n            \'driver\' =&gt; \'s3\',\r\n            \'key\' =&gt; env(\'AWS_ACCESS_KEY_ID\'),\r\n            \'secret\' =&gt; env(\'AWS_SECRET_ACCESS_KEY\'),\r\n            \'region\' =&gt; env(\'AWS_DEFAULT_REGION\'),\r\n            \'bucket\' =&gt; env(\'AWS_BUCKET\'),\r\n        ],\r\n        \'app\' =&gt; [ //加入此行配置\r\n            \'driver\' =&gt; \'local\',\r\n            \'root\'   =&gt; app_path(\'/\'),\r\n        ]\r\n\r\n    ],</pre>\r\n效果演示：\r\n</p>\r\n<p>\r\n	<img src=\"/uploads/2018/06/201806131803165b20ebe45932d.png\" alt=\"\" />\r\n</p>', '1', '2018-06-13 18:03:26');

-- ----------------------------
-- Table structure for settings
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL COMMENT '配置选项',
  `title` varchar(255) NOT NULL COMMENT '设置项目',
  `key` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL COMMENT '设置值',
  `is_public` tinyint(1) NOT NULL COMMENT '是否是公共配置，公共配置只有管理员可以配置',
  `sort` int(11) NOT NULL COMMENT '排序',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示：1是，0否',
  `update_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `sort` (`sort`),
  KEY `state` (`state`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='系统设置';

-- ----------------------------
-- Records of settings
-- ----------------------------
INSERT INTO `settings` VALUES ('1', 'text', '站点标题', 'web_title', '易创互联', '1', '1', '1', '2018-08-28 09:38:22');
INSERT INTO `settings` VALUES ('2', 'text', '后台标题', 'admin_title', '管理后台', '1', '1', '1', '2018-08-28 09:38:22');
INSERT INTO `settings` VALUES ('3', 'text', '网站关键词', 'web_keyword', '易创互联', '1', '1', '1', '2018-08-28 09:38:22');
INSERT INTO `settings` VALUES ('4', 'textarea', '站点描述', 'web_description', '站点描述', '1', '1', '1', '2018-08-28 09:38:22');
INSERT INTO `settings` VALUES ('5', 'image', '网站LOGO', 'web_logo', '123.png', '1', '1', '1', '2018-08-28 09:38:22');
INSERT INTO `settings` VALUES ('6', 'text', '网站域名', 'web_url', 'http://www.huimor.com', '1', '1', '1', '2018-08-28 09:38:22');
INSERT INTO `settings` VALUES ('7', 'textarea', '统计代码', 'web_statistics', '统计代码', '1', '1', '1', '2018-08-28 09:38:22');
INSERT INTO `settings` VALUES ('8', 'text', '版权信息', 'web_copyright', '京ICP备14013819号', '1', '1', '1', '2018-08-28 09:38:22');
INSERT INTO `settings` VALUES ('9', 'image', '官方微信平台', 'web_qrcode', '123.png', '1', '1', '1', '2018-08-28 09:38:22');
