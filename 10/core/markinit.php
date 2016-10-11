<?php
/**
 * Created by PhpStorm.
 * User: red
 * Date: 10/10/16
 * Time: 2:11 PM
 */
namespace core;

use core\frame\mark_frame;
define('CSTRING','json');
//require 'markconfig.php';
define('SPACE',['\r','\n','\r\n']);     //php7新特性，支持定义常量数组

function __autoload($className)
{
    $className=str_replace('\\','/',$className);
    if(file_exists(getcwd().'/'.$className.'.php'))
    {
        require(getcwd().'/'.$className.'.php');
    }
}

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
        return TC3(array('prj_name'=>$prj_name,'prj_author'=>$prj_author));
    }

    static function make()
    {
        $pchar = new Phar("mark.phar");
        $pchar->buildFromDirectory(dirname(__FILE__));
        $pchar->setStub($pchar->createDefaultStub('mark'));     //entrance
        $pchar->compressFiles(Phar::GZ);
    }

    static function ini()
    {
        $get_config=loadConfig();
        foreach($get_config as $key => $value){
            echo $key.' = '.$value.PHP_EOL;
        }
    }
    static function start()
    {
        if (!file_exists('mark.json')) return 'Error! json file does not exist!';
        if (is_object($get_config = loadConfig())) {
            $mf=new mark_frame($get_config->prj_name);
            //$this输出的是自己被实例化的那个类中的所有值，所以在这里设置类变量，一样可以得到这些变量
            $mf->prj_name=$get_config->prj_name;
            $mf->prj_author=$get_config->prj_author;
            return $mf->run();
        }else{
            return 'Error!data unknown!';
        }
    }
    static function C(){
        $get_config=loadConfig();
        $mf=new mark_frame($get_config->prj_name);
        $mf->compile();

    }
    //魔术方法，当调用一个不可用的静态方法时调用
    static function __callStatic($p1,$p2){      //第一个是方法名，第二个是方法参数
        echo 'error function!';
    }
}