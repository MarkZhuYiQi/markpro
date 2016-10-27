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
     * @RequestMapping("/login$",Method=GET)
     */
    function user_login($display)
    {
        $vars['var1']='通过类中设置数组变量，传递给模板函数';
        $display('login',$vars);
    }

    /**
     * @RequestMapping("/getabc",Method=POST)
     */
    function abc()
    {
        echo 'abc';
    }
}