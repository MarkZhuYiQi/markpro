<?php   //加一个杠是为了防止我们include这个文件的时候被php执行
/*
 * project name:zhu
 * User:test
 * Date:2016-10-11 09:38:53
 */

require('functions.php');

$display=function($tpl='',$vars=array()){        //模板加载函数
    extract($vars);
    require('vars.php');
    if($tpl!='')include('page/'.$tpl.'.html');
};



$pi=isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:false;
if(!$pi)exit(404);
$route=require('request_route');
$route_keys=array_keys($route);
foreach($route_keys as $key)
{
    $new_key=str_replace('/','\/',$key);
    if(preg_match('/'.$new_key.'/',$pi,$result))
    {
        $route_obj=$route[$key];
        if($route_obj['RequestMethod']==$_SERVER['REQUEST_METHOD'])
        {
            $className=$route_obj['Class'];
            $method=$route_obj['Method'];
            require('code/'.$className.'.class.php');

            $param=array_filter($result,'getMatch',ARRAY_FILTER_USE_KEY);
            $class_obj=new ReflectionClass($className);
            $getMethod=$class_obj->getMethod($method);

            $param['display']=$display;
            $class_obj_instance=$class_obj->newInstance();  //实例化对象
//            if($param && count($param) >0)
//            {
                $getMethod->invokeArgs($class_obj_instance,$param);
//            }
//            else
//            {
//                $getMethod->invoke($class_obj->newInstance());
//            }
        }
        else
        {
            exit('request method not allowed!405');
        }
    }
}

function getMatch($v)
{
    return preg_match('/[a-zA-Z]+/',$v);
}
