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
     * @RequestMapping("/getme",Method=GET);
     */
    function default(){
        echo 'hello,mark'.PHP_EOL;
        echoName1();
    }

    /**
     * @RequestMapping("/getage",Method=POST);
     */
    function abc()
    {
        echo 'abc';
    }
}