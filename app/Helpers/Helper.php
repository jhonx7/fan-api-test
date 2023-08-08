<?php

if (!function_exists('getNumberPairs')) {
    function getNumberPairs($array)
    {
        $pairs = 0;
        $counts = array_count_values($array);

        foreach ($counts as $val => $count) {
            $count = floor($count / 2);
            $pairs = $pairs + $count;
        }

        return (int)$pairs;
    }
}
if (!function_exists('countWord')) {
    function countWord($sentence)
    {
        // Split the sentence into an array of words
        $words = explode(' ', $sentence);

        // Initialize a count for valid words
        $validWordCount = 0;

        foreach ($words as $word) {
            if (preg_match('/^[a-zA-Z0-9?!.,-]+$/', $word)) {
                $validWordCount++;
            }
        }

        return $validWordCount;
    }
}
