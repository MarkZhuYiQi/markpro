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
        'orderby'=>' order by ',
        'limit'=>' limit ',
        'groupby'=>' group by ',
        'leftjoin'=>' left join ',
        'insertinto'=>' insert into ',
        'insertfields'=>'',
        'values'=>' values ',
        'update'=>'update ',
        'set'=>' set ',
        'where'=>' where ',
    );
    public $db=false;
    public $errorCode='';
    function __construct()
    {
        $this->sql_bak=$this->sql;
        try{
            $this->db=new pdo('mysql:host=127.0.0.1;dbname=red','root','7777777y');
        }catch(PDOException $e) {
            print_r('error!'.$e->getMessage().'<br />');
            die();
        }
    }
    function _runExecAndCallBack($callback)
    {
        if(count($callback)>0)
        {
            $this->exec();
            foreach($callback as $call)
            {
                $call=Closure::bind($call,$this,'orm');
                $call();
            }
        }
    }
    function exec()
    {
        $sql=strval($this);
        $stmt=$this->db->prepare($sql);
        if(!$stmt->execute())
        {
            if($this->db->inTransaction())
            {
                $this->db->rollBack();
            }
            $this->errorCode=$stmt->errorCode();
            echo 'Error while execute sql:'.$sql.'<br />'.'error code:'.$this->errorCode.'error message:'.$stmt->errorInfo()[2];
        }
        return $this;
    }
    function commit()
    {
        $this->db->commit();
        return $this;
    }
    function getLastInsertId()
    {
        return $this->db->lastInsertId();
    }

    function insert()
    {
        $fields=[];
        $fields_values=[];
        $callback=[];
        $parameters=func_get_args();
        foreach($parameters as $params)
        {
            if(is_array($params))
            {
                foreach($params as $item)
                {
                    $field=key($item);
                    $field_value=$item[$field];
                    if(is_string($field_value))
                    {
                        $fields_values[]="'".$field_value."'";
                    }else{
                        $fields_values[]=$field_value;
                    }
                    $fields[]=$field;
                }
                $this->_add('insertfields', '('.implode($fields,',').')');
                $this->_add('values', '('.implode($fields_values,',').')');
            }
            if(is_string($params))
            {
                $this->_add('insertinto',$params);
            }
            if(is_callable($params))
            {
                $callback[]=$params;
            }
            if(is_bool($params))
            {
                $this->db->beginTransaction();
            }
        }
        $this->_runExecAndCallBack($callback);
        return $this;
    }
    function update()
    {
        $keys=[];
        $values=[];
        $params=func_get_args();
        foreach($params as $param)
        {
            if(is_array($param))
            {
                foreach($param as $v)
                {
                    $key=key($v);
                    $value=is_string($v[$key])?"'".$v[$key]."'":$v[$key];
                    $this->_add('set',"`".$key."`=".$value);
                }
            }
            if(is_string($param))
            {
                $this->_add(__FUNCTION__,'`'.$param.'`');
            }
        }
        return $this;
    }
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
    function where()
    {
        $parameters=func_get_args();
        $callback=[];
        foreach($parameters as $str){
            if(is_array($str))
            {
                foreach($str as $params)
                {
                    $key=key($params);
                    $value=$params[$key];
                    if(is_string($value))
                    {
                        $this->_add(__FUNCTION__,"`".$key."`='".$value."'",' and ');
                    }
                    else
                    {
                        $this->_add(__FUNCTION__,"`".$key."`=".$value,' and ');
                    }
                }
            }
            if(is_string($str)){
                $this->_add(__FUNCTION__,$str,' and ');
            }
            if(is_callable($str))
            {
                $callback[]=$str;
            }
            if(is_bool($str))
            {
                $this->db->beginTransaction();
            }
            $this->_runExecAndCallBack($callback);
        }
        return $this;
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
//        if(!$this->sql[$key])return;
        //这种判断太粗暴了，需要判断数组
        if(!array_key_exists($key,$this->sql))return;
        if(is_array($this->sql[$key]))
        {
            if(!in_array($str,$this->sql[$key][1]))
            {
                array_push($this->sql[$key][1],$str);
            }
        }
        else
        {
            if(preg_replace('/\s/','',$this->sql[$key]) == $key || preg_replace('/\s/','',$this->sql[$key])=='')
            {
                $this->sql[$key].=$str;
            }
            else{
                $this->sql[$key].=$spliter.$str;
            }
        }
    }
    function __toString()
    {
        // TODO: Implement __toString() method.

        $filter=function($value,$key){
//            if(!is_string($value))return true;        //这里只是粗暴的判断如果不是字符串就返回真，但是如果from里面是空的，依旧输出
            if(is_array($value))        //首先判断数组里的键值是否为数组，如果是数组说明是form那种类型
            {
                if(count($value[1])>0)return true;
                return false;
            }
            if(preg_replace('/\s/','',$value)==$key)return false;
            return true;
        };
        //array_filter 将每个成员放进回调函数处理，如果返回true则返回数组中，否则删除
        $this->sql=array_filter($this->sql,$filter,ARRAY_FILTER_USE_BOTH);
        //这个map是把回调函数放到外面去。然后通过绑定对象和类作用域的形式把orm类付给了外部$map
        global $map;
        $map=Closure::bind($map,$this,'orm');
        $ret=array_map($map,array_values($this->sql));
        $this->_clearConfig();
        return implode(array_values($ret));
    }
    function _aliastb($tb_name)
    {
        return ' _'.$tb_name;
    }
    function _clearConfig()
    {
        $this->sql=$this->sql_bak;
    }
}
$orm=new orm();
//echo $orm->select('uname','upwd',['news'=>'uid'],['users'=>'uid'])->from([['news'=>'classId'],['users'=>'uid']])
//    ->orderby('news','desc')->limit(0,2)->groupby(['class'=>'uid'],['users'=>'uid']);
//echo $orm->select(['users'=>'u_id'],['users'=>'u_name'],['news'=>'n_id'],['news'=>'n_name'])->from
//    ->leftjoin(['users'=>'u_id'],['news'=>'n_id']);

/*$orm->insert([['user_name'=>'zhu']],'users',true,
    function()
    {
        $userid=$this->getLastInsertId();
        orm::insert([
            ['user_id'=>intval($userid)],
            ['log_type'=>'reg'],
            ['log_date'=>time()]
        ],'user_log',function() {
            if ($this->errorCode == '')
            {
                $this->commit();
                echo 'reg success';
            }else{
                echo 'failed!<br />';
                exit();
            }
        });
    });*/

$orm->update([['log_type'=>'modify'],['user_id'=>99]],'user_log')->where([['log_id'=>9]],function(){
    if($this->db->errorCode=='')
    {
        echo '1';
    }else{
        echo $this->db->errorCode;
    }
});
?>

<script>
    var str=document.body.innerHTML;
    var matches=str.match(/(where)|(insert)|(update)|(delete)|(select)|(limit)|(left\sjoin)|(group\sby)|(order\sby)|(set)/g);
    matches.forEach(function(item)
    {
        str=str.replace(item,"<span style='color:red'>"+item+"</span>");
    });
    document.body.innerHTML=str;

</script>
