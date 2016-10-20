<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 16-10-20
 * Time: 下午4:48
 */

namespace Application\Test\Controller;


use Sharin\Exception;

class SQLite {

    public function testCreate(){
        \Sharin\dump('CHECK INSTALLED:',\Sharin\Library\SQLite::isInstalled('a'));
        try{
            \Sharin\dump('DO INSTALL:',\Sharin\Library\SQLite::install('a'));
        }catch (Exception $e) {
            \Sharin\dump('ERROR:',$e->getMessage());
        }
    }

    public function testIO(){
        $instance = \Sharin\Library\SQLite::getInstance('a');
        \Sharin\dump(
            $instance->create([
                'ID'   => '1231',
                'NAME' => 'dadsadsa'
            ]),
            $instance->select('ID = 1231')

        );
    }

}