<?php
/**
 * Created by PhpStorm.
 * User: red
 * Date: 10/10/15
 * Time: 2:11 PM
 */
class markinit
{
    static $VERSION='mark version is 1.1';
    static $prj_name="";    //project name
    static $prj_author="";    //project author
    static function init()
    {
        echo 'input your project name?'.PHP_EOL;
        self::$prj_name=fgets(STDIN);

        echo 'input author name?'.PHP_EOL;
        self::$prj_author=fgets(STDIN);

        $content='"name":"'.self::$prj_name.'","author":"'.self::$prj_author.'"';

        echo "information collected like follows:".PHP_EOL;
        echo self::$prj_name.PHP_EOL;
        echo self::$prj_author.PHP_EOL;
        return $content;
    }
    static function make()
    {
        $pchar=new Phar("mark.phar");
        $pchar->buildFromDirectory(dirname(__FILE__));
        $pchar->setStub($pchar->createDefaultStub('mark'));     //entrance
        $pchar->compressFiles(Phar::GZ);
    }
}