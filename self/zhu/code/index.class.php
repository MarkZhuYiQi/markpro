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
     * @RequestMapping("/getInfo",Method=GET)
     */
    function default()
    {
        echo "i am mark, 26 years old, but i am still a loser";
    }

    /**
     * @RequestMapping("/getabc",Method=POST)
     */
    function abc()
    {
        echo 'abc';
    }
}