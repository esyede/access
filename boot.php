<?php

defined('DS') or exit('No direct script access.');

Autoloader::namespaces(['Esyede\Access\Libraries' => __DIR__.DS.'libraries']);
Autoloader::namespaces(['Esyede\Access\Models' => __DIR__.DS.'models']);
Autoloader::map(['Access' => __DIR__.DS.'libraries'.DS.'Access.php']);

Auth::extend('access', function () {
    return new Access();
});
