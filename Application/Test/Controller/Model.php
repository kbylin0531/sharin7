<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 16-10-19
 * Time: 下午2:04
 */

namespace Application\Test\Controller;

use Application\Test\Model\MemberModel;
use Sharin\Exceptions\PropertyInvalidException;

class Model {

    public function lists()
    {

        $model = new MemberModel();
        $list = [];
        $list['0'] = $model->lists(0);
        $list['1'] = $model->lists(1);
        $list['default'] = $model->lists();
        \Sharin\dumpout($list);

    }

    public function add(){
        $model = new MemberModel();
        try {
            $model->email = '1797290103@qq.com';
            $model->nickname = 'Baishou';
            $model->passwd = md5(sha1('123456'));
            $model->profile = '';
            $model->sex = 1;
            $model->status = 1;
//            $model->FIELD_NOT_EXIST = '323232';//会抛出异常并被捕获
        }catch (PropertyInvalidException $e) {
            \Sharin\dumpout($e->getMessage());
        }
        $result = $model->add();
        \Sharin\dumpout($result,$model->error());
    }

}