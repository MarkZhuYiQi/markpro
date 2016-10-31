<?php
function name(){
    if(isset($_POST['name']))
    {
        echo $_POST['name'];
    }
}
function age(){
    if(isset($_POST['age']))
    {
        echo $_POST['age'];
    }
}

function getMatch($v)
{
    return preg_match('/[a-zA-Z]+/',$v);
}

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
