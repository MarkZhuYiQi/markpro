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
     * @RequestMapping("/getage",Method=POST);
     */
    function abc()
    {
        echo 'abc';
    }
}