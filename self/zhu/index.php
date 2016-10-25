<?php
/*
 * project name:zhu
 * project author:test
 * Date:2016-10-21 01:47:26
 */
require('vars.php');
require('functions.php');
//首先判断$_SERVER['PATH_INFO']是否存在，如果不存在就直接退出了，存在继续执行
$pi=isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:false;
if(!$pi)exit(404);
$route=require('request_route');
//获得route数组里面的所有键
$route_keys=array_keys($route);
//遍历键，将其中的正则取出来做替换
foreach($route_keys as $key)
{
    $new_key=str_replace('/','\/',$key);
    if(preg_match('/'.$new_key.'/',$pi,$result))
    {
        $route_obj=$route[$key];
        if($route_obj['RequestMethod']==$_SERVER['REQUEST_METHOD'])
        {
            $params=array_filter($result, 'getMatch', ARRAY_FILTER_USE_KEY);
            $className=$route_obj['class'];
            $classMethod=$route_obj['Method'];
            require ('code/'.$className.'.class.php');
            $class_obj=new ReflectionClass($className);
            $getMethod=$class_obj->getMethod($classMethod);
            if($params && count($params)>0)
            {
                $getMethod->invokeArgs($class_obj->newInstance(),$params);
            }
            else
            {
                $getMethod->invoke($class_obj->newInstance());
            }
        }
    }
}

function getMatch($v)
{
    return preg_match('/[a-zA-Z]+/',$v);
}

