<?php
/**
 * Created by PhpStorm.
 * User: red
 * Date: 10/12/16
 * Time: 12:28 PM
 */
namespace core;
use core\frame\mark_frame;
class markInit
{
    static $prj_name='';        //静态变量必须提前初始化
    static $prj_author='';
    
    static $v='mark version is 1.1';
    static function init(){
        echo "input your project name:".PHP_EOL;
        self::$prj_name=fgets(STDIN);
//        exit(self::$prj_name);
        //不允许使用mark作为项目名称，无限循环
        while(preg_match('/^mark$/i',self::$prj_name)){
            echo "Failed!input your project name:".PHP_EOL;
            self::$prj_name=fgets(STDIN);
        }
        echo "input author name:".PHP_EOL;
        self::$prj_author=fgets(STDIN);
        return json_encode(TC3(array("prj_name"=>self::$prj_name,"prj_author"=>self::$prj_author)));
    }
    static function info()
    {
        $obj=loadConfig();
        foreach($obj as $key=>$value)
        {
            echo $key.'='.$value.PHP_EOL;
        }
    }
    static function start()
    {
        if(file_exists(getcwd().'/mark.json'))
        {
            if(is_object($init=loadConfig()))
            {
                $mf=new mark_frame($init->prj_name);
                $mf->project_name=$init->prj_name;
                $mf->project_author=$init->prj_author;
                $mf->run();
            }
        }
        else
        {
            return 'config file does not exist!';
        }

    }
}