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

/**
 * @param $req_method   请求方式，分为GET和POST
 * @param $param        传入模板的数组，这里按值传递
 */
function poster($req_method,&$param,$method){
    if($req_method=='POST'){
         foreach($_POST as $k => $v){
            if(existParam($method,$k))           //如果这个方法请求这个参数，我们就传递，否则不传
            {
                $param[$k]=$v;
            }
         }
    }
}
function existParam($method,$param)
{
    foreach($method->getParameters() as $paramter)   //获取反射方法
    {
        if($paramter->name==$param)
        {
            return true;
        }
        return false;
    }
}

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
            $getMethod=$class_obj->getMethod($method);      //获得需要调用的反射类下的对应方法

            poster($_SERVER['REQUEST_METHOD'],$param,$getMethod);  //把请求的变量都存进这个数组,还要判断方法内是否要取这个值
            $param['display']=$display;
            $class_obj_instance=$class_obj->newInstance();      //实例化对象
            $getMethod->invokeArgs($class_obj_instance,$param); //这个方法的参数以数组的方式传递
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
