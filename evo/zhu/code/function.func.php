<?php
function getMatch($key)
{
    return preg_match("/[A-Za-z]+/",$key);
}