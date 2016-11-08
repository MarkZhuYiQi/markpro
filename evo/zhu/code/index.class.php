<?php
/**
 * Created by PhpStorm.
 * User: red
 * Date: 11/8/16
 * Time: 9:18 AM
 */

/**
 * Class index
 * @Controller
 */
class index
{
    /**
     * @RequestMapping("/getName/(?<name>\w{2,50})/(?<age>\d{1,3})$",Method=GET)
     */
    function default($name,$age)
    {
        echo 'hello mark!'.PHP_EOL;
        echo 'visitor name:'.$name.PHP_EOL;
        echo 'visitor age:'.$age.PHP_EOL;
    }
    /**
     * @RequestMapping("/name",Method=GET)
     */
    function name()
    {
        echo 'hello mark!'.PHP_EOL;
    }

    /**
     * @RequestMapping("/getAbc",Method=POST)
     */
    function abc()
    {

    }

    /**
     * @param $display
     * @RequestMapping("/login$",Method=GET)
     */
    function login($display)
    {
        $vars['userName']='red_026';
        $vars['password']='7777777y';
        $tpl='login';
        $display($tpl,$vars);
    }

    /**
     * @param $userName
     * @param $password
     * @param $display
     * @RequestMapping("/login_post$",Method=POST)
     */
    function login_post($userName,$password,$display)
    {
        $arr['userName']=$userName;
        $arr['password']=$password;
        $display('index',$arr);
    }
}