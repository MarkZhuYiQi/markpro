<?php
/**
 * Created by PhpStorm.
 * User: red
 * Date: 10/10/16
 * Time: 2:11 PM
 */
require 'markconfig.php';
class markinit
{
    static $v = 'mark version is 1.2';
//    static $prj_name = "";    //project name
//    static $prj_author = "";    //project author

    static function init()
    {
        $gc=new markconfig();
        echo 'input your project name?' . PHP_EOL;
//        self::$prj_name = fgets(STDIN);
        $gc->prj_name=fgets(STDIN);
        echo 'input author name?' . PHP_EOL;
//        self::$prj_author = fgets(STDIN);
        $gc->prj_author=fgets(STDIN);
        return json_encode($gc);
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