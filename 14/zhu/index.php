<?php   //加一个杠是为了防止我们include这个文件的时候被php执行
/*
 * project name:zhu
 * User:test
 * Date:2016-10-11 09:38:53
 */

require('vars.php');
require('functions.php');

$pi=isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:false;
if(!$pi)exit(404);
$route=require('request_route');
//if(array_key_exists($pi,$route))
//{
    $route_keys=array_keys($route);
    foreach($route_keys as $key)
    {
        $new_key=str_replace('/','\/',$key);
        if(preg_match('/'.$new_key.'/',$pi))
        {
            $route_obj=$route[$key];
            if($route_obj['RequestMethod']==$_SERVER['REQUEST_METHOD'])
            {
                $className=$route_obj['Class'];
                $method=$route_obj['Method'];
                require('code/'.$className.'.class.php');
                $class_obj=new $className();
                $class_obj->$method();
                exit;
            }
            else
            {
                exit('not allowed!405');
            }
        }


//    }
}

