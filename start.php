<?php

defined('DS') or exit('No direct script access.');

Autoloader::namespaces(['Esyede\Access\Libraries' => Package::path('access').'libraries']);
Autoloader::namespaces(['Esyede\Access\Models' => Package::path('access').'models']);
Autoloader::map(['Access' => Package::path('access').'libraries'.DS.'Access'.EXT]);

Auth::extend('access', function () {
    return new Access();
});
