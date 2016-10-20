<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 16-10-17
 * Time: 下午3:19
 */
return [
    'rules' => [
        'begin_with'    => [
            '/assets'   => function($assets){
                if($pos = strpos($assets,'?')){
                    $assets = substr($assets,0,$pos);
                }
                return SR_PATH_APP.'/Template/'.$assets;
            },
        ],
    ],
];