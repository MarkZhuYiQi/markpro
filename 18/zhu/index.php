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
        if($_SERVER['CONTENT_TYPE']=='application/json')    //这个值是通过前端的文件头过来的，值是我定的
        {
            $getObj=json_decode(file_get_contents("php://input"));
            foreach($getObj as $key =>$value)
            {
                if(existParam($method,$key))
                {
                    $param[$key]=$value;
                }
            }
            return;
        }
         foreach($_POST as $k => $v){
            if(existParam($method,$k))           //如果这个方法请求这个参数，我们就传递，否则不传
            {
                $param[$k]=$v;
            }
         }
    }
}

/**
 * @param $method       所请求的方法对象
 * @param $key          post请求的键
 * @return bool
 * 我日你妈卖批的这个return false 写在循环里面会导致第一次循环就结束循环，必须特么的仍在外面。我日，还是太嫩了！2016年10月27日
 */
function existParam($method,$key)
{
//    var_export($key);
    foreach($method->getParameters() as $parameter)   //获取反射方法
    {
        if($parameter->name==$key)
        {
            return true;
        }
    }
    return false;
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
        if($route_obj['RequestMethod']==$_SERVER['REQUEST_METHOD'])     //判断请求方法是否正确
        {
            $className=$route_obj['Class'];
            $method=$route_obj['Method'];
            require('code/'.$className.'.class.php');
            $param=array_filter($result,'getMatch',ARRAY_FILTER_USE_KEY);   //根据回调函数筛选匹配数组内容
            $class_obj=new ReflectionClass($className);             //实例化反射出来的类
            $getMethod=$class_obj->getMethod($method);              //获得需要调用的反射类下的对应方法
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
