<?php

//Fetch the theme cookie
$theme='';

if(!empty($_COOKIE['theme']) && $_COOKIE['theme'] === 'dark'){
    $theme = 'dark';
}