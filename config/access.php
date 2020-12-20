<?php

defined('DS') or exit('No direct script access.');

return [

    // The db column to authenticate against
    'username' => ['email', 'username'],

    // The User mode to use
    'model' => '\Esyede\Access\Models\User',

    // The Super Admin role (returns true for all permissions)
    'superadmin' => 'Super Admin',

    // DB prefix for tables NO '_' NECESSARY, e.g. use 'access' for 'access_users'
    'prefix' => '',
];
