<?php
/**
 * Created by PhpStorm.
 * User: red
 * Date: 10/20/16
 * Time: 9:56 PM
 */

namespace core\frame;
class mark_mvc
{
    public $className='';       //传入的类名
    public $classComment='';     //类的注释
    public $classMethods=array();
    function __construct($cName)
    {
        $this->className=$cName;
        $f=new \ReflectionClass($cName);
        $this->classComment=$f->getDocComment();
        $this->classMethods=$f->getMethods();    //获取类中所有方法集合，返回一个方法对象数组

    }
    function isController()
    {
        return preg_match('/@Controller/',$this->classComment);
    }
    function getRequestMapping()
    {
        $result=array();
        foreach($this->classMethods as $method)
        {
            $getRes=$this->genRequestMappingResult($method);
            if($getRes)
            {
                $result=array_merge($result,$getRes);
            }
        }
        return $result;
    }
    function genRequestMappingResult($method)
    {
        if(preg_match("/@RequestMapping\(\"(?<RequestUrl>[\/a-z]{2,50})\"\,Method=(?<RequestMethod>[\w]{3,8})\)/",$method->getDocComment(),$result))
        {
            return array(
                $result['RequestUrl']=>array(
                    'RequestMethod'=>$result['RequestMethod'],
                    'Class'=>$this->className,
                    'Method'=>$method->getName()
                )
            );
        }
        return false;
    }
}