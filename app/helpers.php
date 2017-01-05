<?php

function arrayToCollection(array $array)
{
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $array[$key] = arrayToCollection($array[$key]);
        }
    }

    return collect($array);
}

function kebabize($str)
{
    return (str_replace(' ', '-', strtolower($str)));
}