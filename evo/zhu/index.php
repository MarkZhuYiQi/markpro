<?php
/**
 * project name: zhu
 * User: mark
 * Date:2016-11-07 17:24:59
 */
require('func.php');
require('vars.php');
if(isset($_SERVER['PATH_INFO']))
{
    //获取path_info
    $p=$_SERVER['PATH_INFO'];
    //获取路由数组
    $route=require('request_route');
    //获取路由数组的键
    $paths=array_keys($route);
    foreach($paths as $path)
    {
        //替换path_info中的斜杠，防止被解析
        $cp=str_replace('/','\/',$path);
        if(preg_match("/".$cp."/",$p,$result))
        {
            $route_arr=$route[$path];
            if($route_arr['RequestMethod']==$_SERVER['REQUEST_METHOD'])
            {
                $className=$route_arr['Class'];
                $method=$route_arr['Method'];
                $result=array_filter($result,'getMatch',ARRAY_FILTER_USE_KEY);
                require('code/'.$className.'.class.php');
                $res_obj=new ReflectionClass($className);
                $getMethod=$res_obj->getMethod($method);
                //执行一个方法，需要传入实例化对象，实例化对象也可以通过反射实现
                $getMethod->invoke($res_obj->newInstance());
            }
            else
            {
                exit('请求姿势不对！');
            }
        }
    }
}
else
{
    exit(404);
}