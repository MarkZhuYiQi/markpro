<?php
/*
 * project name:zhu
 * project author:test
 * Date:2016-10-21 01:47:26
 */
require('vars.php');
require('functions.php');

$pi=isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:false;
if(!$pi)exit(404);
$route=require('request_route');
var_export($route);
if(array_key_exists($pi,$route))
{
    $route_obj=$route[$pi];
    if($route_obj['RequestMethod']==$_SERVER['REQUEST_METHOD'])
    {
        $className=$route['Class'];
        $method=$route_obj['Method'];
        require('code/'.$className.'.class.php');
        $class_obj=new $className();
        $class_obj->$method();
    }
    else
    {
        exit('not allowed!405');
    }
}






