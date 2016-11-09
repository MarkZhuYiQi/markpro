<?php
/**
 * project name: zhu
 * User: mark
 * Date:2016-11-07 17:24:59
 */
require('func.php');
require('vars.php');

$display=function($tpl,$vars=array())
{
    require('vars.php');
    extract($vars);
    include('page/'.$tpl.'.html');
};


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
            if($route_arr['RequestMethod']==$_SERVER['REQUEST_METHOD']) {
                $className = $route_arr['Class'];
                $method = $route_arr['Method'];
                //这里头存放了带键名的数组
                $result = array_filter($result, 'getMatch', ARRAY_FILTER_USE_KEY);
                require('code/' . $className . '.class.php');
                $res_obj = new ReflectionClass($className);
                $getMethod = $res_obj->getMethod($method);
                $class_obj = $res_obj->newInstance();
                poster($getMethod,$result);
                //强制让数组带上这个display，并且总是在最后一个
                $result['display'] = $display;
                $getMethod->invokeArgs($class_obj, $result);
            }
            else
            {
                var_export($_SERVER['REQUEST_METHOD']);
                var_export($route_arr['RequestMethod']);
                exit('请求姿势不对！');
            }
        }
    }
}
else
{
    exit(404);
}


