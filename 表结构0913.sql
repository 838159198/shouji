-- phpMyAdmin SQL Dump
-- version 4.7.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2017-09-14 09:17:27
-- 服务器版本： 5.5.56-log
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `baichuan`
--

-- --------------------------------------------------------

--
-- 表的结构 `app_advisory_records`
--

CREATE TABLE `app_advisory_records` (
  `id` int(11) UNSIGNED NOT NULL,
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mid` int(11) UNSIGNED NOT NULL COMMENT '客服ID',
  `content` text COMMENT '内容',
  `jointime` int(10) DEFAULT NULL COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户咨询记录表';

-- --------------------------------------------------------

--
-- 表的结构 `app_agent_price`
--

CREATE TABLE `app_agent_price` (
  `id` int(11) NOT NULL,
  `pid` int(11) NOT NULL COMMENT '产品',
  `price` double(7,2) NOT NULL COMMENT '价格',
  `agent` tinyint(4) NOT NULL COMMENT '分组'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_area`
--

CREATE TABLE `app_area` (
  `id` bigint(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `ename` varchar(255) NOT NULL,
  `up` int(11) NOT NULL DEFAULT '1',
  `secq` int(11) NOT NULL DEFAULT '1',
  `youbian` varchar(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='行政区划';

-- --------------------------------------------------------

--
-- 表的结构 `app_article`
--

CREATE TABLE `app_article` (
  `id` int(11) NOT NULL,
  `title` varchar(60) NOT NULL COMMENT '标题',
  `cid` int(4) NOT NULL COMMENT '栏目id',
  `tags` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `createtime` int(10) NOT NULL,
  `lasttime` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态1正常0隐藏',
  `uid` tinyint(3) NOT NULL COMMENT '用户id',
  `hits` int(10) NOT NULL DEFAULT '0',
  `keywords` varchar(255) NOT NULL,
  `descriptions` text NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_article_category`
--

CREATE TABLE `app_article_category` (
  `id` int(10) NOT NULL,
  `name` varchar(10) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `descriptions` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态1正常0隐藏',
  `seotitle` varchar(100) NOT NULL,
  `createtime` int(10) NOT NULL,
  `lasttime` int(10) NOT NULL,
  `pathname` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_article_tags`
--

CREATE TABLE `app_article_tags` (
  `id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL,
  `seotitle` varchar(60) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `descriptions` varchar(255) NOT NULL,
  `createtime` int(10) NOT NULL,
  `hits` int(10) NOT NULL DEFAULT '0',
  `frequency` int(6) NOT NULL DEFAULT '0' COMMENT '频率'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_article_tagslist`
--

CREATE TABLE `app_article_tagslist` (
  `id` int(10) NOT NULL,
  `tid` int(10) NOT NULL,
  `name` varchar(60) NOT NULL,
  `createtime` int(10) NOT NULL,
  `aid` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_ask_task`
--

CREATE TABLE `app_ask_task` (
  `id` int(11) NOT NULL,
  `a_id` int(11) NOT NULL COMMENT '被申请的管理员id for ',
  `m_id` int(11) NOT NULL COMMENT '被申请的注册会员的id member',
  `a_time` int(11) NOT NULL COMMENT '任务申请时间	ask_for_time',
  `allow_time` int(11) DEFAULT NULL COMMENT '任务批准时间',
  `is_allow` int(11) NOT NULL DEFAULT '2' COMMENT '请求状态 0=>未准许,1=》准许,2=>等待查看中',
  `t_status` int(11) DEFAULT '1' COMMENT '任务状态,1=》可申请,2=》已被申请,3=>任务上报,4=>任务已完成,5=>任务删除',
  `f_id` int(11) NOT NULL COMMENT '申请任务的客服的id',
  `t_id` int(11) NOT NULL COMMENT 'app_task 表的id',
  `tw_id` int(11) DEFAULT NULL COMMENT 'task_when表的主键',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '1=>新用户任务，2=>降量任务，3=>周任务,4=>其他，',
  `content` varchar(100) DEFAULT '0' COMMENT '准许或拒绝任务申请时的回复',
  `availability` int(10) DEFAULT NULL COMMENT '有效回访：1=>有效,2=>无效,其它为申请'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_bind_sample`
--

CREATE TABLE `app_bind_sample` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(10) UNSIGNED DEFAULT NULL,
  `type` char(10) NOT NULL COMMENT '类型',
  `val` char(32) NOT NULL COMMENT '值',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，0已分配，1未分配',
  `allot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0自动分配，1手动分配',
  `closed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0可用，1已封号',
  `utype` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '独立0分组1',
  `klradio` float UNSIGNED NOT NULL DEFAULT '0' COMMENT '扣量基数'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_campaign`
--

CREATE TABLE `app_campaign` (
  `id` int(11) UNSIGNED NOT NULL,
  `pid` int(11) UNSIGNED NOT NULL COMMENT '产品ID',
  `title` char(25) DEFAULT NULL COMMENT '活动标题',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=>有效0=>无效',
  `instruction` varchar(500) DEFAULT NULL COMMENT '说明',
  `temp` varchar(255) DEFAULT NULL,
  `starttime` datetime DEFAULT NULL COMMENT '活动开始时间',
  `endtime` datetime DEFAULT NULL COMMENT '活动截止时间',
  `userstarttime` datetime DEFAULT NULL COMMENT '用户报名开始时间',
  `userendtime` datetime DEFAULT NULL COMMENT '用户报名截止时间',
  `createtime` datetime DEFAULT NULL COMMENT '添加时间',
  `periods` varchar(50) DEFAULT NULL COMMENT '期数',
  `num` int(4) DEFAULT NULL COMMENT '人数上限',
  `publishtime` int(15) DEFAULT NULL COMMENT '发布时间',
  `publish_status` int(2) DEFAULT '0' COMMENT '0未1已发布'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=REDUNDANT;

-- --------------------------------------------------------

--
-- 表的结构 `app_campaign_income`
--

CREATE TABLE `app_campaign_income` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `cid` int(11) UNSIGNED NOT NULL COMMENT '活动ID',
  `type` varchar(20) NOT NULL COMMENT '业务类型',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `olddata` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '原单价收益',
  `campaigndata` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '活动赠送',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户活动收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_campaign_log`
--

CREATE TABLE `app_campaign_log` (
  `id` int(11) UNSIGNED NOT NULL,
  `cid` int(11) UNSIGNED NOT NULL COMMENT '活动ID',
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `pid` int(11) UNSIGNED NOT NULL COMMENT '产品ID',
  `title` char(25) DEFAULT NULL COMMENT '活动标题',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=>未审核,1=>审核通过,2=>审核拒绝',
  `bak` varchar(500) DEFAULT NULL COMMENT '拒绝理由',
  `createtime` datetime DEFAULT NULL COMMENT '审核时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=REDUNDANT;

-- --------------------------------------------------------

--
-- 表的结构 `app_campaign_sort`
--

CREATE TABLE `app_campaign_sort` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL COMMENT '用户id',
  `periods` int(11) NOT NULL COMMENT '活动期数',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '活动排名',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '激活量',
  `del` int(11) NOT NULL DEFAULT '1' COMMENT '0-删除 1-可用',
  `type` varchar(255) NOT NULL COMMENT '产品类型',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `truth` int(11) DEFAULT '1' COMMENT '真假 1-真 0-假'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_catalogue`
--

CREATE TABLE `app_catalogue` (
  `id` int(11) NOT NULL COMMENT '主键id',
  `name` char(100) NOT NULL COMMENT '目录内容',
  `content` varchar(255) DEFAULT NULL COMMENT '目录内容（此目录分类的具体内容）',
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '上级目录的主键id',
  `rank` int(1) DEFAULT NULL COMMENT '是树状的第几层',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '此状态是否可用。0=》可用，1=》删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_check_phone_log`
--

CREATE TABLE `app_check_phone_log` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `createtime` datetime NOT NULL,
  `model` varchar(100) CHARACTER SET utf8 NOT NULL,
  `brand` varchar(100) CHARACTER SET utf8 NOT NULL,
  `status` varchar(255) CHARACTER SET utf8 NOT NULL,
  `duration` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `app_client_data`
--

CREATE TABLE `app_client_data` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `app_id` int(6) NOT NULL COMMENT '业务id',
  `imei` varchar(20) NOT NULL,
  `iccid` varchar(20) DEFAULT NULL,
  `system_version_code` varchar(100) NOT NULL COMMENT '系统',
  `md5` varchar(32) NOT NULL,
  `sim_operator_name` varchar(100) DEFAULT NULL COMMENT '运营商',
  `mac` varchar(100) NOT NULL,
  `mobi_ip` varchar(15) DEFAULT NULL,
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `pc_ip` varchar(15) NOT NULL,
  `models` varchar(20) DEFAULT NULL COMMENT '手机型号',
  `status` tinyint(1) UNSIGNED NOT NULL COMMENT '0非1第一次',
  `from` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_client_data_seller`
--

CREATE TABLE `app_client_data_seller` (
  `id` int(10) UNSIGNED NOT NULL,
  `userId` varchar(30) NOT NULL COMMENT '用户',
  `app_id` int(6) NOT NULL COMMENT '业务id',
  `imei` varchar(30) NOT NULL,
  `iccid` varchar(30) DEFAULT NULL,
  `systemVersionCode` varchar(20) DEFAULT NULL,
  `simOperatorName` varchar(20) DEFAULT NULL,
  `mac` varchar(30) DEFAULT NULL,
  `phoneIp` varchar(15) DEFAULT NULL,
  `models` varchar(20) DEFAULT NULL COMMENT '手机型号',
  `brand` varchar(15) DEFAULT NULL,
  `cpu` varchar(15) DEFAULT NULL,
  `resolution_w` varchar(20) DEFAULT NULL,
  `resolution_h` varchar(20) DEFAULT NULL,
  `pcIp` varchar(20) DEFAULT NULL,
  `timestamp` int(11) NOT NULL COMMENT '创建时间',
  `installtime` int(11) NOT NULL COMMENT '安装时间',
  `installcount` int(11) DEFAULT NULL COMMENT '安装次数',
  `status` tinyint(1) UNSIGNED NOT NULL COMMENT '0非1第一次'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_client_login_log`
--

CREATE TABLE `app_client_login_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(30) NOT NULL,
  `pcmac` varchar(20) NOT NULL,
  `pcsys` varchar(30) NOT NULL COMMENT '系统',
  `pcip` varchar(15) NOT NULL,
  `createtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_client_mdapp_data`
--

CREATE TABLE `app_client_mdapp_data` (
  `id` int(10) UNSIGNED NOT NULL,
  `tj_id` int(6) DEFAULT NULL COMMENT '业务id',
  `imei` varchar(30) DEFAULT NULL,
  `iccid` varchar(30) DEFAULT NULL COMMENT 'sim卡',
  `systemVersionCode` varchar(20) DEFAULT NULL COMMENT '系统版本',
  `simOperatorName` varchar(20) DEFAULT NULL COMMENT '运营商',
  `mac` varchar(30) DEFAULT NULL COMMENT 'mac地址',
  `models` varchar(20) DEFAULT NULL COMMENT '手机型号',
  `brand` varchar(15) DEFAULT NULL COMMENT '手机品牌',
  `ip` varchar(20) DEFAULT NULL,
  `md5` tinyint(2) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL COMMENT '创建时间',
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `pack_id` int(11) DEFAULT NULL COMMENT '套餐id',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(1) UNSIGNED NOT NULL COMMENT '0失败  1成功'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_error_log`
--

CREATE TABLE `app_error_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(10) UNSIGNED NOT NULL,
  `ip` varchar(15) NOT NULL,
  `system` varchar(30) NOT NULL,
  `fbl` varchar(15) NOT NULL,
  `content` varchar(800) NOT NULL,
  `createtime` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_error_log_rom`
--

CREATE TABLE `app_error_log_rom` (
  `id` int(10) NOT NULL,
  `uid` int(10) UNSIGNED NOT NULL,
  `appNo` varchar(15) NOT NULL,
  `imei` varchar(50) NOT NULL,
  `simcode` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `tjcode` varchar(15) NOT NULL,
  `crashstr` text NOT NULL,
  `createtime` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_helper_package`
--

CREATE TABLE `app_helper_package` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '组合名称',
  `apk_id` varchar(255) NOT NULL,
  `star` int(11) NOT NULL COMMENT '评分',
  `size` varchar(255) NOT NULL COMMENT '包文件大小',
  `num` int(11) NOT NULL COMMENT '软件数量',
  `createtime` varchar(10) NOT NULL,
  `updatetime` varchar(10) NOT NULL,
  `create_id` int(11) NOT NULL DEFAULT '0' COMMENT '创建人id',
  `status` int(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态0关闭1显示'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='助手套餐组合';

-- --------------------------------------------------------

--
-- 表的结构 `app_helper_version`
--

CREATE TABLE `app_helper_version` (
  `hv_id` int(10) UNSIGNED NOT NULL,
  `hv_version` varchar(255) DEFAULT NULL,
  `hv_content` text,
  `hv_download_url` varchar(255) DEFAULT NULL,
  `hv_constraint` int(1) UNSIGNED DEFAULT '0' COMMENT '强制更新1是0否',
  `hv_title` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_import_agent_log`
--

CREATE TABLE `app_import_agent_log` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'id',
  `uid` varchar(15) NOT NULL COMMENT '父级id',
  `month` varchar(15) NOT NULL COMMENT '数据月份',
  `data` varchar(15) NOT NULL COMMENT '推广收益',
  `date` date NOT NULL COMMENT '创修时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_import_log`
--

CREATE TABLE `app_import_log` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'id',
  `gid` varchar(15) NOT NULL COMMENT '分组id',
  `type` varchar(15) NOT NULL COMMENT '业务类型',
  `data` varchar(15) NOT NULL COMMENT '激活量',
  `date` date NOT NULL COMMENT '创修时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_income`
--

CREATE TABLE `app_income` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_2345sjzs`
--

CREATE TABLE `app_income_2345sjzs` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_2345tqw`
--

CREATE TABLE `app_income_2345tqw` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_2345wpllq`
--

CREATE TABLE `app_income_2345wpllq` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_2345ydw`
--

CREATE TABLE `app_income_2345ydw` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_2345ysdq`
--

CREATE TABLE `app_income_2345ysdq` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_all`
--

CREATE TABLE `app_income_all` (
  `id` int(11) NOT NULL,
  `createtime` date NOT NULL COMMENT '创建时间',
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_aqws360`
--

CREATE TABLE `app_income_aqws360` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_aqy`
--

CREATE TABLE `app_income_aqy` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_aqzm`
--

CREATE TABLE `app_income_aqzm` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_ayd`
--

CREATE TABLE `app_income_ayd` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_azllq`
--

CREATE TABLE `app_income_azllq` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_baidu`
--

CREATE TABLE `app_income_baidu` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_bddt`
--

CREATE TABLE `app_income_bddt` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_bdllq`
--

CREATE TABLE `app_income_bdllq` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_bdsjzs`
--

CREATE TABLE `app_income_bdsjzs` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_bfyy`
--

CREATE TABLE `app_income_bfyy` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_cbdh`
--

CREATE TABLE `app_income_cbdh` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_clear_log`
--

CREATE TABLE `app_income_clear_log` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` int(11) UNSIGNED NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号',
  `type` char(5) NOT NULL COMMENT '业务类型',
  `cleartime` datetime NOT NULL COMMENT '清除时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益清理历史记录';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_dzdp`
--

CREATE TABLE `app_income_dzdp` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_final`
--

CREATE TABLE `app_income_final` (
  `id` int(11) NOT NULL,
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `install_income` decimal(7,2) DEFAULT NULL COMMENT '安装收益',
  `arrive_income` decimal(7,2) DEFAULT NULL COMMENT '到达收益',
  `activate_income` decimal(7,2) DEFAULT NULL COMMENT '激活收益',
  `income_type` int(11) DEFAULT NULL COMMENT '总收益类型',
  `all_income` decimal(7,2) DEFAULT NULL COMMENT '总收益',
  `date` date NOT NULL COMMENT '数据日期',
  `status` int(11) NOT NULL COMMENT '可用状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_income_gddt`
--

CREATE TABLE `app_income_gddt` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_jd`
--

CREATE TABLE `app_income_jd` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_jiuyou`
--

CREATE TABLE `app_income_jiuyou` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_jjddz`
--

CREATE TABLE `app_income_jjddz` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_jrtt`
--

CREATE TABLE `app_income_jrtt` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_jyyxzx`
--

CREATE TABLE `app_income_jyyxzx` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_kwyy`
--

CREATE TABLE `app_income_kwyy` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_kxxxl`
--

CREATE TABLE `app_income_kxxxl` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_lbqlds`
--

CREATE TABLE `app_income_lbqlds` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_llq`
--

CREATE TABLE `app_income_llq` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_llq360`
--

CREATE TABLE `app_income_llq360` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_lssp`
--

CREATE TABLE `app_income_lssp` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_meipai`
--

CREATE TABLE `app_income_meipai` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_meituan`
--

CREATE TABLE `app_income_meituan` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_pps`
--

CREATE TABLE `app_income_pps` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_ppzs`
--

CREATE TABLE `app_income_ppzs` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_qqllq`
--

CREATE TABLE `app_income_qqllq` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_qsbk`
--

CREATE TABLE `app_income_qsbk` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_qyyd`
--

CREATE TABLE `app_income_qyyd` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_sdxw`
--

CREATE TABLE `app_income_sdxw` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_sgsc`
--

CREATE TABLE `app_income_sgsc` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_shsp`
--

CREATE TABLE `app_income_shsp` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_sjzs360`
--

CREATE TABLE `app_income_sjzs360` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_szdh`
--

CREATE TABLE `app_income_szdh` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_taobao`
--

CREATE TABLE `app_income_taobao` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_tq`
--

CREATE TABLE `app_income_tq` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_txsjgj`
--

CREATE TABLE `app_income_txsjgj` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_txsp`
--

CREATE TABLE `app_income_txsp` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_txxw`
--

CREATE TABLE `app_income_txxw` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_ucllq`
--

CREATE TABLE `app_income_ucllq` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_wdj`
--

CREATE TABLE `app_income_wdj` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_weixin`
--

CREATE TABLE `app_income_weixin` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_wnl`
--

CREATE TABLE `app_income_wnl` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_wyyyy`
--

CREATE TABLE `app_income_wyyyy` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_wzdq`
--

CREATE TABLE `app_income_wzdq` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_xfsrf`
--

CREATE TABLE `app_income_xfsrf` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_ysdq360`
--

CREATE TABLE `app_income_ysdq360` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_yyb`
--

CREATE TABLE `app_income_yyb` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_yysd`
--

CREATE TABLE `app_income_yysd` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_yyzx`
--

CREATE TABLE `app_income_yyzx` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_zhwnl`
--

CREATE TABLE `app_income_zhwnl` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_zkns`
--

CREATE TABLE `app_income_zkns` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_zmtq`
--

CREATE TABLE `app_income_zmtq` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_zssq`
--

CREATE TABLE `app_income_zssq` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_zysc`
--

CREATE TABLE `app_income_zysc` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_income_zysck`
--

CREATE TABLE `app_income_zysck` (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
  `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
  `createtime` date NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';

-- --------------------------------------------------------

--
-- 表的结构 `app_invitationcode`
--

CREATE TABLE `app_invitationcode` (
  `id` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `code` varchar(255) CHARACTER SET latin1 NOT NULL COMMENT '邀请码',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `agent` smallint(6) NOT NULL COMMENT '分组'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_link`
--

CREATE TABLE `app_link` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL COMMENT '网站名称',
  `website` varchar(200) NOT NULL COMMENT '网址',
  `createtime` int(10) NOT NULL,
  `lasttime` int(10) NOT NULL,
  `uid` int(5) NOT NULL,
  `remarks` varchar(255) NOT NULL COMMENT '备注',
  `qq` varchar(12) NOT NULL,
  `num` int(5) NOT NULL DEFAULT '100' COMMENT '排序从小到大，默认100，最大5位数',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态1显示0隐藏',
  `cid` int(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_link_category`
--

CREATE TABLE `app_link_category` (
  `id` int(10) NOT NULL,
  `name` varchar(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态1正常0隐藏'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_mail`
--

CREATE TABLE `app_mail` (
  `id` int(11) UNSIGNED NOT NULL,
  `send` int(11) NOT NULL DEFAULT '0' COMMENT '发件人(admin)',
  `recipient` int(11) NOT NULL DEFAULT '0' COMMENT '收件人(member_info)',
  `content` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '内容',
  `jointime` datetime NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态0未读，1已读，2客户删除，3管理员删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='站内信';

-- --------------------------------------------------------

--
-- 表的结构 `app_mail_content`
--

CREATE TABLE `app_mail_content` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='站内信内容';

-- --------------------------------------------------------

--
-- 表的结构 `app_manage`
--

CREATE TABLE `app_manage` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(32) NOT NULL COMMENT '帐号',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `joinip` varchar(15) NOT NULL COMMENT '注册IP',
  `overip` varchar(15) NOT NULL COMMENT '登陆IP',
  `jointime` int(10) NOT NULL COMMENT '注册时间',
  `overtime` int(10) NOT NULL COMMENT '登陆时间',
  `group` int(10) UNSIGNED NOT NULL DEFAULT '2',
  `auth` text NOT NULL COMMENT '权限',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `role` int(11) UNSIGNED NOT NULL COMMENT '角色',
  `name` varchar(32) NOT NULL COMMENT '姓名',
  `sex` int(1) DEFAULT '0' COMMENT '0=>男，1=>女',
  `phone` int(11) DEFAULT NULL COMMENT '联系电话',
  `birthday` int(11) DEFAULT NULL COMMENT '出生日期',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注信息',
  `idcard` char(20) DEFAULT NULL COMMENT '有效证件号码',
  `department` int(11) DEFAULT NULL COMMENT '部门id',
  `qq` varchar(15) DEFAULT NULL COMMENT '工作QQ，便于收发邮件',
  `mail` varchar(255) DEFAULT NULL,
  `promotion` int(1) UNSIGNED DEFAULT '0' COMMENT '0=》不可晋升，1=》可晋升，2=》等待晋升，3=》已晋升',
  `mark` varchar(255) DEFAULT NULL COMMENT '客服追踪用户状态的id—list，最多只能同时追踪20个用户',
  `pro_time` int(11) UNSIGNED DEFAULT NULL COMMENT '确认晋升的时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员';

-- --------------------------------------------------------

--
-- 表的结构 `app_manage_deduct`
--

CREATE TABLE `app_manage_deduct` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL COMMENT 'manage表主键',
  `leave` int(1) DEFAULT '1' COMMENT '1=>事假，2=》病假，3=》迟到/早退，4=》保险，5=》其他',
  `start_time` int(11) DEFAULT NULL COMMENT '请假开始时间',
  `end_time` int(11) DEFAULT NULL COMMENT '请假结束时间',
  `reason` varchar(255) DEFAULT NULL COMMENT '原因',
  `ischeck` int(11) DEFAULT '0' COMMENT '0=>等待批准，1=》准许，2=》拒绝 ',
  `checkid` int(11) DEFAULT NULL COMMENT '批准假条的用户id',
  `message` varchar(255) DEFAULT NULL COMMENT '批准人留言'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_manage_department`
--

CREATE TABLE `app_manage_department` (
  `id` int(11) NOT NULL COMMENT '主键',
  `department` varchar(30) DEFAULT NULL COMMENT '所属部门',
  `f_id` int(11) DEFAULT NULL COMMENT '上级部门id',
  `status` int(1) DEFAULT '1' COMMENT '部门是否启用。1=>启用,0=>禁用'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_manage_group`
--

CREATE TABLE `app_manage_group` (
  `id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_manage_login_log`
--

CREATE TABLE `app_manage_login_log` (
  `uid` int(11) DEFAULT NULL COMMENT '登录管理员id',
  `overtime` int(11) DEFAULT NULL COMMENT '登录时间',
  `overip` varchar(255) DEFAULT NULL COMMENT '登录Ip地址'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_manage_wage`
--

CREATE TABLE `app_manage_wage` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL COMMENT 'manage 主键',
  `base_wage` decimal(9,2) DEFAULT NULL COMMENT '所在等级基本工资',
  `scale` int(11) DEFAULT NULL COMMENT '输入的比例',
  `week_payback` decimal(9,2) DEFAULT '0.00' COMMENT '周任务收益',
  `tdr_payback` decimal(9,2) DEFAULT '0.00' COMMENT '降量任务收益',
  `visit_payback` decimal(9,2) DEFAULT '0.00' COMMENT '回访任务收益（见习客服权限才有）',
  `task_payback` decimal(9,2) DEFAULT '0.00' COMMENT '新用户任务收益',
  `bonus` decimal(9,2) DEFAULT NULL COMMENT '奖金红包',
  `deduct` decimal(9,2) DEFAULT NULL COMMENT '扣款',
  `date` varchar(255) DEFAULT NULL COMMENT '工资条所属年月',
  `publish` int(11) DEFAULT NULL COMMENT '工资发布人的id',
  `should_pay` decimal(9,2) DEFAULT NULL COMMENT '应支付',
  `total_pay` decimal(9,2) DEFAULT NULL COMMENT '实际支付',
  `publish_time` varchar(255) DEFAULT NULL COMMENT '工资条发布时间',
  `com` decimal(10,2) DEFAULT NULL COMMENT '见习主管，主管手下客服任务提成总和10%'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_market_softpak`
--

CREATE TABLE `app_market_softpak` (
  `id` int(6) UNSIGNED NOT NULL,
  `uid` int(6) UNSIGNED DEFAULT NULL COMMENT '用户id',
  `type` char(10) NOT NULL COMMENT '类型',
  `serial_number` varchar(6) NOT NULL COMMENT '软件序列号',
  `version` varchar(3) NOT NULL COMMENT '软件版本号',
  `url` varchar(80) NOT NULL COMMENT '软件地址',
  `codeurl` varchar(80) NOT NULL COMMENT '二维码路径',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，0已分配，1未分配',
  `allot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0自动分配，1手动分配',
  `closed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0可用，1已封号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='应用市场';

-- --------------------------------------------------------

--
-- 表的结构 `app_member`
--

CREATE TABLE `app_member` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` char(20) NOT NULL,
  `password` char(32) NOT NULL,
  `tel` char(20) NOT NULL COMMENT '电话',
  `mail` char(30) NOT NULL,
  `qq` char(11) NOT NULL,
  `bank` char(4) NOT NULL COMMENT '开户银行',
  `bank_no` char(50) NOT NULL COMMENT '银行卡号',
  `bank_site` char(50) NOT NULL COMMENT '开户地址',
  `holder` char(10) NOT NULL COMMENT '收款人',
  `id_card` char(20) NOT NULL COMMENT '身份证',
  `clients` char(30) NOT NULL,
  `jointime` char(12) NOT NULL COMMENT '注册时间',
  `overtime` char(12) NOT NULL COMMENT '最后登录时间',
  `scale` char(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户状态',
  `content` char(200) NOT NULL COMMENT '备注',
  `agent` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '代理商ID，0为官网',
  `father_id` int(11) UNSIGNED NOT NULL COMMENT '推广链接父级',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '用户类型，0普通用户，1代理商',
  `alias` char(32) NOT NULL COMMENT '别名',
  `point` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '扣点',
  `province` int(11) NOT NULL COMMENT '省',
  `city` int(11) NOT NULL COMMENT '市',
  `county` int(11) NOT NULL COMMENT '县',
  `regist_tel` char(20) NOT NULL COMMENT '注册电话',
  `manage_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '客服ID',
  `wt_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '周任务id',
  `cataid` int(11) UNSIGNED NOT NULL COMMENT '用户状态树分类id',
  `category` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户类型',
  `reg_ip` varchar(20) DEFAULT NULL,
  `login_ip` varchar(20) DEFAULT NULL,
  `invitationcode` varchar(10) DEFAULT NULL COMMENT '邀请码',
  `subagent` int(11) UNSIGNED DEFAULT NULL COMMENT '二级代理商',
  `credits` int(11) UNSIGNED DEFAULT '0' COMMENT '积分',
  `weixin_createtimes` int(11) UNSIGNED DEFAULT NULL,
  `weixin_openid` varchar(32) DEFAULT NULL,
  `weixin_name` varchar(32) DEFAULT NULL COMMENT '微信名',
  `qrcode` varchar(100) DEFAULT NULL COMMENT '二维码',
  `source_id` int(11) DEFAULT NULL COMMENT '推广来源',
  `datadown` int(11) DEFAULT NULL,
  `launcher_install` int(2) DEFAULT '1',
  `uninstall` int(2) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=REDUNDANT;

-- --------------------------------------------------------

--
-- 表的结构 `app_memberpool_bak`
--

CREATE TABLE `app_memberpool_bak` (
  `id` int(11) UNSIGNED NOT NULL,
  `mid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '管理ID',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户ID',
  `createtime` datetime DEFAULT NULL COMMENT '发布时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未被申请，1已申请'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='备选用户池';

-- --------------------------------------------------------

--
-- 表的结构 `app_member_bill`
--

CREATE TABLE `app_member_bill` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `paid` float(10,2) NOT NULL COMMENT '已支付',
  `nopay` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '未付款',
  `surplus` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '剩余',
  `cy` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '当月收益'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_member_category`
--

CREATE TABLE `app_member_category` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` char(50) NOT NULL COMMENT '类型',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可用'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户类型表';

-- --------------------------------------------------------

--
-- 表的结构 `app_member_credits_log`
--

CREATE TABLE `app_member_credits_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `create_datetime` varchar(10) NOT NULL,
  `memberId` int(11) NOT NULL COMMENT '会员id',
  `credits` int(10) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `opid` int(10) UNSIGNED NOT NULL COMMENT '操作员id',
  `account_credits` int(11) NOT NULL COMMENT '结算积分，最终积分',
  `source` varchar(20) NOT NULL COMMENT '积分来源'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_member_credits_source`
--

CREATE TABLE `app_member_credits_source` (
  `name` varchar(20) NOT NULL,
  `title` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_member_info_log`
--

CREATE TABLE `app_member_info_log` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'id',
  `mid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户ID',
  `utype` char(6) NOT NULL COMMENT '修改人类型',
  `username` char(20) NOT NULL COMMENT '修改人',
  `detail` varchar(1000) NOT NULL COMMENT '内容',
  `createtime` int(11) NOT NULL COMMENT '修改时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_member_location`
--

CREATE TABLE `app_member_location` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL COMMENT '姓名',
  `address` varchar(255) NOT NULL COMMENT '地址',
  `tel` varchar(20) NOT NULL COMMENT '联系电话',
  `mid` int(11) NOT NULL COMMENT '会员id',
  `create_datetime` int(10) NOT NULL,
  `update_datetime` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收货地址';

-- --------------------------------------------------------

--
-- 表的结构 `app_member_login_log`
--

CREATE TABLE `app_member_login_log` (
  `uid` int(11) DEFAULT NULL COMMENT '登录管理员id',
  `overtime` int(11) DEFAULT NULL COMMENT '登录时间',
  `overip` varchar(255) DEFAULT NULL COMMENT '登录Ip地址'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_member_paylog`
--

CREATE TABLE `app_member_paylog` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(10) NOT NULL COMMENT '用户ID',
  `ask_sum` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '申请金额',
  `sums` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `ask_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '申请时间',
  `answer_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '回复时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '申请状态0申请中，1已付款',
  `valid` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效0无效1有效',
  `surplus` float(10,2) NOT NULL DEFAULT '0.00',
  `payee` varchar(60) NOT NULL COMMENT '收款人',
  `bank` varchar(255) NOT NULL COMMENT '开户行',
  `bank_site` varchar(255) NOT NULL COMMENT '开户地址',
  `bank_num` varchar(255) NOT NULL COMMENT '银行账户'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_member_resource`
--

CREATE TABLE `app_member_resource` (
  `id` int(11) UNSIGNED NOT NULL,
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `bod` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '网吧序号，0为主用户',
  `type` char(10) NOT NULL COMMENT '资源ID',
  `key` char(32) NOT NULL DEFAULT '' COMMENT '资源key',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态1可用0不可用',
  `openstatus` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '开启状态0关闭1开启',
  `is_put` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否投放',
  `createtime` int(10) UNSIGNED NOT NULL COMMENT '创建时间',
  `motifytime` int(10) UNSIGNED NOT NULL COMMENT '修改时间',
  `closestart` date NOT NULL DEFAULT '0000-00-00' COMMENT '封号时间开始',
  `closeend` date NOT NULL DEFAULT '0000-00-00' COMMENT '封号时间结束'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户资源关系表';

-- --------------------------------------------------------

--
-- 表的结构 `app_member_resource_item`
--

CREATE TABLE `app_member_resource_item` (
  `id` int(11) UNSIGNED NOT NULL,
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `gid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '分组id',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '封号',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `motifytime` int(10) NOT NULL COMMENT '修改时间',
  `type` char(6) NOT NULL COMMENT '资源分类',
  `key` char(32) NOT NULL COMMENT '子ID',
  `openstatus` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态1可用0不可用'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源关系表';

-- --------------------------------------------------------

--
-- 表的结构 `app_member_resource_log`
--

CREATE TABLE `app_member_resource_log` (
  `id` int(11) UNSIGNED NOT NULL,
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户ID',
  `type` char(6) NOT NULL COMMENT '业务类型',
  `mrid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '业务关系ID',
  `open` tinyint(1) NOT NULL DEFAULT '0' COMMENT '开启状态0关闭1开启',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0不可以1可用'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户业务开关LOG';

-- --------------------------------------------------------

--
-- 表的结构 `app_message_log`
--

CREATE TABLE `app_message_log` (
  `id` int(11) NOT NULL,
  `mid` int(10) NOT NULL COMMENT '会员id',
  `theme` char(50) NOT NULL COMMENT '主题',
  `resource` char(100) NOT NULL COMMENT '业务',
  `content` text NOT NULL COMMENT '信息内容',
  `paid` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '已支付',
  `openid` varchar(255) NOT NULL COMMENT '用户绑定的微信号',
  `username` char(50) NOT NULL COMMENT '账户名',
  `createtime` char(12) NOT NULL COMMENT '发送时间',
  `ip` varchar(20) NOT NULL COMMENT '登录ip',
  `category` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1:短信;2:微信',
  `send_type` int(1) NOT NULL COMMENT '0:全体接收;1:登录接收;'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='信息记录';

-- --------------------------------------------------------

--
-- 表的结构 `app_note_log`
--

CREATE TABLE `app_note_log` (
  `id` mediumint(9) NOT NULL,
  `mid` mediumint(9) NOT NULL COMMENT '管理员',
  `uid` mediumint(9) NOT NULL COMMENT '用户',
  `createtime` datetime NOT NULL COMMENT '创建时间',
  `detail` text NOT NULL COMMENT '详情',
  `update_detail` text NOT NULL COMMENT '修改前',
  `tag` varchar(100) NOT NULL COMMENT '标志'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_page`
--

CREATE TABLE `app_page` (
  `id` int(11) NOT NULL,
  `title` varchar(60) NOT NULL COMMENT '标题',
  `content` text NOT NULL,
  `createtime` int(10) NOT NULL,
  `lasttime` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态1正常0隐藏',
  `uid` tinyint(3) NOT NULL COMMENT '用户id',
  `hits` int(10) NOT NULL DEFAULT '0',
  `pathname` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_posts`
--

CREATE TABLE `app_posts` (
  `id` int(11) NOT NULL,
  `title` varchar(60) NOT NULL COMMENT '标题',
  `cid` int(4) NOT NULL COMMENT '栏目id',
  `content` text NOT NULL,
  `createtime` int(10) NOT NULL,
  `lasttime` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态1正常0隐藏',
  `uid` tinyint(3) NOT NULL COMMENT '用户id',
  `hits` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_posts_category`
--

CREATE TABLE `app_posts_category` (
  `id` int(10) NOT NULL,
  `name` varchar(10) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `descriptions` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态1正常0隐藏',
  `seotitle` varchar(100) NOT NULL,
  `createtime` int(10) NOT NULL,
  `lasttime` int(10) NOT NULL,
  `pathname` varchar(15) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_product`
--

CREATE TABLE `app_product` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL COMMENT '资源名称',
  `pathname` varchar(10) NOT NULL COMMENT '标识',
  `pic` varchar(60) NOT NULL COMMENT '图标',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态1可用0不可用',
  `auth` tinyint(1) NOT NULL DEFAULT '0' COMMENT '开通权限0客户1管理员2临时关闭',
  `officialprice` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '官方单价',
  `price` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '用户默认实际单价',
  `quote` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '用户默认报价',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `updatetime` int(10) NOT NULL COMMENT '修改时间',
  `order` int(5) NOT NULL DEFAULT '100' COMMENT '排序',
  `ptype` int(1) NOT NULL COMMENT '0默认1测试区2活动区',
  `install_instructions` varchar(255) NOT NULL COMMENT '安装说明',
  `activate_instructions` varchar(255) NOT NULL,
  `under_instructions` varchar(255) NOT NULL COMMENT '下架须知',
  `content` text NOT NULL COMMENT '产品介绍',
  `enrollment` int(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '人数',
  `actrule` tinyint(1) NOT NULL COMMENT '激活规则',
  `appurl` varchar(20) NOT NULL,
  `category` int(3) NOT NULL COMMENT '业务分类:1:应用市场;1:浏览器;2:新闻资讯;3:在线视频;4:地图;5:工具',
  `sign` varchar(50) NOT NULL COMMENT '产品标示:1:热门;2:推荐;3:新品',
  `default_app` tinyint(2) NOT NULL DEFAULT '0' COMMENT '默认分配'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告资源表';

-- --------------------------------------------------------

--
-- 表的结构 `app_product_categoryname`
--

CREATE TABLE `app_product_categoryname` (
  `id` int(11) UNSIGNED NOT NULL,
  `category_name` varchar(30) NOT NULL COMMENT '类名',
  `createtime` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_product_list`
--

CREATE TABLE `app_product_list` (
  `id` int(11) UNSIGNED NOT NULL,
  `pid` int(3) NOT NULL COMMENT '业务id',
  `agent` int(10) NOT NULL DEFAULT '0' COMMENT '代理商',
  `type` varchar(20) NOT NULL COMMENT '业务类型',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态1可用0不可用',
  `pakid` varchar(32) NOT NULL COMMENT '包id',
  `pakname` varchar(50) NOT NULL COMMENT '包名',
  `sign` varchar(32) NOT NULL COMMENT 'md5',
  `appurl` varchar(80) NOT NULL COMMENT 'app下载地址',
  `filesize` varchar(10) NOT NULL COMMENT '文件大小',
  `version` char(20) NOT NULL COMMENT '版本号',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `isshow` int(1) UNSIGNED DEFAULT '0' COMMENT '1显示0不显示'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告子id列表';

-- --------------------------------------------------------

--
-- 表的结构 `app_qrcode`
--

CREATE TABLE `app_qrcode` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `createtime` int(11) DEFAULT NULL COMMENT '生成时间',
  `qrurl` varchar(80) DEFAULT NULL COMMENT '二维码地址',
  `source` varchar(20) DEFAULT NULL COMMENT '来源',
  `img` varchar(80) DEFAULT NULL COMMENT '图片'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_recruit_class`
--

CREATE TABLE `app_recruit_class` (
  `id` int(100) NOT NULL,
  `classname` varchar(30) NOT NULL,
  `classid` varchar(100) NOT NULL,
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `is_show` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_recruit_job`
--

CREATE TABLE `app_recruit_job` (
  `id` int(100) NOT NULL,
  `jobname` varchar(100) NOT NULL,
  `jobid` varchar(100) NOT NULL,
  `is_show` int(1) NOT NULL DEFAULT '0' COMMENT '是否前显示'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_recruit_resume`
--

CREATE TABLE `app_recruit_resume` (
  `id` int(10) NOT NULL,
  `classname` varchar(30) NOT NULL,
  `jobname` varchar(100) NOT NULL COMMENT '职位名',
  `link_id` int(100) NOT NULL,
  `age` int(1) NOT NULL COMMENT '1:30岁以下;2:30岁以上;3:不限',
  `sex` int(1) NOT NULL COMMENT '1:男;2女;3:不限',
  `need_num` int(1) NOT NULL COMMENT '1:1人;2:2人;3:若干',
  `education` int(1) NOT NULL COMMENT '1:不限;2:大专及以上;3:本科及以上',
  `experience` int(1) NOT NULL COMMENT '1:不限，可接受应届毕业生;2:一年以下;3:一年到两年;4:两年到三年',
  `salary` int(1) NOT NULL COMMENT '1:2k-4k;2:4k-6k;3:6k-8k;4:面议',
  `pay` text NOT NULL COMMENT '1:六险一金;2:带薪年假;3:周末双休;4:年底双薪;5:全勤奖;6:员工旅游;7:其他',
  `duty` text NOT NULL COMMENT '岗位职责',
  `job_require` text NOT NULL COMMENT '任职要求',
  `createtime` int(11) NOT NULL,
  `tel_num` varchar(20) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `is_show` int(1) NOT NULL DEFAULT '0' COMMENT '0:不显示;1:显示',
  `working_place` text NOT NULL COMMENT '工作地点'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_role`
--

CREATE TABLE `app_role` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'PK',
  `name` char(20) NOT NULL COMMENT '角色名称',
  `fid` int(11) UNSIGNED NOT NULL COMMENT '上级ID',
  `base_wage` decimal(10,0) NOT NULL COMMENT '该等级下的基本工资'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色表';

-- --------------------------------------------------------

--
-- 表的结构 `app_rom_appresource`
--

CREATE TABLE `app_rom_appresource` (
  `id` int(6) UNSIGNED NOT NULL,
  `uid` int(6) UNSIGNED NOT NULL COMMENT '用户ID',
  `type` char(20) NOT NULL COMMENT '业务类型',
  `imeicode` char(15) DEFAULT NULL COMMENT '手机imei码',
  `simcode` char(30) DEFAULT NULL COMMENT 'sim卡',
  `tjcode` char(30) DEFAULT NULL COMMENT '统计app内码',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1监视0不监视',
  `model` varchar(30) DEFAULT NULL COMMENT '手机型号',
  `brand` varchar(30) DEFAULT NULL COMMENT '品牌',
  `sys` varchar(30) DEFAULT NULL COMMENT 'andriod系统',
  `finishstatus` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0未完成1完成',
  `createtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `createstamp` int(11) UNSIGNED NOT NULL COMMENT '创建时间戳',
  `closeend` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '封号时间',
  `finishdate` date NOT NULL COMMENT '完成激活时间点',
  `finishtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '完成激活时间',
  `installtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '安装时间',
  `tcfirsttime` datetime DEFAULT NULL COMMENT '首次到达时间',
  `installcount` int(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '安装次数',
  `ip` char(15) DEFAULT NULL,
  `from` int(2) UNSIGNED DEFAULT '0',
  `tc` int(1) UNSIGNED DEFAULT NULL COMMENT '套餐标记',
  `tcid` int(1) UNSIGNED DEFAULT NULL COMMENT '首次到达',
  `noincome` int(1) UNSIGNED DEFAULT NULL COMMENT '0不计费1计费',
  `is_check` int(1) UNSIGNED DEFAULT '0' COMMENT '导入判定0-默认1-判定'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户统计APP关系表';

-- --------------------------------------------------------

--
-- 表的结构 `app_rom_appupdata`
--

CREATE TABLE `app_rom_appupdata` (
  `id` int(12) UNSIGNED NOT NULL,
  `uid` int(6) UNSIGNED NOT NULL COMMENT '用户id',
  `simcode` char(30) DEFAULT NULL COMMENT 'sim卡',
  `sys` varchar(20) DEFAULT NULL COMMENT 'andriod系统',
  `mac` char(17) DEFAULT NULL COMMENT '手机mac',
  `imeicode` char(15) DEFAULT NULL COMMENT '手机imeicode',
  `tjcode` char(30) DEFAULT NULL COMMENT '统计app内码',
  `model` char(20) DEFAULT NULL COMMENT '手机型号',
  `brand` varchar(30) DEFAULT NULL COMMENT '品牌',
  `com` char(10) DEFAULT NULL COMMENT '运营商',
  `appname` varchar(20) NOT NULL COMMENT '业务名',
  `runlength` int(3) UNSIGNED DEFAULT NULL COMMENT '运行时长-分',
  `runcount` int(3) UNSIGNED DEFAULT NULL COMMENT '运行次数',
  `runtime` datetime DEFAULT NULL COMMENT '运行时间点',
  `type` int(1) UNSIGNED DEFAULT NULL COMMENT '卸载-1',
  `date` date NOT NULL COMMENT '数据时间',
  `appmd5` char(32) DEFAULT NULL COMMENT 'appmd5',
  `createtime` datetime NOT NULL COMMENT '上报时间',
  `finshstatus` int(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '完成状态',
  `finshstatustime` date DEFAULT NULL COMMENT '完成状态时间',
  `ip` char(15) DEFAULT NULL,
  `from` int(2) UNSIGNED DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='上报表';

-- --------------------------------------------------------

--
-- 表的结构 `app_rom_appupdatalog`
--

CREATE TABLE `app_rom_appupdatalog` (
  `id` int(12) UNSIGNED NOT NULL,
  `uid` int(6) UNSIGNED NOT NULL COMMENT '用户id',
  `simcode` char(30) DEFAULT NULL COMMENT 'sim卡',
  `sys` varchar(20) DEFAULT NULL COMMENT 'andriod系统',
  `mac` char(17) DEFAULT NULL COMMENT '手机mac',
  `imeicode` char(15) DEFAULT NULL COMMENT '手机imeicode',
  `tjcode` char(30) DEFAULT NULL COMMENT '统计app内码',
  `model` char(20) DEFAULT NULL COMMENT '手机型号',
  `appname` varchar(20) NOT NULL COMMENT '业务名',
  `runlength` int(3) UNSIGNED DEFAULT NULL COMMENT '运行时长-分',
  `runcount` int(3) UNSIGNED DEFAULT NULL COMMENT '运行次数',
  `type` int(1) UNSIGNED DEFAULT NULL COMMENT '卸载-1',
  `appmd5` char(32) DEFAULT NULL COMMENT 'appmd5',
  `createtime` datetime NOT NULL COMMENT '上报时间',
  `first` int(1) UNSIGNED DEFAULT NULL COMMENT '首次上报',
  `status` int(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0正常1封号',
  `ip` char(15) DEFAULT NULL,
  `from` int(2) UNSIGNED DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='上报表日志';

-- --------------------------------------------------------

--
-- 表的结构 `app_rom_appupdata_day`
--

CREATE TABLE `app_rom_appupdata_day` (
  `id` int(12) NOT NULL,
  `uid` int(6) NOT NULL COMMENT '用户id',
  `simcode` char(30) NOT NULL COMMENT 'sim卡',
  `sys` varchar(20) CHARACTER SET utf8mb4 NOT NULL COMMENT 'andriod系统',
  `mac` char(17) NOT NULL COMMENT '手机mac',
  `imeicode` char(15) NOT NULL COMMENT '手机imeicode',
  `tjcode` char(30) NOT NULL COMMENT '统计app内码',
  `models` char(20) NOT NULL COMMENT '手机型号',
  `brand` varchar(30) NOT NULL COMMENT '品牌',
  `com` char(10) NOT NULL COMMENT '运营商',
  `type` int(1) NOT NULL COMMENT '统计上报1',
  `ip` char(15) NOT NULL COMMENT 'ip地址',
  `createtime` datetime NOT NULL COMMENT '首次创建',
  `reportime` datetime NOT NULL COMMENT '最后时间',
  `reportimestamp` int(11) NOT NULL COMMENT '时间戳',
  `count` int(12) NOT NULL COMMENT '上报计数'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='检测日上报';

-- --------------------------------------------------------

--
-- 表的结构 `app_rom_appupdata_dtmark`
--

CREATE TABLE `app_rom_appupdata_dtmark` (
  `id` int(12) UNSIGNED NOT NULL,
  `uid` int(6) UNSIGNED NOT NULL COMMENT '用户id',
  `simcode` char(30) DEFAULT NULL COMMENT 'sim卡',
  `sys` varchar(20) DEFAULT NULL COMMENT 'andriod系统',
  `mac` char(17) DEFAULT NULL COMMENT '手机mac',
  `imeicode` char(15) DEFAULT NULL COMMENT '手机imeicode',
  `tjcode` char(30) DEFAULT NULL COMMENT '统计app内码',
  `model` char(20) DEFAULT NULL COMMENT '手机型号',
  `com` char(10) DEFAULT NULL COMMENT '运营商',
  `appname` varchar(20) NOT NULL COMMENT '业务名',
  `runlength` int(3) UNSIGNED DEFAULT NULL COMMENT '运行时长-分',
  `runcount` int(3) UNSIGNED DEFAULT NULL COMMENT '运行次数',
  `runtime` datetime DEFAULT NULL COMMENT '运行时间点',
  `type` int(1) UNSIGNED DEFAULT NULL COMMENT '卸载-1',
  `date` date NOT NULL COMMENT '数据时间',
  `appmd5` char(32) DEFAULT NULL COMMENT 'appmd5',
  `createtime` datetime NOT NULL COMMENT '上报时间',
  `finshstatus` int(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '完成状态',
  `finshstatustime` date DEFAULT NULL COMMENT '完成状态时间',
  `ip` char(15) DEFAULT NULL,
  `from` int(2) UNSIGNED DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='上报表';

-- --------------------------------------------------------

--
-- 表的结构 `app_rom_arrival_count`
--

CREATE TABLE `app_rom_arrival_count` (
  `id` int(11) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `arrive_tc` int(11) NOT NULL COMMENT '到达套餐ID',
  `arrive_count` int(11) NOT NULL COMMENT '到达个数',
  `arrive_backcount` int(11) NOT NULL COMMENT '扣量之前个数',
  `date` date NOT NULL COMMENT '数据日期'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_rom_boxdata`
--

CREATE TABLE `app_rom_boxdata` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '文件名',
  `md5` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `downPath` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '下载地址',
  `uid` int(11) DEFAULT '0' COMMENT '默认0 单用户自己id',
  `createtime` int(11) DEFAULT NULL,
  `updatetime` timestamp NULL DEFAULT NULL,
  `filesize` varchar(10) DEFAULT NULL,
  `classify` varchar(20) DEFAULT NULL,
  `tid` int(10) DEFAULT NULL COMMENT '产品id/统计id',
  `version` char(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 表的结构 `app_rom_boxdesk`
--

CREATE TABLE `app_rom_boxdesk` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `tid` int(11) DEFAULT NULL COMMENT '统计id',
  `md5` varchar(32) DEFAULT NULL COMMENT 'md5值',
  `filename` varchar(30) DEFAULT NULL COMMENT '文件名',
  `downloadurl` varchar(100) DEFAULT NULL COMMENT '下载地址',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `createtime` int(13) DEFAULT NULL,
  `updatetime` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `filesize` varchar(10) DEFAULT NULL,
  `version` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_rom_box_package`
--

CREATE TABLE `app_rom_box_package` (
  `id` int(11) UNSIGNED NOT NULL,
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户id',
  `pack_id` int(11) NOT NULL COMMENT '套餐ID',
  `box_number` varchar(40) NOT NULL COMMENT '盒子序列号（设备码）',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，0禁用，1可用'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_rom_instalcheck`
--

CREATE TABLE `app_rom_instalcheck` (
  `id` int(11) UNSIGNED NOT NULL,
  `uid` int(6) UNSIGNED NOT NULL,
  `model` varchar(30) DEFAULT NULL COMMENT '手机型号',
  `counts` int(11) UNSIGNED NOT NULL,
  `createstamp` int(11) NOT NULL COMMENT '创建时间戳',
  `type` int(1) NOT NULL COMMENT '1:按时间分组;2:按model分组'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_rom_log`
--

CREATE TABLE `app_rom_log` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(5) DEFAULT NULL COMMENT '标题',
  `request` text COMMENT '内容',
  `result` text COMMENT '结果',
  `createtime` datetime DEFAULT NULL COMMENT '上报时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='站内信内容';

-- --------------------------------------------------------

--
-- 表的结构 `app_rom_package`
--

CREATE TABLE `app_rom_package` (
  `id` int(11) UNSIGNED NOT NULL,
  `uid` int(11) UNSIGNED NOT NULL COMMENT '0系统',
  `package_name` char(50) NOT NULL COMMENT '套餐名',
  `pid` varchar(255) NOT NULL COMMENT '产品id',
  `filesize` varchar(10) NOT NULL COMMENT '套餐大小',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '套餐中业务个数',
  `install_bill` int(10) NOT NULL COMMENT '安装收益',
  `arrive_bill` int(11) NOT NULL COMMENT '到达收益',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `sign` int(10) NOT NULL DEFAULT '1' COMMENT '0:自定义;1:默认系统',
  `type` int(3) NOT NULL COMMENT '0盒子1一键2路由3PC'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_rom_softbox`
--

CREATE TABLE `app_rom_softbox` (
  `id` int(11) UNSIGNED NOT NULL,
  `uid` int(11) UNSIGNED DEFAULT NULL COMMENT '用户id',
  `box_number` varchar(40) NOT NULL COMMENT '盒子序列号（设备码）',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，0禁用，1可用',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(11) DEFAULT NULL COMMENT '更新时间',
  `mid` int(11) DEFAULT NULL COMMENT '最后修改人',
  `type` tinyint(4) DEFAULT '0' COMMENT '接口型号0-二口1-八口'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='rom统计工具';

-- --------------------------------------------------------

--
-- 表的结构 `app_rom_softpak`
--

CREATE TABLE `app_rom_softpak` (
  `id` int(6) UNSIGNED NOT NULL,
  `uid` int(6) UNSIGNED DEFAULT NULL COMMENT '用户id',
  `type` char(10) NOT NULL COMMENT '类型',
  `serial_number` varchar(6) NOT NULL COMMENT '软件序列号',
  `version` varchar(3) NOT NULL COMMENT '软件版本号',
  `md5` varchar(32) NOT NULL DEFAULT '1',
  `url` varchar(80) NOT NULL COMMENT '软件地址',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，0已分配，1未分配',
  `allot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0自动分配，1手动分配',
  `closed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0可用，1已封号',
  `updatetime` datetime NOT NULL COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='rom统计工具';

-- --------------------------------------------------------

--
-- 表的结构 `app_rom_softroute`
--

CREATE TABLE `app_rom_softroute` (
  `id` mediumint(6) UNSIGNED NOT NULL,
  `uid` mediumint(6) UNSIGNED DEFAULT NULL COMMENT '用户id',
  `route_number` varchar(200) NOT NULL COMMENT '路由识别码',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，0禁用，1可用',
  `createtime` datetime DEFAULT NULL COMMENT '添加时间',
  `updatetime` datetime DEFAULT NULL COMMENT '更新时间',
  `mid` int(11) DEFAULT NULL COMMENT '最后修改人',
  `type` tinyint(4) DEFAULT '0' COMMENT '0,1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='路由软件';

-- --------------------------------------------------------

--
-- 表的结构 `app_rom_subagentdata`
--

CREATE TABLE `app_rom_subagentdata` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `imeicode` varchar(255) NOT NULL COMMENT '手机imei码',
  `install` smallint(6) NOT NULL COMMENT '安装数量',
  `uninstall` smallint(6) NOT NULL COMMENT '卸载数量',
  `activation` smallint(6) NOT NULL COMMENT '激活数量',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `createtime` datetime NOT NULL COMMENT '创建时间',
  `price` double(4,2) NOT NULL,
  `datetimes` int(11) NOT NULL COMMENT '判定时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_row_softdownload_record`
--

CREATE TABLE `app_row_softdownload_record` (
  `id` int(11) UNSIGNED NOT NULL,
  `pid` int(3) NOT NULL COMMENT '业务id',
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `tj_version` varchar(3) NOT NULL COMMENT '统计版本号',
  `tj_url` varchar(80) NOT NULL COMMENT '统计软件地址',
  `sign` varchar(32) NOT NULL COMMENT 'md5',
  `download_time` char(12) NOT NULL COMMENT '下载时间',
  `user_ip` varchar(20) DEFAULT NULL COMMENT '用户ip地址',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '用户类型'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_salary`
--

CREATE TABLE `app_salary` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '员工id',
  `bonus` decimal(10,0) DEFAULT '0' COMMENT '额外奖金',
  `task_payback` decimal(10,0) DEFAULT '0' COMMENT '普通任务收益',
  `t_prottime` varchar(10) DEFAULT NULL COMMENT '任务上报的时间',
  `week_payback` decimal(10,0) DEFAULT '0' COMMENT '周任务收益'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_serachinfo_records`
--

CREATE TABLE `app_serachinfo_records` (
  `id` int(11) UNSIGNED NOT NULL,
  `sid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mid` int(11) UNSIGNED NOT NULL COMMENT '客服ID',
  `content` text COMMENT '内容',
  `jointime` int(10) DEFAULT NULL COMMENT '添加时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='搜索用户咨询记录表';

-- --------------------------------------------------------

--
-- 表的结构 `app_serach_info`
--

CREATE TABLE `app_serach_info` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` char(20) DEFAULT NULL,
  `username` char(20) DEFAULT NULL COMMENT '注册用户名',
  `tel` char(50) DEFAULT NULL COMMENT '电话',
  `mail` char(50) DEFAULT NULL,
  `qq` char(50) DEFAULT NULL,
  `reg_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '注册状态:0=>未注册1=>已注册',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '有效状态:0=>未审核1=>有效2=>无效',
  `content` char(200) DEFAULT NULL COMMENT '备注',
  `com` char(50) DEFAULT NULL COMMENT '公司',
  `area` char(20) DEFAULT NULL COMMENT '地区',
  `source` char(30) DEFAULT NULL COMMENT '信息来源',
  `search_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '录入ID',
  `manage_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '客服ID',
  `createtime` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `motifytime` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '最后修改时间',
  `tixingtime` datetime DEFAULT NULL COMMENT '提醒时间',
  `zixuntime` datetime DEFAULT NULL,
  `type` int(1) DEFAULT '0' COMMENT '用户类型区分',
  `userarea` varchar(50) DEFAULT NULL COMMENT '所属地区'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=REDUNDANT;

-- --------------------------------------------------------

--
-- 表的结构 `app_serach_info_log`
--

CREATE TABLE `app_serach_info_log` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'id',
  `mid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户ID',
  `utype` char(6) NOT NULL COMMENT '修改人类型',
  `username` char(20) NOT NULL COMMENT '修改人',
  `detail` varchar(1000) NOT NULL COMMENT '内容',
  `createtime` int(11) NOT NULL COMMENT '修改时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_share_count`
--

CREATE TABLE `app_share_count` (
  `id` int(11) NOT NULL,
  `hits` int(11) DEFAULT '0' COMMENT '点击次数',
  `ip` varchar(20) DEFAULT NULL,
  `is_login` int(10) DEFAULT NULL COMMENT '0-未登录',
  `uid` int(11) DEFAULT NULL,
  `date` int(11) DEFAULT NULL COMMENT '分享时间',
  `sid` int(11) DEFAULT NULL COMMENT '渠道id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_shop_goods`
--

CREATE TABLE `app_shop_goods` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(250) NOT NULL COMMENT '标题',
  `intro` text NOT NULL COMMENT '简介',
  `content` text NOT NULL COMMENT '内容',
  `credits` int(10) UNSIGNED NOT NULL COMMENT '所需积分',
  `status` int(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态1正常0关闭',
  `create_datetime` int(10) UNSIGNED NOT NULL COMMENT '创建时间',
  `update_datetime` int(10) UNSIGNED NOT NULL COMMENT '更新时间',
  `coverimage` varchar(255) NOT NULL COMMENT '封面图片',
  `previewimage` varchar(255) NOT NULL COMMENT '预览图',
  `address` varchar(150) NOT NULL,
  `num` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '兑换人数',
  `hits` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `uid` int(11) NOT NULL,
  `order` int(5) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
  `recommend` int(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '推荐1不推荐0',
  `kucun` int(10) UNSIGNED NOT NULL COMMENT '库存',
  `down_datetime` varchar(20) NOT NULL COMMENT '下架时间',
  `cid` int(4) UNSIGNED NOT NULL COMMENT '商品分类'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_shop_goods_category`
--

CREATE TABLE `app_shop_goods_category` (
  `id` int(11) NOT NULL,
  `cname` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '分类名',
  `status` int(11) DEFAULT '1' COMMENT '状态 1-可用 0-禁用',
  `mid` int(11) DEFAULT NULL COMMENT '填加者',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 表的结构 `app_shop_goods_order`
--

CREATE TABLE `app_shop_goods_order` (
  `id` int(10) UNSIGNED NOT NULL,
  `gid` int(11) NOT NULL COMMENT '商品id',
  `mid` int(10) UNSIGNED NOT NULL COMMENT '会员id',
  `create_datetime` int(10) NOT NULL,
  `status` int(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态0待发货1已发货2取消订单',
  `address` varchar(255) NOT NULL COMMENT '地址',
  `tel` varchar(20) NOT NULL COMMENT '手机号码',
  `username` varchar(20) NOT NULL COMMENT '姓名',
  `remarks` text NOT NULL COMMENT '备注信息：包含快递信息',
  `gname` varchar(255) NOT NULL COMMENT '商品名称',
  `update_datetime` int(10) NOT NULL,
  `credits` int(11) NOT NULL DEFAULT '0' COMMENT '使用积分',
  `opid` int(5) UNSIGNED NOT NULL COMMENT '操作员id',
  `realcredits` int(11) NOT NULL COMMENT '实际积分',
  `num` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '数量',
  `reply` text NOT NULL COMMENT '回复消息',
  `mailname` varchar(20) DEFAULT NULL,
  `mailcode` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品兑换记录';

-- --------------------------------------------------------

--
-- 表的结构 `app_spread_source`
--

CREATE TABLE `app_spread_source` (
  `id` int(11) NOT NULL,
  `source_name` varchar(30) DEFAULT NULL COMMENT '渠道名称',
  `source_mark` varchar(20) DEFAULT NULL COMMENT '渠道标识',
  `source_reg` int(11) DEFAULT '0' COMMENT '注册人数',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `mid` int(11) DEFAULT NULL COMMENT '创建人',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态 0-不可用 1-可用'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_subagent_price`
--

CREATE TABLE `app_subagent_price` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '子用户',
  `price` double NOT NULL COMMENT '价格',
  `agent` mediumint(10) NOT NULL COMMENT '代理',
  `updatetime` datetime NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_system_log`
--

CREATE TABLE `app_system_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` char(20) NOT NULL COMMENT '类型',
  `content` varchar(200) NOT NULL COMMENT '操作内容',
  `target` char(30) NOT NULL COMMENT '操作对象',
  `operate` char(30) NOT NULL COMMENT '操作人',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '操作时间',
  `is_show` int(2) NOT NULL COMMENT '0隐藏1开启'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_task`
--

CREATE TABLE `app_task` (
  `id` int(11) UNSIGNED NOT NULL,
  `publish` int(11) UNSIGNED NOT NULL COMMENT '发布人',
  `accept` int(11) UNSIGNED NOT NULL COMMENT '接收人',
  `createtime` int(10) UNSIGNED NOT NULL COMMENT '发布时间',
  `motifytime` int(10) UNSIGNED NOT NULL COMMENT '修改时间',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `content` varchar(255) NOT NULL COMMENT '内容',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0进行中，1已提交，2已完成，3已删除',
  `isshow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否查看0未看，1已看',
  `mid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户ID',
  `type` int(11) DEFAULT '1' COMMENT '1=>新客户任务，2=>降量任务,3=>周任务，4=>其他',
  `test` int(11) DEFAULT '0',
  `availability` int(1) DEFAULT NULL COMMENT '有效回访：1=>有效,2=>无效,其它为申请'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='任务表';

-- --------------------------------------------------------

--
-- 表的结构 `app_task_earnings`
--

CREATE TABLE `app_task_earnings` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '客服id',
  `mid` int(11) NOT NULL COMMENT '用户id',
  `createtime` int(11) NOT NULL COMMENT '申请发布时间',
  `endtime` int(11) NOT NULL COMMENT '上报时间',
  `payback` decimal(9,2) NOT NULL COMMENT '任务收益',
  `role` int(2) NOT NULL COMMENT '上报任务时客服等级',
  `type` int(1) NOT NULL COMMENT '1=》新用户任务  2=》降量任务。',
  `atid` int(11) NOT NULL COMMENT '任务申请表的id',
  `twid` int(11) NOT NULL COMMENT '任务详情表id',
  `ispay` int(1) DEFAULT '0' COMMENT '工资是否发布，0=》未发布，1=》已发布',
  `motifytime` int(11) DEFAULT NULL COMMENT '任务收益修改的时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_task_week_earnings`
--

CREATE TABLE `app_task_week_earnings` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '客服id',
  `createtime` int(11) NOT NULL COMMENT '开始时间',
  `endtime` int(11) NOT NULL COMMENT '结束时间',
  `percent` int(3) NOT NULL COMMENT '合格率',
  `concount` int(2) NOT NULL COMMENT '合格任务数量',
  `askcount` int(2) NOT NULL COMMENT '申请的任务总数量',
  `payback` decimal(9,2) NOT NULL COMMENT '通过周任务获得的收益',
  `role` int(2) NOT NULL COMMENT '当时客服所属的等级'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_task_when`
--

CREATE TABLE `app_task_when` (
  `id` int(11) UNSIGNED NOT NULL,
  `tid` int(11) UNSIGNED NOT NULL COMMENT '任务ID',
  `createtime` int(10) UNSIGNED NOT NULL COMMENT '创建时间',
  `motifytime` int(10) UNSIGNED NOT NULL COMMENT '打回时间，评分时间，修改时间',
  `content` varchar(255) NOT NULL COMMENT '内容',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0正常，1已上报，2打回修改',
  `isfail` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0正常，1失败（任务已无法进行）',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '评分0-10',
  `scoreuid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '评分用户',
  `porttime` int(11) DEFAULT NULL COMMENT '客服报告任务的时间',
  `pay_back` decimal(10,2) DEFAULT '0.00' COMMENT '成功完成任务后的回报',
  `remind` int(11) DEFAULT NULL COMMENT '客服设置的任务提醒时间',
  `remark` varchar(80) DEFAULT NULL COMMENT '客服对用户简短备注',
  `important` int(11) DEFAULT '0' COMMENT '客服设置的用户优先级',
  `paytime` int(11) DEFAULT '0' COMMENT '管理员发布任务收益的时间',
  `ispay` int(11) DEFAULT '0' COMMENT '是否发布任务收益',
  `spare` int(1) DEFAULT '0' COMMENT '备用用户池，1=》备用，0=》默认主用户池',
  `a_pay` decimal(10,2) DEFAULT '0.00' COMMENT '任务提交业务收益总和',
  `b_pay` decimal(10,2) DEFAULT '0.00' COMMENT '任务提交前业务收益总和'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='任务进度';

-- --------------------------------------------------------

--
-- 表的结构 `app_week_task`
--

CREATE TABLE `app_week_task` (
  `id` int(11) NOT NULL,
  `f_id` int(11) NOT NULL COMMENT '上报管理员id',
  `m_id` int(11) NOT NULL COMMENT '任务用户id',
  `payback` decimal(10,0) DEFAULT '0' COMMENT '周任务收益',
  `createtime` int(11) DEFAULT NULL COMMENT '标记为周任务的时间',
  `endtime` int(11) DEFAULT NULL COMMENT '周任务结束时间,可选',
  `is_pro` int(1) DEFAULT '0' COMMENT '是否已发布收益，0=》未发布，1=》已发布',
  `at_id` int(11) DEFAULT NULL COMMENT 'ask_task表主键',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '0=》正常，1=》上报，2=》失效，3=》完成，4=》删除',
  `isqualified` int(1) DEFAULT NULL COMMENT '1=》合格，0=》不合格',
  `is_continue` int(1) DEFAULT '0' COMMENT '是否被继续标记 0=》否，1=》是',
  `time_slow` int(1) DEFAULT '0' COMMENT '延迟计算，0=》等待计算，1=》开始计算',
  `a_role` int(11) DEFAULT NULL COMMENT '申请周任务时客服的role等级',
  `time_slow2` int(1) DEFAULT NULL COMMENT '延迟计算2'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_weixin_token`
--

CREATE TABLE `app_weixin_token` (
  `id` int(11) NOT NULL,
  `access_token` text,
  `create_times` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `app_yearend_draw`
--

CREATE TABLE `app_yearend_draw` (
  `id` int(11) NOT NULL,
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `username` char(20) NOT NULL COMMENT '用户名',
  `draw` char(255) NOT NULL COMMENT '抽中奖项',
  `createtime` char(12) NOT NULL COMMENT '创建时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='年末抽奖';

-- --------------------------------------------------------

--
-- 表的结构 `ele_advisory_records`
--

CREATE TABLE `ele_advisory_records` (
  `id` int(11) UNSIGNED NOT NULL,
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `mid` int(11) UNSIGNED NOT NULL COMMENT '客服ID',
  `content` text COMMENT '内容',
  `jointime` int(10) DEFAULT NULL COMMENT '添加时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户咨询记录表';

-- --------------------------------------------------------

--
-- 表的结构 `ele_eda_cpu`
--

CREATE TABLE `ele_eda_cpu` (
  `id` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `cpu_type` varchar(255) NOT NULL COMMENT 'cpu型号',
  `cpu_heshu` tinyint(4) NOT NULL COMMENT 'cpu核数',
  `cpu_use` tinyint(4) NOT NULL COMMENT 'cpu使用核数',
  `cpu_start` tinyint(4) NOT NULL COMMENT '启动比例',
  `cpu_stop` tinyint(4) NOT NULL COMMENT '停止比例',
  `num` tinyint(4) NOT NULL DEFAULT '0' COMMENT '排除程序个数'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ele_eda_process`
--

CREATE TABLE `ele_eda_process` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `gid` tinyint(4) NOT NULL COMMENT '分组',
  `app_name` varchar(100) NOT NULL COMMENT '程序名称',
  `process_name` varchar(100) NOT NULL COMMENT '进程名',
  `status` tinyint(4) DEFAULT '0' COMMENT '状态',
  `del` tinyint(4) DEFAULT '0' COMMENT '移除 0-未 1-移除',
  `cid` int(11) DEFAULT NULL COMMENT 'cpu型号id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `app_advisory_records`
--
ALTER TABLE `app_advisory_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `mid` (`mid`),
  ADD KEY `jointime` (`jointime`);

--
-- Indexes for table `app_agent_price`
--
ALTER TABLE `app_agent_price`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_area`
--
ALTER TABLE `app_area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_article`
--
ALTER TABLE `app_article`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_article_category`
--
ALTER TABLE `app_article_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_article_tags`
--
ALTER TABLE `app_article_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_article_tagslist`
--
ALTER TABLE `app_article_tagslist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_ask_task`
--
ALTER TABLE `app_ask_task`
  ADD PRIMARY KEY (`id`,`type`,`f_id`,`t_id`,`a_id`,`m_id`);

--
-- Indexes for table `app_bind_sample`
--
ALTER TABLE `app_bind_sample`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `val` (`val`);

--
-- Indexes for table `app_campaign`
--
ALTER TABLE `app_campaign`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_campaign_income`
--
ALTER TABLE `app_campaign_income`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_campaign_log`
--
ALTER TABLE `app_campaign_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_campaign_sort`
--
ALTER TABLE `app_campaign_sort`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_catalogue`
--
ALTER TABLE `app_catalogue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_check_phone_log`
--
ALTER TABLE `app_check_phone_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_client_data`
--
ALTER TABLE `app_client_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_client_data_seller`
--
ALTER TABLE `app_client_data_seller`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_client_login_log`
--
ALTER TABLE `app_client_login_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_client_mdapp_data`
--
ALTER TABLE `app_client_mdapp_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_error_log`
--
ALTER TABLE `app_error_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_error_log_rom`
--
ALTER TABLE `app_error_log_rom`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_helper_package`
--
ALTER TABLE `app_helper_package`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_helper_version`
--
ALTER TABLE `app_helper_version`
  ADD PRIMARY KEY (`hv_id`);

--
-- Indexes for table `app_import_agent_log`
--
ALTER TABLE `app_import_agent_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_import_log`
--
ALTER TABLE `app_import_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gid` (`gid`);

--
-- Indexes for table `app_income`
--
ALTER TABLE `app_income`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_2345sjzs`
--
ALTER TABLE `app_income_2345sjzs`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_2345tqw`
--
ALTER TABLE `app_income_2345tqw`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_2345wpllq`
--
ALTER TABLE `app_income_2345wpllq`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_2345ydw`
--
ALTER TABLE `app_income_2345ydw`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_2345ysdq`
--
ALTER TABLE `app_income_2345ysdq`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_all`
--
ALTER TABLE `app_income_all`
  ADD PRIMARY KEY (`uid`,`createtime`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `app_income_aqws360`
--
ALTER TABLE `app_income_aqws360`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_aqy`
--
ALTER TABLE `app_income_aqy`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_aqzm`
--
ALTER TABLE `app_income_aqzm`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_ayd`
--
ALTER TABLE `app_income_ayd`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_azllq`
--
ALTER TABLE `app_income_azllq`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_baidu`
--
ALTER TABLE `app_income_baidu`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_bddt`
--
ALTER TABLE `app_income_bddt`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_bdllq`
--
ALTER TABLE `app_income_bdllq`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_bdsjzs`
--
ALTER TABLE `app_income_bdsjzs`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_bfyy`
--
ALTER TABLE `app_income_bfyy`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_cbdh`
--
ALTER TABLE `app_income_cbdh`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_clear_log`
--
ALTER TABLE `app_income_clear_log`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`,`type`,`cleartime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_dzdp`
--
ALTER TABLE `app_income_dzdp`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_final`
--
ALTER TABLE `app_income_final`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_income_gddt`
--
ALTER TABLE `app_income_gddt`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_jd`
--
ALTER TABLE `app_income_jd`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_jiuyou`
--
ALTER TABLE `app_income_jiuyou`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_jjddz`
--
ALTER TABLE `app_income_jjddz`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_jrtt`
--
ALTER TABLE `app_income_jrtt`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_jyyxzx`
--
ALTER TABLE `app_income_jyyxzx`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_kwyy`
--
ALTER TABLE `app_income_kwyy`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_kxxxl`
--
ALTER TABLE `app_income_kxxxl`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_lbqlds`
--
ALTER TABLE `app_income_lbqlds`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_llq`
--
ALTER TABLE `app_income_llq`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_llq360`
--
ALTER TABLE `app_income_llq360`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_lssp`
--
ALTER TABLE `app_income_lssp`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_meipai`
--
ALTER TABLE `app_income_meipai`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_meituan`
--
ALTER TABLE `app_income_meituan`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_pps`
--
ALTER TABLE `app_income_pps`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_ppzs`
--
ALTER TABLE `app_income_ppzs`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_qqllq`
--
ALTER TABLE `app_income_qqllq`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_qsbk`
--
ALTER TABLE `app_income_qsbk`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_qyyd`
--
ALTER TABLE `app_income_qyyd`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_sdxw`
--
ALTER TABLE `app_income_sdxw`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_sgsc`
--
ALTER TABLE `app_income_sgsc`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_shsp`
--
ALTER TABLE `app_income_shsp`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_sjzs360`
--
ALTER TABLE `app_income_sjzs360`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_szdh`
--
ALTER TABLE `app_income_szdh`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_taobao`
--
ALTER TABLE `app_income_taobao`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_tq`
--
ALTER TABLE `app_income_tq`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_txsjgj`
--
ALTER TABLE `app_income_txsjgj`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_txsp`
--
ALTER TABLE `app_income_txsp`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_txxw`
--
ALTER TABLE `app_income_txxw`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_ucllq`
--
ALTER TABLE `app_income_ucllq`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_wdj`
--
ALTER TABLE `app_income_wdj`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_weixin`
--
ALTER TABLE `app_income_weixin`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_wnl`
--
ALTER TABLE `app_income_wnl`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_wyyyy`
--
ALTER TABLE `app_income_wyyyy`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_wzdq`
--
ALTER TABLE `app_income_wzdq`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_xfsrf`
--
ALTER TABLE `app_income_xfsrf`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_ysdq360`
--
ALTER TABLE `app_income_ysdq360`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_yyb`
--
ALTER TABLE `app_income_yyb`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_yysd`
--
ALTER TABLE `app_income_yysd`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_yyzx`
--
ALTER TABLE `app_income_yyzx`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_zhwnl`
--
ALTER TABLE `app_income_zhwnl`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_zkns`
--
ALTER TABLE `app_income_zkns`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_zmtq`
--
ALTER TABLE `app_income_zmtq`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_zssq`
--
ALTER TABLE `app_income_zssq`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_zysc`
--
ALTER TABLE `app_income_zysc`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_income_zysck`
--
ALTER TABLE `app_income_zysck`
  ADD PRIMARY KEY (`uid`,`mrid`,`createtime`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_invitationcode`
--
ALTER TABLE `app_invitationcode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_link`
--
ALTER TABLE `app_link`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_link_category`
--
ALTER TABLE `app_link_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_mail`
--
ALTER TABLE `app_mail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `content` (`content`),
  ADD KEY `status` (`status`),
  ADD KEY `jointime` (`jointime`),
  ADD KEY `recipient` (`recipient`);

--
-- Indexes for table `app_mail_content`
--
ALTER TABLE `app_mail_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_manage`
--
ALTER TABLE `app_manage`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_manage_deduct`
--
ALTER TABLE `app_manage_deduct`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_manage_department`
--
ALTER TABLE `app_manage_department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_manage_group`
--
ALTER TABLE `app_manage_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_manage_wage`
--
ALTER TABLE `app_manage_wage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_market_softpak`
--
ALTER TABLE `app_market_softpak`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_member`
--
ALTER TABLE `app_member`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `jointime` (`jointime`),
  ADD KEY `status` (`status`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `app_memberpool_bak`
--
ALTER TABLE `app_memberpool_bak`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_member_bill`
--
ALTER TABLE `app_member_bill`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_member_category`
--
ALTER TABLE `app_member_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_member_credits_log`
--
ALTER TABLE `app_member_credits_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_member_credits_source`
--
ALTER TABLE `app_member_credits_source`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `app_member_info_log`
--
ALTER TABLE `app_member_info_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mid` (`mid`);

--
-- Indexes for table `app_member_location`
--
ALTER TABLE `app_member_location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_member_paylog`
--
ALTER TABLE `app_member_paylog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `app_member_resource`
--
ALTER TABLE `app_member_resource`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `key` (`key`),
  ADD KEY `type` (`type`),
  ADD KEY `status` (`status`),
  ADD KEY `closestart` (`closestart`),
  ADD KEY `openstatus` (`openstatus`),
  ADD KEY `bod` (`bod`);

--
-- Indexes for table `app_member_resource_item`
--
ALTER TABLE `app_member_resource_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `status` (`status`),
  ADD KEY `bod` (`gid`);

--
-- Indexes for table `app_member_resource_log`
--
ALTER TABLE `app_member_resource_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `mrid` (`mrid`),
  ADD KEY `status` (`status`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `app_message_log`
--
ALTER TABLE `app_message_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_note_log`
--
ALTER TABLE `app_note_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_page`
--
ALTER TABLE `app_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_posts`
--
ALTER TABLE `app_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_posts_category`
--
ALTER TABLE `app_posts_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_product`
--
ALTER TABLE `app_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_product_categoryname`
--
ALTER TABLE `app_product_categoryname`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_product_list`
--
ALTER TABLE `app_product_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `app_qrcode`
--
ALTER TABLE `app_qrcode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_role`
--
ALTER TABLE `app_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_rom_appresource`
--
ALTER TABLE `app_rom_appresource`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index_names` (`status`,`finishstatus`),
  ADD KEY `index_namess` (`createtime`,`finishdate`,`model`),
  ADD KEY `type_imeicode_uid_tcfirst_tc_tcfirsttime` (`type`,`imeicode`,`uid`,`tcid`,`tc`,`tcfirsttime`),
  ADD KEY `createstamp` (`createstamp`);

--
-- Indexes for table `app_rom_appupdata`
--
ALTER TABLE `app_rom_appupdata`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `date` (`date`),
  ADD KEY `type` (`type`),
  ADD KEY `appname` (`appname`),
  ADD KEY `finshstatus` (`finshstatus`),
  ADD KEY `index_name` (`appname`,`imeicode`,`uid`),
  ADD KEY `index_names` (`finshstatus`,`finshstatustime`),
  ADD KEY `index_namess` (`createtime`);

--
-- Indexes for table `app_rom_appupdatalog`
--
ALTER TABLE `app_rom_appupdatalog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appname` (`appname`),
  ADD KEY `index_namess` (`createtime`),
  ADD KEY `imeicode` (`imeicode`),
  ADD KEY `first` (`first`);

--
-- Indexes for table `app_rom_appupdata_day`
--
ALTER TABLE `app_rom_appupdata_day`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `text` (`id`,`uid`,`imeicode`,`simcode`,`sys`,`mac`,`tjcode`,`models`,`brand`,`com`,`type`,`ip`,`createtime`) USING BTREE;

--
-- Indexes for table `app_rom_appupdata_dtmark`
--
ALTER TABLE `app_rom_appupdata_dtmark`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `date` (`date`),
  ADD KEY `type` (`type`),
  ADD KEY `appname` (`appname`),
  ADD KEY `finshstatus` (`finshstatus`),
  ADD KEY `index_name` (`appname`,`imeicode`,`uid`),
  ADD KEY `index_names` (`finshstatus`,`finshstatustime`),
  ADD KEY `index_namess` (`createtime`);

--
-- Indexes for table `app_rom_arrival_count`
--
ALTER TABLE `app_rom_arrival_count`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_rom_boxdata`
--
ALTER TABLE `app_rom_boxdata`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_rom_boxdesk`
--
ALTER TABLE `app_rom_boxdesk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_rom_box_package`
--
ALTER TABLE `app_rom_box_package`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_rom_instalcheck`
--
ALTER TABLE `app_rom_instalcheck`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`,`uid`,`model`,`counts`,`createstamp`);

--
-- Indexes for table `app_rom_log`
--
ALTER TABLE `app_rom_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_rom_package`
--
ALTER TABLE `app_rom_package`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_rom_softbox`
--
ALTER TABLE `app_rom_softbox`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_rom_softpak`
--
ALTER TABLE `app_rom_softpak`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_rom_softroute`
--
ALTER TABLE `app_rom_softroute`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_rom_subagentdata`
--
ALTER TABLE `app_rom_subagentdata`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_row_softdownload_record`
--
ALTER TABLE `app_row_softdownload_record`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_salary`
--
ALTER TABLE `app_salary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_serachinfo_records`
--
ALTER TABLE `app_serachinfo_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mid` (`mid`),
  ADD KEY `jointime` (`jointime`),
  ADD KEY `sid` (`sid`);

--
-- Indexes for table `app_serach_info`
--
ALTER TABLE `app_serach_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tel` (`tel`),
  ADD KEY `mail` (`mail`),
  ADD KEY `qq` (`qq`);

--
-- Indexes for table `app_serach_info_log`
--
ALTER TABLE `app_serach_info_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mid` (`mid`);

--
-- Indexes for table `app_share_count`
--
ALTER TABLE `app_share_count`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_shop_goods`
--
ALTER TABLE `app_shop_goods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_shop_goods_category`
--
ALTER TABLE `app_shop_goods_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_shop_goods_order`
--
ALTER TABLE `app_shop_goods_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_spread_source`
--
ALTER TABLE `app_spread_source`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_subagent_price`
--
ALTER TABLE `app_subagent_price`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_system_log`
--
ALTER TABLE `app_system_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `target` (`target`);

--
-- Indexes for table `app_task`
--
ALTER TABLE `app_task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `publish` (`publish`),
  ADD KEY `accept` (`accept`);

--
-- Indexes for table `app_task_earnings`
--
ALTER TABLE `app_task_earnings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_task_week_earnings`
--
ALTER TABLE `app_task_week_earnings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_task_when`
--
ALTER TABLE `app_task_when`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tid` (`tid`),
  ADD KEY `scoreuid` (`scoreuid`);

--
-- Indexes for table `app_week_task`
--
ALTER TABLE `app_week_task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_weixin_token`
--
ALTER TABLE `app_weixin_token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_yearend_draw`
--
ALTER TABLE `app_yearend_draw`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ele_advisory_records`
--
ALTER TABLE `ele_advisory_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `mid` (`mid`),
  ADD KEY `jointime` (`jointime`);

--
-- Indexes for table `ele_eda_cpu`
--
ALTER TABLE `ele_eda_cpu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ele_eda_process`
--
ALTER TABLE `ele_eda_process`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `app_advisory_records`
--
ALTER TABLE `app_advisory_records`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10031;
--
-- 使用表AUTO_INCREMENT `app_agent_price`
--
ALTER TABLE `app_agent_price`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- 使用表AUTO_INCREMENT `app_area`
--
ALTER TABLE `app_area`
  MODIFY `id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3467;
--
-- 使用表AUTO_INCREMENT `app_article`
--
ALTER TABLE `app_article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=307;
--
-- 使用表AUTO_INCREMENT `app_article_category`
--
ALTER TABLE `app_article_category`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `app_article_tags`
--
ALTER TABLE `app_article_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- 使用表AUTO_INCREMENT `app_article_tagslist`
--
ALTER TABLE `app_article_tagslist`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- 使用表AUTO_INCREMENT `app_ask_task`
--
ALTER TABLE `app_ask_task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2467;
--
-- 使用表AUTO_INCREMENT `app_bind_sample`
--
ALTER TABLE `app_bind_sample`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=231;
--
-- 使用表AUTO_INCREMENT `app_campaign`
--
ALTER TABLE `app_campaign`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `app_campaign_log`
--
ALTER TABLE `app_campaign_log`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;
--
-- 使用表AUTO_INCREMENT `app_campaign_sort`
--
ALTER TABLE `app_campaign_sort`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- 使用表AUTO_INCREMENT `app_catalogue`
--
ALTER TABLE `app_catalogue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id';
--
-- 使用表AUTO_INCREMENT `app_check_phone_log`
--
ALTER TABLE `app_check_phone_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3447;
--
-- 使用表AUTO_INCREMENT `app_client_data`
--
ALTER TABLE `app_client_data`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15671;
--
-- 使用表AUTO_INCREMENT `app_client_data_seller`
--
ALTER TABLE `app_client_data_seller`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=875;
--
-- 使用表AUTO_INCREMENT `app_client_login_log`
--
ALTER TABLE `app_client_login_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3890;
--
-- 使用表AUTO_INCREMENT `app_client_mdapp_data`
--
ALTER TABLE `app_client_mdapp_data`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1720;
--
-- 使用表AUTO_INCREMENT `app_error_log`
--
ALTER TABLE `app_error_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9058;
--
-- 使用表AUTO_INCREMENT `app_error_log_rom`
--
ALTER TABLE `app_error_log_rom`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1219516;
--
-- 使用表AUTO_INCREMENT `app_helper_package`
--
ALTER TABLE `app_helper_package`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `app_helper_version`
--
ALTER TABLE `app_helper_version`
  MODIFY `hv_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `app_import_agent_log`
--
ALTER TABLE `app_import_agent_log`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id', AUTO_INCREMENT=20;
--
-- 使用表AUTO_INCREMENT `app_import_log`
--
ALTER TABLE `app_import_log`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id', AUTO_INCREMENT=12180;
--
-- 使用表AUTO_INCREMENT `app_income_all`
--
ALTER TABLE `app_income_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25170;
--
-- 使用表AUTO_INCREMENT `app_income_final`
--
ALTER TABLE `app_income_final`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;
--
-- 使用表AUTO_INCREMENT `app_invitationcode`
--
ALTER TABLE `app_invitationcode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `app_link`
--
ALTER TABLE `app_link`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `app_link_category`
--
ALTER TABLE `app_link_category`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `app_mail`
--
ALTER TABLE `app_mail`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69832;
--
-- 使用表AUTO_INCREMENT `app_mail_content`
--
ALTER TABLE `app_mail_content`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69800;
--
-- 使用表AUTO_INCREMENT `app_manage`
--
ALTER TABLE `app_manage`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
--
-- 使用表AUTO_INCREMENT `app_manage_deduct`
--
ALTER TABLE `app_manage_deduct`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `app_manage_department`
--
ALTER TABLE `app_manage_department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=23;
--
-- 使用表AUTO_INCREMENT `app_manage_group`
--
ALTER TABLE `app_manage_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `app_manage_wage`
--
ALTER TABLE `app_manage_wage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `app_market_softpak`
--
ALTER TABLE `app_market_softpak`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=721;
--
-- 使用表AUTO_INCREMENT `app_member`
--
ALTER TABLE `app_member`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2032;
--
-- 使用表AUTO_INCREMENT `app_memberpool_bak`
--
ALTER TABLE `app_memberpool_bak`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=653;
--
-- 使用表AUTO_INCREMENT `app_member_bill`
--
ALTER TABLE `app_member_bill`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=712;
--
-- 使用表AUTO_INCREMENT `app_member_category`
--
ALTER TABLE `app_member_category`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `app_member_credits_log`
--
ALTER TABLE `app_member_credits_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1116;
--
-- 使用表AUTO_INCREMENT `app_member_info_log`
--
ALTER TABLE `app_member_info_log`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id', AUTO_INCREMENT=495;
--
-- 使用表AUTO_INCREMENT `app_member_location`
--
ALTER TABLE `app_member_location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
--
-- 使用表AUTO_INCREMENT `app_member_paylog`
--
ALTER TABLE `app_member_paylog`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1247;
--
-- 使用表AUTO_INCREMENT `app_member_resource`
--
ALTER TABLE `app_member_resource`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107470;
--
-- 使用表AUTO_INCREMENT `app_member_resource_item`
--
ALTER TABLE `app_member_resource_item`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `app_member_resource_log`
--
ALTER TABLE `app_member_resource_log`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6421;
--
-- 使用表AUTO_INCREMENT `app_message_log`
--
ALTER TABLE `app_message_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=261;
--
-- 使用表AUTO_INCREMENT `app_note_log`
--
ALTER TABLE `app_note_log`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2058;
--
-- 使用表AUTO_INCREMENT `app_page`
--
ALTER TABLE `app_page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- 使用表AUTO_INCREMENT `app_posts`
--
ALTER TABLE `app_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;
--
-- 使用表AUTO_INCREMENT `app_posts_category`
--
ALTER TABLE `app_posts_category`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `app_product`
--
ALTER TABLE `app_product`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
--
-- 使用表AUTO_INCREMENT `app_product_categoryname`
--
ALTER TABLE `app_product_categoryname`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- 使用表AUTO_INCREMENT `app_product_list`
--
ALTER TABLE `app_product_list`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=431;
--
-- 使用表AUTO_INCREMENT `app_qrcode`
--
ALTER TABLE `app_qrcode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- 使用表AUTO_INCREMENT `app_role`
--
ALTER TABLE `app_role`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK', AUTO_INCREMENT=13;
--
-- 使用表AUTO_INCREMENT `app_rom_appresource`
--
ALTER TABLE `app_rom_appresource`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4316619;
--
-- 使用表AUTO_INCREMENT `app_rom_appupdata`
--
ALTER TABLE `app_rom_appupdata`
  MODIFY `id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47620779;
--
-- 使用表AUTO_INCREMENT `app_rom_appupdatalog`
--
ALTER TABLE `app_rom_appupdatalog`
  MODIFY `id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26696322;
--
-- 使用表AUTO_INCREMENT `app_rom_appupdata_day`
--
ALTER TABLE `app_rom_appupdata_day`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;
--
-- 使用表AUTO_INCREMENT `app_rom_appupdata_dtmark`
--
ALTER TABLE `app_rom_appupdata_dtmark`
  MODIFY `id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=980;
--
-- 使用表AUTO_INCREMENT `app_rom_arrival_count`
--
ALTER TABLE `app_rom_arrival_count`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- 使用表AUTO_INCREMENT `app_rom_boxdata`
--
ALTER TABLE `app_rom_boxdata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- 使用表AUTO_INCREMENT `app_rom_boxdesk`
--
ALTER TABLE `app_rom_boxdesk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210;
--
-- 使用表AUTO_INCREMENT `app_rom_box_package`
--
ALTER TABLE `app_rom_box_package`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- 使用表AUTO_INCREMENT `app_rom_instalcheck`
--
ALTER TABLE `app_rom_instalcheck`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52146;
--
-- 使用表AUTO_INCREMENT `app_rom_log`
--
ALTER TABLE `app_rom_log`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3829;
--
-- 使用表AUTO_INCREMENT `app_rom_package`
--
ALTER TABLE `app_rom_package`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- 使用表AUTO_INCREMENT `app_rom_softbox`
--
ALTER TABLE `app_rom_softbox`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- 使用表AUTO_INCREMENT `app_rom_softpak`
--
ALTER TABLE `app_rom_softpak`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=940;
--
-- 使用表AUTO_INCREMENT `app_rom_softroute`
--
ALTER TABLE `app_rom_softroute`
  MODIFY `id` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- 使用表AUTO_INCREMENT `app_rom_subagentdata`
--
ALTER TABLE `app_rom_subagentdata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- 使用表AUTO_INCREMENT `app_row_softdownload_record`
--
ALTER TABLE `app_row_softdownload_record`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6767;
--
-- 使用表AUTO_INCREMENT `app_salary`
--
ALTER TABLE `app_salary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `app_serachinfo_records`
--
ALTER TABLE `app_serachinfo_records`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=262;
--
-- 使用表AUTO_INCREMENT `app_serach_info`
--
ALTER TABLE `app_serach_info`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=214;
--
-- 使用表AUTO_INCREMENT `app_serach_info_log`
--
ALTER TABLE `app_serach_info_log`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id', AUTO_INCREMENT=9;
--
-- 使用表AUTO_INCREMENT `app_share_count`
--
ALTER TABLE `app_share_count`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=658;
--
-- 使用表AUTO_INCREMENT `app_shop_goods`
--
ALTER TABLE `app_shop_goods`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
--
-- 使用表AUTO_INCREMENT `app_shop_goods_category`
--
ALTER TABLE `app_shop_goods_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- 使用表AUTO_INCREMENT `app_shop_goods_order`
--
ALTER TABLE `app_shop_goods_order`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;
--
-- 使用表AUTO_INCREMENT `app_spread_source`
--
ALTER TABLE `app_spread_source`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `app_subagent_price`
--
ALTER TABLE `app_subagent_price`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- 使用表AUTO_INCREMENT `app_system_log`
--
ALTER TABLE `app_system_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67344;
--
-- 使用表AUTO_INCREMENT `app_task`
--
ALTER TABLE `app_task`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2439;
--
-- 使用表AUTO_INCREMENT `app_task_earnings`
--
ALTER TABLE `app_task_earnings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;
--
-- 使用表AUTO_INCREMENT `app_task_week_earnings`
--
ALTER TABLE `app_task_week_earnings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `app_task_when`
--
ALTER TABLE `app_task_when`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2439;
--
-- 使用表AUTO_INCREMENT `app_week_task`
--
ALTER TABLE `app_week_task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `app_weixin_token`
--
ALTER TABLE `app_weixin_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4370;
--
-- 使用表AUTO_INCREMENT `app_yearend_draw`
--
ALTER TABLE `app_yearend_draw`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;
--
-- 使用表AUTO_INCREMENT `ele_advisory_records`
--
ALTER TABLE `ele_advisory_records`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ele_eda_cpu`
--
ALTER TABLE `ele_eda_cpu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ele_eda_process`
--
ALTER TABLE `ele_eda_process`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
