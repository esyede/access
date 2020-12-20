<?php

namespace Esyede\Access\Models;

defined('DS') or exit('No direct script access.');

class Permission extends Model
{
    public static $accessible = ['name', 'description'];

    public function roles()
    {
        return $this->has_many_and_belongs_to(
            '\Esyede\Access\Models\Role',
            $this->prefix.'permission_role'
        );
    }
}
