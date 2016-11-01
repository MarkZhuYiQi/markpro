<?php
/**
 * Created by PhpStorm.
 * User: red
 * Date: 10/31/16
 * Time: 11:25 AM
 */
ini_set('display_errors',1);
ini_set('error_reporting',E_ALL);

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
//            这里两种方法都可以，取决于你在绑定时候怎么绑定，如果绑定了类名称，那么可以直接使用，否则需要使用$this但是这样子很难理解
//            $result.=$item.$this->_aliastb($item);
            $result.=$item.orm::_aliastb($item);
        }
        return $items[0].$result;
    }
};

class orm
{
    public $sql=array(
        'select'=>'select ',
        'from'=>[' from ',[]],
        'where'=>' where ',
        'orderby'=>' order by ',
        'limit'=>' limit ',
        'groupby'=>' group by ',
        'leftjoin'=>' left join '
    );
    function select()
    {
        $fields=func_get_args();
        foreach($fields as $field)
        {
            if(is_array($field))
            {
                $tb=key($field);
                $this->_add(__FUNCTION__,orm::_aliastb($tb).'.'.$field[$tb]);
            }
            else
            {
                $this->_add(__FUNCTION__,$field);
            }

        }
        return $this;
    }
    function from($tableName)
    {
        if(is_array($tableName))
        {
            if(count($tableName)!=2)return false;
            $tb1=current($tableName);   //第一张表，形如['news'=>'classId']
            $tb2=next($tableName);
            $tb1_key=key($tb1);
            $tb1_value=$tb1[$tb1_key];
            $tb2_key=key($tb2);
            $tb2_value=$tb2[$tb2_key];
            $this->_add(__FUNCTION__,$tb1_key);
            $this->_add(__FUNCTION__,$tb2_key);
            $whereString=orm::_aliastb($tb1_key).'.'.$tb1_value.'='.orm::_aliastb($tb2_key).'.'.$tb2_value;
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
    function orderby($str,$order='ASC')
    {
        $this->_add(__FUNCTION__,orm::_aliastb($str).' '.$order);
        return $this;
    }
    function limit($start,$end)
    {
        is_numeric($start)&&is_numeric($end)?$this->_add(__FUNCTION__,$start.','.$end):false;
        return $this;
    }
    function groupby()
    {
        $fields=func_get_args();
        foreach($fields as $field)
        {
            if(is_array($field))
            {
                $tb=key($field);
                $tb_value=$field[$tb];
                $this->_add(__FUNCTION__,orm::_aliastb($tb).'.'.$tb_value);
            }
            else
            {
                $this->_add(__FUNCTION__,$field);
            }
        }
        return $this;
    }
    function leftjoin($tb1,$tb2)
    {
        if(is_array($tb1)&&is_array($tb2))
        {

        }
        return $this;
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
            if(preg_replace('/\s/','',$this->sql[$key]) != $key)
            {
                $this->sql[$key].=$spliter.$str;
            }
            else{
                $this->sql[$key].=$str;
            }
        }
    }
    function __toString()
    {
        // TODO: Implement __toString() method.

        $filter=function($value,$key){
            if(!is_string($value))return true;
            if(preg_replace('/\s/','',$value)==$key)return false;
            return true;
        };
        //array_filter 将每个成员放进回调函数处理，如果返回true则返回数组中，否则删除
        $this->sql=array_filter($this->sql,$filter,ARRAY_FILTER_USE_BOTH);
        //这个map是把回调函数放到外面去。然后通过绑定对象和类作用域的形式把orm类付给了外部$map
        global $map;
        $map=Closure::bind($map,$this,'orm');
        $ret=array_map($map,array_values($this->sql));
        return implode(array_values($ret));
    }
    function _aliastb($tb_name)
    {
        return ' _'.$tb_name;
    }
}
$orm=new orm();
//echo $orm->select('uname','upwd',['news'=>'uid'],['users'=>'uid'])->from([['news'=>'classId'],['users'=>'uid']])
//    ->orderby('news','desc')->limit(0,2)->groupby(['class'=>'uid'],['users'=>'uid']);
echo $orm->select(['users'=>'u_id'],['users'=>'u_name'],['news'=>'n_id'],['news'=>'n_name'])->from
    ->leftjoin(['users'=>'u_id'],['news'=>'n_id']);
?>

<script>
    var str=document.body.innerHTML;
    var matches=str.match(/(where)|(insert)|(update)|(delete)|(select)|(limit)|(left\sjoin)|(group\sby)|(order\sby)/g);
    matches.forEach(function(item)
    {
        str=str.replace(item,"<span style='color:red'>"+item+"</span>");
    });
    document.body.innerHTML=str;

</script>
