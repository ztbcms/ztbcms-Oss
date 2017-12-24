<?php
/**
 * Created by PhpStorm.
 * User: jaren
 * Date: 2017/12/23
 * Time: 20:36
 */

namespace Oss\Service;

require_once dirname(__DIR__) . '/Lib/Qiniu/autoload.php';

use Qiniu\Auth;
use Qiniu\Processing\PersistentFop;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

class QiniuService {

    static $auth = null;

    static $token = [];

    static $uploader = null;

    static $bucketManager = null;

    static $pfop = [];

    /**
     * 获取 auth 对象
     *
     * @param $accessKey
     * @param $secretKey
     *
     * @return null|\Qiniu\Auth
     */
    static function getAuth($accessKey, $secretKey) {
        if (empty(self::$auth)) {
            self::$auth = new Auth($accessKey, $secretKey);
        }
        return self::$auth;
    }

    /**
     * 获取上传凭证
     *
     * @param $accessKey
     * @param $secretKey
     * @param $bucket
     *
     * @return mixed
     */
    static function getToken($accessKey, $secretKey, $bucket) {
        if (empty(self::$token[ $bucket ])) {
            // 生成上传Token
            $auth                   = self::getAuth($accessKey, $secretKey);
            self::$token[ $bucket ] = $auth->uploadToken($bucket);
        }
        return self::$token[ $bucket ];
    }

    /**
     * 获取上传类
     *
     * @return null|\Qiniu\Storage\UploadManager
     */
    static function getUploader() {
        if (empty(self::$uploader)) {
            self::$uploader = new UploadManager();
        }
        return self::$uploader;
    }


    static function getBucketManager($accessKey, $secretKey) {
        if (empty(self::$bucketManager)) {
            $auth                = self::getAuth($accessKey, $secretKey);
            self::$bucketManager = new BucketManager($auth);
        }
        return self::$bucketManager;
    }


    static function getPfop($accessKey, $secretKey, $bucket, $pipeline) {
        if (empty(self::$pfop[ $bucket ][ $pipeline ])) {
            $auth                               = self::getAuth($accessKey, $secretKey);
            self::$pfop[ $bucket ][ $pipeline ] = new PersistentFop($auth, $bucket, $pipeline);
        }
        return self::$pfop[ $bucket ][ $pipeline ];
    }
}
