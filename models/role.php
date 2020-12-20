<?php

namespace Esyede\Access\Models;

defined('DS') or exit('No direct script access.');

class Role extends Model
{
    public static $accessible = ['name', 'description', 'level'];

    public function users()
    {
        return $this->has_many_and_belongs_to(
            '\Esyede\Access\Models\User',
            $this->prefix.'role_user'
        );
    }

    public function permissions()
    {
        return $this->has_many_and_belongs_to(
            '\Esyede\Access\Models\Permission',
            $this->prefix.'permission_role'
        );
    }
}
