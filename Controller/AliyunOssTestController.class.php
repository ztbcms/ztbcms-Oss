<?php
/**
 * Created by PhpStorm.
 * User: jaren
 * Date: 2017/12/24
 * Time: 14:37
 */

namespace Oss\Controller;

use Think\Controller;

class AliyunOssTestController extends Controller {

    private $AK          = '';
    private $SK          = '';
    private $endpoint    = '';
    private $expire_time = 1800;

    function index() {
        $this->display();
    }

    function gmt_iso8601($time) {
        $dtStr      = date("c", $time);
        $mydatetime = new \DateTime($dtStr);
        $expiration = $mydatetime->format(\DateTime::ISO8601);
        $pos        = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration . "Z";
    }

    function getToken() {
        $end        = time() + $this->expire_time;
        $expiration = $this->gmt_iso8601($end);

        //最大文件大小.用户可以自己设置
        $condition    = [0 => 'content-length-range', 1 => 0, 2 => 1048576000];
        $conditions[] = $condition;

        $dir = 'ztb-open/';
        //用户上传数据的位置匹配,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
        $start       = [0 => 'starts-with', 1 => '$key', 2 => $dir];
        $condition[] = $start;

        $arr = ['expiration' => $expiration, 'conditions' => $conditions];
        //echo json_encode($arr);
        //return;
        $policy         = json_encode($arr);
        $base64_policy  = base64_encode($policy);
        $string_to_sign = $base64_policy;
        $signature      = base64_encode(hash_hmac('sha1', $string_to_sign, $this->SK, true));

        $response                = [];
        $response[ 'accessid' ]  = $this->AK;
        $response[ 'host' ]      = $this->endpoint;
        $response[ 'policy' ]    = $base64_policy;
        $response[ 'signature' ] = $signature;
        $response[ 'expire' ]    = $end;
        $response[ 'dir' ]       = $dir . '${filename}';


        $this->ajaxReturn($response);
    }
}