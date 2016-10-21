<?php
function name(){
    if(isset($_POST['name']))
    {
        echo $_POST['name'];
    }
}
function age(){
    if(isset($_POST['age']))
    {
        echo $_POST['age'];
    }
}
