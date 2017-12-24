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
    static $bucket = null;

    static function getClient($accessKeyId, $accessKeySecret, $endpoint) {
        if (empty(self::$client)) {
            try {
                self::$client = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            } catch (OssException $e) {
                exit($e->getMessage());
            }
        }
        return self::$client;
    }


    static function getBucket($accessKeyId, $accessKeySecret, $endpoint, $bucket) {
        if (empty(self::$bucket)) {
            $ossClient    = self::getClient($accessKeyId, $accessKeySecret, $endpoint);
            self::$bucket = $ossClient->createBucket($bucket);
        }
        return self::$bucket;
    }

}