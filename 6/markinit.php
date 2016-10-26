<?php
/**
 * Created by PhpStorm.
 * User: red
 * Date: 10/10/15
 * Time: 2:11 PM
 */
define('CSTRING','json');
require 'markconfig.php';
define('SPACE',['\r','\n','\r\n']);     //php7新特性，支持定义常量数组
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
        if (is_object($obj = loadConfig())) {
            !(file_exists(getcwd().'/'.$obj->prj_name)&&is_dir(getcwd().'/'.$obj->prj_name)) && mkdir(getcwd() . '/' . $obj->prj_name, 0777);
            !file_exists(getcwd().'/'.$obj->prj_name.'/index.php') && file_put_contents(getcwd().'/'.$obj->prj_name.'/index.php', '<?php'.PHP_EOL.'//project name:'.$obj->prj_name.PHP_EOL.'//project author:'.$obj->prj_author.PHP_EOL);
//            if (!is_dir($obj->prj_name)) {
//                mkdir(getcwd() . '/' . $obj->prj_name, 0777);
//            }else{
//                return 'Error! '.$obj->prj_name.' dir already exists!';
//            }
//            if(file_exists(getcwd().'/'.$obj->prj_name.'/index.php'))return 'Error! index.php already exists!';
//            file_put_contents(getcwd().'/'.$obj->prj_name.'/index.php',
//                '<?php'.PHP_EOL.'//project name:'.$obj->prj_name.PHP_EOL.'//project author:'.$obj->prj_author.PHP_EOL);
            return 'dir catallog has been established!';
        }else{
            return 'Error!data unknown!';
        }
    }
    //魔术方法，当调用一个不可用的静态方法时调用
    static function __callStatic($p1,$p2){      //第一个是方法名，第二个是方法参数
        echo 'error function!';
    }
}