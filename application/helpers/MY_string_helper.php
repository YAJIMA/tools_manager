<?php
/**
 * Created by PhpStorm.
 * User: yajima
 * Date: 2018-7月-1
 * Time: 15:34
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('stringfromcolindex'))
{
    function stringfromcolindex($colindex)
    {
        $c = intval($colindex);
        if ($c <= 0) return '';

        $letter = '';

        while($c != 0){
            $p = ($c - 1) % 26;
            $c = intval(($c - $p) / 26);
            $letter = chr(65 + $p) . $letter;
        }

        return $letter;
    }
}

if ( ! function_exists('path2directory'))
{
    function path2directory($str, $search = '__')
    {
        return str_replace($search, DIRECTORY_SEPARATOR, $str);
    }
}

if ( ! function_exists('dir2path'))
{
    function dir2path($str, $search = '__')
    {
        return str_replace(DIRECTORY_SEPARATOR, $search, $str);
    }
}