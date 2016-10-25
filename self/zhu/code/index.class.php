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
     * @RequestMapping("/getInfo/(?<name>\w{2,10})/(?<age>\d+)$",Method=GET)
     */
    function default($name,$age)
    {
        echo "i am ".$name.", ".$age." years old, but i am still a loser";
    }

    /**
     * @RequestMapping("/getabc",Method=POST)
     */
    function abc()
    {
        echo 'abc';
    }
}