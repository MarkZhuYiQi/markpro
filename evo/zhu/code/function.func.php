<?php
function getMatch($key)
{
    return preg_match("/[A-Za-z]+/",$key);
}
function poster($method,&$result)
{
//    $params=file_get_contents('php://input');
    if($_SERVER['REQUEST_METHOD']=='POST')
    {
        foreach($_POST as $key=>$value)
        {
            if(isExistParam($key,$method))$result[$key]=$value;
        }
    }
}
function isExistParam($key,$method)
{
    $params=$method->getParameters();
    foreach($method->getParameters() as $parameter)
    {
        if($parameter->name==$key)return true;
    }
    return false;
}