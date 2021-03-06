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
        "from"=>[" from ",[]],      //5.4之后的写法,在from中添加一个子数组，数组中存放需要查找的表数组，最后组合字符串
        "where"=>" where "
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
            $this->_add(__FUNCTION__,$field);

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
            if(count($tableName)<2)return $this;
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
            $whereString='_'.$tb1_key.'.'.$tb1_value.'='.'_'.$tb2_key.'.'.$tb2_value;
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
                    $result .= $item . '_' .$item;
                }
                //$result最终就是类似news,news_class $items[0]就是譬如from这个sql关键字
                return $items[0] . $result;
            }
        };
        $ret = array_map($map, array_values($this->sql));  //函数通过回调，产生一个新的数组，用于构建字符串
        return implode(array_values($ret), ' ');
    }
}

$orm=new orm();
echo $orm->select("uid","uname","uage","uid")->from([["news"=>"classId"],["users"=>"uid"]]);
//$result=$orm->select("uid","uname","uage")->from("users");
//echo $result;