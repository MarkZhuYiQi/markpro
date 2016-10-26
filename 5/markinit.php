<?php
/**
 * Created by PhpStorm.
 * User: red
 * Date: 10/10/15
 * Time: 2:11 PM
 */
require 'markconfig.php';
class markinit
{
    static $v = 'mark version is 1.2';

    static function init()
    {
        echo 'input your project name?' . PHP_EOL;
        $prj_name=fgets(STDIN);
        echo 'input author name?' . PHP_EOL;
        $prj_author=fgets(STDIN);
        //第一种方法，利用顶层基类创建临时类用于存放变量，返回类
//        return json_encode(TC1(array('prj_name'=>$prj_name,'prj_author'=>$prj_author)));
        //第二种方法，直接把数组转换成类
        return json_encode(TC3(array('prj_name'=>$prj_name,'prj_author'=>$prj_author)));
    }

    static function make()
    {
        $pchar = new Phar("mark.phar");
        $pchar->buildFromDirectory(dirname(__FILE__));
        $pchar->setStub($pchar->createDefaultStub('mark'));     //entrance
        $pchar->compressFiles(Phar::GZ);
    }
    //魔术方法，当调用一个不可用的静态方法时调用
    static function __callStatic($p1,$p2){      //第一个是方法名，第二个是方法参数
        echo 'error function!';
    }
}