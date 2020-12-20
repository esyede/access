<?php

Autoloader::namespaces(['Esyede\Access\Libraries' => Package::path('access').'models']);
Autoloader::map(['Access' => __DIR__.DS.'libraries/Access'.EXT]);
Auth::extend('access', function () {
    return new Access();
});
