CREATE TABLE `cms_oss_operator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '平台名',
  `tablename` varchar(255) NOT NULL DEFAULT '' COMMENT '表名',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '平台备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

INSERT INTO `cms_oss_operator` VALUES ('1', '阿里云OSS', 'oss_aliyun', '阿里云OSS');
INSERT INTO `cms_oss_operator` VALUES ('2', '七牛云存储', 'oss_qiniu', '七牛云存储');


CREATE TABLE `cms_oss_qiniu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ak` varchar(255) NOT NULL DEFAULT '' COMMENT 'AccessKeyID ',
  `sk` varchar(255) NOT NULL DEFAULT '' COMMENT 'AccessKeySecret ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `cms_oss_qiniu` VALUES ('1', '', '');


CREATE TABLE `cms_oss_aliyun` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ak` varchar(255) NOT NULL DEFAULT '' COMMENT 'AccessKeyID',
  `sk` varchar(255) NOT NULL DEFAULT '' COMMENT 'AccessKeySecret',
  `endpoint` varchar(255) NOT NULL DEFAULT '' COMMENT 'endpoint',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `cms_oss_aliyun` VALUES ('1', '', '','');