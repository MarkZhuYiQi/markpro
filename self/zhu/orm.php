<?php
/**
 * Created by PhpStorm.
 * User: red
 * Date: 10/31/16
 * Time: 11:25 AM
 */
ini_set('display_errors',1);
ini_set('error_reporting',E_ALL);

class orm
{
    public $sql=array(
        'select'=>'select ',
        'from'=>' from ',
        'where'=>' where '
    );
    function select()
    {
        $fields=func_get_args();
        foreach($fields as $field)
        {
            $this->_add(__FUNCTION__,$field);
        }
        return $this;
    }
    function from($tableName)
    {
        $this->_add(__FUNCTION__,$tableName);
        return $this;
    }
    function where()
    {

    }
    // 实现字符串累加
    function _add($key,$str,$spliter=',')
    {
        if(!$this->sql[$key])return;
        if(trim($this->sql[$key])==$key)
        {
            $this->sql[$key].=$str;
        }
        else
        {
            $this->sql[$key].=$spliter.$str;
        }
    }
    function __toString()
    {
        // TODO: Implement __toString() method.
        return implode(array_values($this->sql));
    }
}
$orm=new orm();
echo $orm->select('uname','upwd','uid')->from('users')->select('uage');
