<?php
/*
 * project name:zhu
 * project author:test
 * Date:2016-10-21 01:47:26
 */
require('functions.php');
//首先判断$_SERVER['PATH_INFO']是否存在，如果不存在就直接退出了，存在继续执行
$pi=isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:false;
if(!$pi)exit(404);

$display = function($tpl='',$vars=array()){      //这是V，模板解析函数
    extract($vars);
    require('vars.php');
    if($tpl!='')include('page/'.$tpl.'.html');
};

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
            $getMethod=$class_obj->getMethod($classMethod);     //获得反射类中的那个方法
            $class_obj_instance=$class_obj->newInstance();          //实例化反射出来的类
            geter($_SERVER['REQUEST_METHOD'],$params,$getMethod);
            poster($_SERVER['REQUEST_METHOD'],$params,$getMethod);   //函数判断如果是post方法，讲post变量都传入数组
            $params['display']=$display;                        //将匿名函数传给数组作为参数
            $getMethod->invokeArgs($class_obj_instance,$params);    //带参数的调用类中的方法
        }else{
            exit('request method not allowed!405');
        }
    }
}
/**
 * 用于辅助array_filter(),回调函数
 * @param $v
 * @return int
 */
function getMatch($v)
{
    return preg_match('/[a-zA-Z]+/',$v);
}

/**
 * 如果get请求传入的变量在类方法中病没有接受，那么就删除这个变量
 * @param $requestMethod    请求方法
 * @param $params           get请求获得的变量存入数组$params
 * @param $method           反射出来的类方法
 */
function geter($requestMethod,&$params,$method)
{
    if($requestMethod=='GET')
    {
        foreach($params as $key => $value)
        {
            if(!existParam($method,$key))
            {
                unset($params[$key]);
            }
        }
    }
}

function poster($requestMethod,&$params,$method)
{
    if($requestMethod=='POST')
    {
        foreach($_POST as $key =>$value)
        {
            if(existParam($method,$key))
            {
                $params[$key]=$value;
            }
        }
    }
}
function existParam($method,$key)
{
    foreach($method->getParameters() as $parameter)
    {
        if($parameter->name==$key){
            return true;
        }
    }
    return false;
}