<?php
/**
 * Created by PhpStorm.
 * User: jaren
 * Date: 2017/12/23
 * Time: 23:06
 */

namespace Oss\Controller;

use Oss\Service\QiniuService;
use Think\Controller;

class QiniuTestController extends Controller {

    function index() {
        $this->display();
    }

    function getToken() {
        $expire_time = 1800;
        QiniuService::init($expire_time);

        $bucket = 'maibao';//指定存储空间名
        //此参数在存储空间中的内容管理中配置获取，详细参见文档
        $domain = 'http://ojclqkqcg.bkt.clouddn.com/';//存储空间外网访问链接，需以/结尾
        $this->ajaxReturn(QiniuService::getToken($bucket, $domain));
    }
}