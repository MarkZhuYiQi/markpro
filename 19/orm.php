<?php
/**
 * Created by PhpStorm.
 * User: red
 * Date: 10/31/16
 * Time: 9:46 AM
 */
ini_set('display_errors',1);
ini_set('error_reporting',E_ALL);

//$orm->select("uname","uage","uid")->from("users");

class orm
{
//    public $sql='';
//    将sql设置为数组，主要目的是防止在连写时多写几个select也能全部在同一个数组中操，最后拼接出来的字符串依然是正确的。
    public $sql=array(
        "select"=>"select ",
        "from"=>" from ",
    );

    /**
     * 拼接select后的值内容，这里使用func_get_args获取调用时传入的所有变量，然后循环这个数组，将每个变量拼接到select后方
     * 注意这里放入的是sql数组的select键中，程序全程都是对这个数组操作，所以不会重复或者覆盖别的变量，如果用字符串就会发生问题
     * __FUNCTION__是返回该方法的名字，比如这个方法返回的就是select。
     *
     * @return $this
     */
    function select()
    {
        $fields=func_get_args();
//        var_export($fields);
        foreach($fields as $field)
        {
            //__FUNCTION__返回的是方法的名字 类似select
            //__METHOD__返回的是类的名字和方法的名字 类似Orm::select
            if(trim($this->sql[__FUNCTION__])==__FUNCTION__)
            {
                $this->sql[__FUNCTION__].=$field;
            }
            else
            {
                $this->sql[__FUNCTION__].=','.$field;
            }

        }
        return $this;
    }

    /**
     * 拼接from表名
     * @param $tableName
     * @return $this
     */
    function from($tableName)
    {
        $this->sql[__FUNCTION__].=$tableName;
        return $this;
    }
    function where()
    {

    }

    /**
     * 魔术方法：将输出类型改成字符串类型
     * @return string
     */
    function __toString()
    {
        // TODO: Implement __toString() method.
//        这个办法有点土，先加再删无用功，应该在创建的时候动脑经
//        $this->sql['select']=substr($this->sql['select'],0,count($this->sql['select'])-2);

        // array_values（）返回键值中的值部分
        return implode(array_values($this->sql),' ');
    }
}

$orm=new orm();
echo $orm->select("uid","uname","uage")->from("users")->select("upwd");
//$result=$orm->select("uid","uname","uage")->from("users");
//echo $result;