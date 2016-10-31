<?php

/**
 * Created by PhpStorm.
 * User: red
 * Date: 10/14/16
 * Time: 10:37 AM
 */

/**
 * Class index
 * @Controller
 */
class index
{

    /**
     * @RequestMapping("/getme/(?<name>\w{2,10})/(?<age>\d+)$",Method=GET);
     */
    function default($name,$age){
        echo 'hello,'.$name.PHP_EOL;
        echo '<hr />';
        echo 'my age is '.$age;
    }
    
    /**
     * @RequestMapping("/login$",Method=GET)
     */
    function user_login($display)
    {
        $vars['var1']='通过类中设置数组变量，传递给模板函数';
        $display('login',$vars);
    }

    /**
     * @RequestMapping("/login_post$",Method=POST)
     */
    function user_login_post($uname,$upwd,$display)
    {
        $json=new stdClass();
        $json->uname=$uname;
        $json->upwd=$upwd;
        exit(json_encode($json));


//        echo readfile('php://input');
//        echo $uname;
//        echo "<hr>";
//        echo $upwd;
//        echo '<hr>';
        
//        $vars['index']='test if the variable transfer to the index html?';
//        $display('index',$vars);
    }

    /**
     * @RequestMapping("/json_post$",Method=POST)
     */
    function json_post($uname,$upwd,$display)
    {
        $json=new stdClass();
        $json->uname=$uname;
        $json->upwd=$upwd;
        exit($json);
    }



    /**
     * @RequestMapping("/getabc",Method=POST)
     */
    function abc()
    {
        echo 'abc';
    }
}