<?php
/**
 * Created by PhpStorm.
 * User: red
 * Date: 10/13/16
 * Time: 1:49 PM
 */

function age(){
    if(isset($_POST['age']))
    {
        echo $_POST['age'];
    }
}