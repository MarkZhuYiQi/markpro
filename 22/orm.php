<?php
/**
 * Created by PhpStorm.
 * User: red
 * Date: 10/31/16
 * Time: 9:46 AM
 */
ini_set('display_errors',1);
ini_set('error_reporting',E_ALL);

$map = function ($items) {
    if (!is_array($items)) {
        return $items;
    } else {
        $result = '';
        foreach ($items[1] as $item) {
            if ($result != '') {
                $result .= ',';
            }
            //加上表别名，如new  _news
            //这里使用orm，是在类中绑定的类名，将类中的类作用域和类对象都指定了。
            $result .= $item . ' ' .orm::_aliastb($item);
        }
        //$result最终就是类似news,news_class $items[0]就是譬如from这个sql关键字
        return $items[0] . $result;
    }
};


class orm
{
//    public $sql='';
//    将sql设置为数组，主要目的是防止在连写时多写几个select也能全部在同一个数组中操，最后拼接出来的字符串依然是正确的。
    public $sql=array(
        "select"=>"select ",
        "from"=>[" from ",[]],      //5.4之后的写法,在from中添加一个子数组，数组中存放需要查找的表数组，最后组合字符串
        "where"=>" where ",
        "orderby"=>" order by ",
        "limit"=>" limit ",
        "insertinto"=>'insert into',
        "insertfields"=>" ",
        "values"=>" values"
    );
    function insert()
    {
        $params=func_get_args();        //获得所有参数
        $fields=[];
        $fields_values=[];
        $callback=[];
        foreach($params as $param)
        {
            if(is_array($param))
            {
                foreach($param as $item)
                {
                    $field=key($item);  //取出字段名
                    $field_value=$item[$field]; //获得字段值
                    //然后把以上的字段名和字段值分别加入不同的数组当中
                    $fields[]=$field;
                    if(is_string($field_value))     //如果是字符串需要加上单引号
                    {
                        $fields_values[]="'".$field_value."'";   //这里要天界危险字符串过滤，请自行添加相应的函数
                    }
                    else
                    {
                        $fields_values[]=$field_value;
                    }
                }
                //字符串处理完成
                $this->_add('insertfields','('.implode($fields,',').')');
                $this->_add('values','('.implode($fields_values,',').')');
//                var_export($this->sql['insertfields']);
//                var_export($this->sql['values']);
            }
            if(is_string($param))
            {
                $this->_add("insertinto",$param);
            }
            if(is_callable($param))
            {
                $callback[]=$param;
            }
        }
        return $this;
    }
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
            //如果select是带表名的项名，即数组键值，那么就转化成字符串。
            if(is_array($field))
            {
                $tb_key=key($field);
                $tb_value=$field[$tb_key];
                $this->_add(__FUNCTION__,orm::_aliastb($tb_key).'.'.$tb_value);
            }
            else
            {
                //__FUNCTION__返回的是方法的名字 类似select
                //__METHOD__返回的是类的名字和方法的名字 类似Orm::select
                $this->_add(__FUNCTION__,$field);
            }
        }
        return $this;
    }

    /**
     * 拼接from表名
     * 处理form过程，如果参数是数组，代表需要多张表进行关联
     * @param $tableName
     * @return $this
     */
    function from($tableName)
    {
        if(is_array($tableName))
        {
            //小于2没有意义，没有对比
            if(count($tableName)!=2)return $this;
            //2张表关联的情况，暂时写死
            //取出第一个数据和第二个数据
            $tb1=current($tableName);   //第一张表，形如["news"=>"classid"]
            $tb2=next($tableName);      //第二张表
            //第一步,把表的key作为from参数进行处理
            $tb1_key=key($tb1); //取出key，譬如news
            $tb1_value=$tb1[$tb1_key];  //取出value如classId
            $tb2_key=key($tb2);
            $tb2_value=$tb2[$tb2_key];
            $this->_add(__FUNCTION__,$tb1_key);
            $this->_add(__FUNCTION__,$tb2_key);
//            第二步，拼凑where条件
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
        $this->_add(__FUNCTION__,$str," and ");
        return $this;
    }
    function orderby($str,$order='ASC') //倒序DESC
    {
        if(is_array($str))
        {
            $tb=key($str);
            $this->_add(__FUNCTION__,orm::_aliastb($tb).'.'.$str[$tb].' '.$order);
        }
        else
        {
            $this->_add(__FUNCTION__,$str.' '.$order);
        }
        return $this;
    }
    function limit($start,$end)
    {
        if(is_numeric($start)&&is_numeric($end))$this->_add(__FUNCTION__,$start.','.$end);
        return $this;
    }
    function _add($key,$str,$spliter=',')
    {
        //如果数组中这个键不存在就退出
        if(!$this->sql[$key])return;
        if(is_array($this->sql[$key]))
        {
            //如果已经存在该项名（表名），不处理
            if(!in_array($str,$this->sql[$key][1]))
            {
                //不存在就加入到数组当中
                array_push($this->sql[$key][1],$str);
            }
        }
        else
        {
            //如果是字符串，我们直接进行字符串累加
            //这里不用trim因->orderby(['news'=>'id'],'desc')->limit(0,2)为只会去掉前后，而像 order by 这种键，trim后是order by依然和键不相等，所以需要吧所有空格都去了
            if(preg_replace('/\s/','',$this->sql[$key])==$key || preg_replace('/\s/','',$this->sql[$key])=='')
            {
                $this->sql[$key].=$str;
            }
            else
            {
                $this->sql[$key].=$spliter.$str;
            }
        }
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
//        return implode(array_values($this->sql),' ');
        global $map;

        //给匿名函数绑定指定的对象，复制其类作用域,这样就可以在外部回调函数里面使用$this
//        $map=Closure::bind($map,$this);
        //或者给定一个类型名，调用时直接使用类似 orm::_aliastb();
        $map=Closure::bind($map,$this,'orm');

        $filter=function($value,$key)   //这个很奇怪，是value在前面，key在后面的
        {
//            if(!is_string($value))return true;
            //这里换一个判断方式，如果不是字符串那就是数组，数组下面的第二个值也是数组，必须有值,这就是给from准备的？
            if (is_array($value))
            {
                if(count($value[1])>0)return true;
                return false;
            }
            //这里光判断键和值不等还不够，还需要判断如果值是空的那么也是不行的，比如insertfields
            if(preg_replace('/\s/','',$value)==$key||preg_replace('/\s/','',$value)=='')return false;
            return true;
        };

        $this->sql=array_filter($this->sql,$filter,ARRAY_FILTER_USE_BOTH);
        $ret = array_map($map, array_values($this->sql));  //函数通过回调，产生一个新的数组，用于构建字符串
        return implode(array_values($ret), ' ');
    }

    function _aliastb($tbName)
    {
        return ' _'.$tbName;
    }
}

$orm=new orm();
//echo $orm->select(["news"=>"uid"],"uname","uage",["users"=>"uid"])->from([["news"=>"uid"],["users"=>"uid"]])
//    ->from([["class"=>"classId"],["info"=>"pid"]])->orderby(['news'=>'id'],'desc')->limit(0,2);
echo $orm->insert([
    ['id'=>123],
    ['name'=>'zhu']
],'news');


?>





<script type="text/javascript">
    var str=document.body.innerHTML;
    var matches=str.match(/(where)|(from)|(order\sby)|(limit)|(select)/g);
    matches.forEach(function(item){
        str=str.replace(item,"<span style='color:red'>"+item+"</span>");
    });
    document.body.innerHTML=str;

</script>