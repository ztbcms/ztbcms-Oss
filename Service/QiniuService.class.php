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

    static $AK          = null;
    static $SK          = null;
    static $expire_time = 18000;


    static function init($expire_time = 1800) {
        $config            = M('oss_qiniu')->find();
        self::$AK          = $config[ 'ak' ];
        self::$SK          = $config[ 'sk' ];
        self::$expire_time = $expire_time;
    }

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
     * @param $bucket
     * @param $domain
     *
     * @return mixed
     */
    static function getToken($bucket, $domain) {
        if (empty(self::$token[ $bucket ])) {
            // 生成上传Token
            $auth                   = self::getAuth(self::$AK, self::$SK);
            $policy                 = [
                'returnBody' => json_encode(['url' => $domain . '$(key)']),
            ];
            self::$token[ $bucket ] = $auth->uploadToken($bucket, null, self::$expire_time, $policy);
        }
        
        return [
            'token'       => self::$token[ $bucket ],
            'expire_time' => time() + self::$expire_time,
        ];
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


    static function getBucketManager() {
        if (empty(self::$bucketManager)) {
            $auth                = self::getAuth(self::$AK, self::$SK);
            self::$bucketManager = new BucketManager($auth);
        }
        return self::$bucketManager;
    }


    static function getPfop($bucket, $pipeline) {
        if (empty(self::$pfop[ $bucket ][ $pipeline ])) {
            $auth                               = self::getAuth(self::$AK, self::$SK);
            self::$pfop[ $bucket ][ $pipeline ] = new PersistentFop($auth, $bucket, $pipeline);
        }
        return self::$pfop[ $bucket ][ $pipeline ];
    }
}
