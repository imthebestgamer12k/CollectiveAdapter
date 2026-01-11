<?php

if (! function_exists('form')) {
    function form() {
        static $instance;
        return $instance ?: $instance = app(\App\Service\FormService::class);
    }
}


?>