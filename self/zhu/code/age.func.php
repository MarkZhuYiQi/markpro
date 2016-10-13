<?php
/**
 * Created by PhpStorm.
 * User: red
 * Date: 10/13/16
 * Time: 1:49 PM
 */

function name(){
    if(isset($_POST['name']))
    {
        echo $_POST['name'];
    }
}