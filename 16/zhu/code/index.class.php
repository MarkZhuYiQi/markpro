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
     * @RequestMapping("/login$",Method=GET);
     */
    function user_login($display){
        $vars['login']='success';
        $vars['method']='displayVars';
        $display('login',$vars);
    }

    /**
     * @RequestMapping("/getage",Method=POST);
     */
    function abc()
    {
        echo 'abc';
    }
}