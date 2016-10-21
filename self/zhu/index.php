<?php
/*
 * project name:zhu
 * project author:test
 * Date:2016-10-21 01:47:26
 */
$route=require('request_route');
if($pathInfo=$_SERVER['PATH_INFO'])
{
    require('code/'.$route[$pathInfo]['class'].'.class.php');
    $controller=new $route[$pathInfo]['class'];
    $action=$route[$pathInfo]['Method'];
    $controller->$action();
}


