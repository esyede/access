<?php

defined('DS') or exit('No direct script access.');

return [

    // Nama kolom database untuk username
    'column' => ['email', 'username'],

    // Nama model user
    'model' => '\Esyede\Access\Models\User',

    // Nama role super admin
    'superadmin' => 'Super Admin',

    // Prefix untuk penamaan kolom.
    'prefix' => '',
];
