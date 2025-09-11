<?php

// Add custom debug
if (!function_exists('ddd')) {
    function ddd($data) {
        echo '<pre>';
        var_dump(json_decode(json_encode($data)));
        echo '</pre>';
        die();
    }
}