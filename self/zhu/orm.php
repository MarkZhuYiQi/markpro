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
        'from'=>[' from ',[]],
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
    {;
        if(is_array($tableName))
        {
            if(count($tableName)<2)return false;
/*            $tb1=current($tableName);   //第一张表，形如['news'=>'classId']
            $tb2=next($tableName);
            $tb1_key=key($tb1);
            $tb1_value=$tb1[$tb1_key];
            $tb2_key=key($tb2);
            $tb2_value=$tb2[$tb2_key];
            $this->_add(__FUNCTION__,$tb1_key);
            $this->_add(__FUNCTION__,$tb2_key);
            $whereString='_'.$tb1_key.'.'.$tb1_value.'=_'.$tb2_key.'.'.$tb2_value;
            $this->where($whereString);*/
            $whereString='';
            while(current($tableName))
            {
                $tb=current($tableName);
                $tb_key=key($tb);
                $tb_value=$tb[$tb_key];
                $this->_add(__FUNCTION__,$tb_key);
                if($whereString=='')
                {
                    $whereString.='_'.$tb_key.'.'.$tb_value;
                }
                else
                {
                    $whereString.='=_'.$tb_key.'.'.$tb_value;
                }
                next($tableName);
            }
            $this->where($whereString);
        }
        else
        {
            $this->_add(__FUNCTION__,$tableName);
        }
        return $this;
    }
    function where($str)
    {
        $this->_add(__FUNCTION__,$str,' and ');
    }
    // 实现字符串累加
    function _add($key,$str,$spliter=',')
    {
        if(!$this->sql[$key])return;
        if(is_array($this->sql[$key]))
        {
            if(!in_array($str,$this->sql[$key][1]))
            {
                array_push($this->sql[$key][1],$str);
            }
        }
        else
        {
            if(trim($this->sql[$key])==$key)
            {
                $this->sql[$key].=$str;
            }
            else
            {
                $this->sql[$key].=$spliter.$str;
            }
        }
    }
    function __toString()
    {
        // TODO: Implement __toString() method.
        $map=function($items){
            if(!is_array($items))
            {
                return $items;
            }
            else
            {
                $result='';
                foreach($items[1] as $item)
                {
                    if($result!='')
                    {
                        $result.=',';
                    }
                    $result.=$item.'_'.$item;
                }
                return $items[0].$result;
            }
        };
        $ret=array_map($map,array_values($this->sql));
        return implode(array_values($ret));
    }
}
$orm=new orm();
echo $orm->select('uname','upwd','uid','uid')->from([['news'=>'classId'],['users'=>'uid'],['class'=>'pid']]);
