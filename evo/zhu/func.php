<?php
/**
 * Compiled By Mark
 * 2016-11-09 13:04:12
 */
function showName()
{
    echo "mark";
}
function showAge()
{
    echo 18;
}
function getMatch($key)
{
    return preg_match("/[A-Za-z]+/",$key);
}

function poster($method,&$result)
{
//    $params=file_get_contents('php://input');
    if($_SERVER['REQUEST_METHOD']=='POST')
    {
        if($_SERVER['CONTENT_TYPE']=='application/json')
        {
            $get_obj=json_decode(file_get_contents("php://input"));
            foreach($get_obj as $key=>$value)
            {
                if(isExistParam($key,$method))$result[$key]=$value;
            }
            return;
        }
        foreach($result as $key=>$value)
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
