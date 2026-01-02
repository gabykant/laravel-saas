<?php

if (! function_exists('tenant')) {
    function tenant() {
        if (app()->has('tenant')) {
            return app('tenant');
        }

        return null;
    }
}