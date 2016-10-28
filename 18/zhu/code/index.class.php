<?php

/**
 * Created by PhpStorm.
 * User: red
 * Date: 10/13/16
 * Time: 11:21 PM
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
     * @RequestMapping("/login$",Method=GET);
     */
    function user_login($display){
        $vars['login']='success';
        $vars['method']='displayVars';
        $display('login',$vars);
    }

    /**
     * @RequestMapping("/login_post$",Method=POST);
     *
     * 这些传入的变量都是通过反射然后带参数的调用方法实现传入这些值
     */
    function user_login_post($uname,$upwd,$display)
    {
        $obj=new stdClass();
        $obj->uname=$uname;
        $obj->upwd=$upwd;
        exit(json_encode($obj));
        
        
//        var_export($_POST);
//        echo readfile('php://input');
//        echo $uname;
//        echo $upwd;
//        $vars['index']='index页面';
//        $display('index',$vars);
    }
}