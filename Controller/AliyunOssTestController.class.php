<?php
/**
 * Created by PhpStorm.
 * User: jaren
 * Date: 2017/12/24
 * Time: 14:37
 */

namespace Oss\Controller;

use Oss\Service\AliOssService;
use Think\Controller;

class AliyunOssTestController extends Controller {

    function index() {
        $this->display();
    }

    function getToken() {
        $expire_time = 1800;
        AliOssService::init($expire_time);

        $bucket = 'ztb-open'; //存储空间名
        $dir    = 'ztb-open/';//文件前缀，如果以/结尾，则新建目录
        $this->ajaxReturn(AliOssService::getToken($bucket, $dir));
    }
}