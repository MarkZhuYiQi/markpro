<?php
/**
 * Created by PhpStorm.
 * User: red
 * Date: 11/8/16
 * Time: 9:53 AM
 */
namespace core\frame;
class mark_mvc
{
    public $className='';
    public $classComment='';
    public $classMethods='';
    function __construct($className)
    {
        $this->className=$className;
        $f=new \ReflectionClass($className);
        $this->classComment=$f->getDocComment();
        $this->classMethods=$f->getMethods();
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
            $res=$this->genRequestMappingResult($method);
            if($res) $result=array_merge($result,$res);
        }
        return $result;
    }
    function genRequestMappingResult($method)
    {
//        * @RequestMapping("/getName",Method=GET)
//        var_export($method);
        if(preg_match("/@RequestMapping\(\"(?<RequestUrl>\/.{2,50})\"\,Method=(?<RequestMethod>[\w]{3,8})\)/",$method->getDocComment(),$result))
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