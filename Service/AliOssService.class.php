<?php
/**
 * Created by PhpStorm.
 * User: jaren
 * Date: 2017/12/23
 * Time: 20:14
 */

namespace Oss\Service;

use OSS\Core\OssException;
use OSS\OssClient;

require_once dirname(__DIR__) . '/Lib/AliyunOss/autoload.php';

class AliOssService {

    static $client = null;

    static $AK          = null;
    static $SK          = null;
    static $endpoint    = null;
    static $expire_time = 1800;

    static function init($expire_time = 1800) {
        $config            = M('oss_aliyun')->find();
        self::$AK          = $config[ 'ak' ];
        self::$SK          = $config[ 'sk' ];
        self::$endpoint    = $config[ 'endpoint' ];
        self::$expire_time = $expire_time;
    }

    static function getClient($bucket) {
        if (empty(self::$client)) {
            try {
                $endpoint     = 'http://' . $bucket . '.' . self::$endpoint;
                self::$client = new OssClient(self::$AK, self::$SK, $endpoint);
            } catch (OssException $e) {
                exit($e->getMessage());
            }
        }
        return self::$client;
    }

    static function getBucket($bucket) {
        $endpoint  = 'http://' . $bucket . '.' . self::$endpoint;
        $ossClient = self::getClient($endpoint);
        return $ossClient->createBucket($bucket);
    }

    static function gmt_iso8601($time) {
        $dtStr      = date("c", $time);
        $mydatetime = new \DateTime($dtStr);
        $expiration = $mydatetime->format(\DateTime::ISO8601);
        $pos        = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration . "Z";
    }

    static function getToken($bucket, $dir) {
        $end        = time() + self::$expire_time;
        $expiration = self::gmt_iso8601($end);

        //最大文件大小.用户可以自己设置
        $condition    = [0 => 'content-length-range', 1 => 0, 2 => 1048576000];
        $conditions[] = $condition;

        //用户上传数据的位置匹配,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
        $start       = [0 => 'starts-with', 1 => '$key', 2 => $dir];
        $condition[] = $start;

        $arr = ['expiration' => $expiration, 'conditions' => $conditions];

        $policy         = json_encode($arr);
        $base64_policy  = base64_encode($policy);
        $string_to_sign = $base64_policy;
        $signature      = base64_encode(hash_hmac('sha1', $string_to_sign, self::$SK, true));

        $endpoint   = 'http://' . $bucket . '.' . self::$endpoint;

        $response                = [];
        $response[ 'accessid' ]  = self::$AK;
        $response[ 'host' ]      = $endpoint;
        $response[ 'policy' ]    = $base64_policy;
        $response[ 'signature' ] = $signature;
        $response[ 'expire' ]    = $end;
        $response[ 'dir' ]       = $dir . '${filename}';

        return $response;
    }

}