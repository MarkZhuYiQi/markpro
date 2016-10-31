<?php
/**
 * Created by PhpStorm.
 * User: red
 * Date: 10/13/16
 * Time: 1:49 PM
 */

function age(){
    if(isset($_POST['age']))
    {
        echo $_POST['age'];
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

/**
 * @param $requestMethod        请求的方法类型，有GET和POST
 * @param $params               取得传入的变量
 * @param $method               url地址所请求的方法
 */
function poster($requestMethod,&$params,$method)
{
    if($requestMethod=='POST')
    {
        if($_SERVER['CONTENT_TYPE']=='application/json')
        {
            $getObj=json_decode(file_get_contents("php://input"));
            foreach($getObj as $key=>$value)
            {
                if(existParam($method,$key))
                {
                    $params[$key]=$value;
                }
            }
        }
        else
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
}
/**
 * @param $method   需要请求的方法
 * @param $key      变量值
 * @return bool     返回对这个键值是否存在
 */
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