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

    private $AK = '';
    private $SK = '';

    function index() {
        $token = QiniuService::getToken($this->AK, $this->SK, 'maibao');
        $this->assign('token', $token);
        $this->display();
    }
}