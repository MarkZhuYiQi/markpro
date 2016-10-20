<?php   //加一个杠是为了防止我们include这个文件的时候被php执行
/*
 * project name:zhu
 * User:test
 * Date:2016-10-11 09:38:53
 */

require('functions.php');
require('vars.php');
$p = $_SERVER['PATH_INFO'];
$controller=explode('/',$p)[1];
$method=explode('/',$p)[2];
require('code/'.$controller.'.class.php');
$f=new ReflectionClass($controller);
if(preg_match("/@Controller/",$f->getDocComment()))
{
    echo "controller";
}
else
{
    echo 'not controller';
}