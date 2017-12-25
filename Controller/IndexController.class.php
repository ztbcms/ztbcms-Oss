<?php
/**
 * Created by PhpStorm.
 * User: jaren
 * Date: 2017/12/23
 * Time: 20:10
 */

namespace Oss\Controller;

use Common\Controller\AdminBase;
use Think\Controller;

class IndexController extends AdminBase {

    protected $operatorModel;

    //初始化
    protected function _initialize() {
        parent::_initialize();
        $this->operatorModel = M('ossOperator');
    }

    /**
     * 展示平台列表
     */
    public function operators() {
        $this->display();
    }

    /**
     * 获取短信平台
     */
    public function get_operators() {

        $operators = $this->operatorModel->select();

        $error = $this->operatorModel->getError();
        empty($error) ? $result[ 'status' ] = true : $result[ 'status' ] = false;
        $result[ 'error' ]                = $error;
        $result[ 'datas' ][ 'count' ]     = count($operators);
        $result[ 'datas' ][ 'operators' ] = $operators;

        $this->ajaxReturn($result);
    }


    /**
     * 展示平台设置
     */
    public function modules() {
        $this->display();
    }

    /**
     * 获取记录及字段详细信息
     */
    public function get_modules() {

        //获取表字段
        $tablename = C('DB_PREFIX') . I('get.operator', "", "trim");
        $Model     = new \Think\Model();
        $fields    = $Model->query("show full fields from $tablename");
        unset($fields[ 0 ]);

        //获取记录
        $tablename = I('get.operator', "", "trim");
        $Model     = M($tablename);
        if (I('get.id')) {
            $modules = $Model->find(I('get.id'));
        } else {
            $modules = $Model->select();
        }

        $result = [
            'status' => true,
            'datas'  => [
                'operator' => $this->operatorModel->where("tablename='%s'", I('get.operator'))->find(),
                'fields'   => $fields,
                'modules'  => $modules,
            ],
        ];

        $this->ajaxReturn($result);
    }

    /**
     * 获取表字段详细信息
     */
    public function get_fields() {
        //获取表字段
        $tablename = C('DB_PREFIX') . "sms_" . I('get.operator', "", "trim");
        $Model     = new \Think\Model();
        $fields    = $Model->query("show full fields from $tablename");
        unset($fields[ 0 ]);

        $result = [
            'status' => true,
            'datas'  => [
                'operator' => $this->operatorModel->where("tablename='%s'", I('get.operator'))->find(),
                'fields'   => $fields,
            ],
        ];

        $this->ajaxReturn($result);
    }


    /**
     * 编辑参数
     */
    public function save() {
        $data  = I('post.');
        $table = $data[ 'operator' ];
        unset($data[ 'operator' ]);
        $res = M($table)->save($data);
        if ($res || empty(M()->getError())) {
            $this->ajaxReturn(self::createReturn(true, [], '保存成功'));
        } else {
            $this->ajaxReturn(self::createReturn(true, [], M()->getError()));
        }
    }
}