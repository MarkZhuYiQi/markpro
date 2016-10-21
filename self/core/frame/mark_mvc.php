<?php
/**
 * Created by PhpStorm.
 * User: red
 * Date: 10/21/16
 * Time: 9:54 AM
 */

namespace core\frame;


class mark_mvc
{
    public $className='';           //传入的类名
    public $classComment='';
    public $classMethods=array();
    function __construct($cName)
    {
        $this->className=$cName;
        $f=new \ReflectionClass($cName);
        $this->classMethods=$f->getMethods();
        $this->classComment=$f->getDocComment();
    }
    function isController()
    {
        return preg_match("/@Controller/",$this->classComment);
    }
    function getRequestMapping()
    {
        $result=array();
        foreach($this->classMethods as $method)
        {
            $getRes=$this->genRequestMappingResult($method);
            $result=array_merge($result,$getRes);
        }
        return $result;
    }
    function genRequestMappingResult($method)
    {
        //@RequestMapping("/getInfo",Method=GET)
        if(preg_match("/@RequestMapping\(\"(?<RequestUrl>[\/A-Za-z]{2,50})\",Method=(?<RequestMethod>[\w]{2,50})\)/",$method->getDocComment(),$result))
        {
            return array(
                $result['RequestUrl']=>array(
                    'RequestMethod'=>$result['RequestMethod'],
                    'class'=>$this->className,
                    'Method'=>$method->getName()
                )
            );
        }
    }
}