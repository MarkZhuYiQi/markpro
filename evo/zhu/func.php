<?php
/**
 * Compiled By Mark
 * 2016-11-08 23:09:23
 */
function showName()
{
    echo "mark";
}
function showAge()
{
    echo 18;
}
function getMatch($key)
{
    return preg_match("/[A-Za-z]+/",$key);
}

