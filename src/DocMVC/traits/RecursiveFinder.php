<?php

namespace DocMVC\traits;

trait RecursiveFinder
{
    private function recursiveFind($arr, $keyArr, $notFoundCallback, $idx = 0)
    {
        $key = $keyArr[$idx];

        if(!isset($arr[$key])) {
            return $notFoundCallback();
        }

        if(!is_array($arr[$key])) {
            return $arr[$key];
        }

        $idx++;
        if(!isset($keyArr[$idx])) {
            return $notFoundCallback();
        }

        return $this->recursiveFind($arr[$key], $keyArr, $notFoundCallback, $idx);
    }
}