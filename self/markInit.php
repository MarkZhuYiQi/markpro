<?php
/**
 * Created by PhpStorm.
 * User: red
 * Date: 10/12/16
 * Time: 12:28 PM
 */
class markInit
{
    static $prj_name='';        //静态变量必须提前初始化
    static $prj_author='';
    
    static $v='mark version is 1.1';
    static function init(){
        echo "input your project name:".PHP_EOL;
        self::$prj_name=fgets(STDIN);
        echo "input author name:".PHP_EOL;
        self::$prj_author=fgets(STDIN);
        return json_encode(TC1(array("prj_name"=>self::$prj_name,"prj_author"=>self::$prj_author)));
    }
    static function info()
    {
        $obj=loadConfig();
        foreach($obj as $key=>$value)
        {
            echo $key.'='.$value.PHP_EOL;
        }
    }
}