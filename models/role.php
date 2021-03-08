<?php

namespace Esyede\Access\Models;

defined('DS') or exit('No direct script access.');

class Role extends Model
{
    public static $fillable = ['name', 'description', 'level'];

    public function users()
    {
        return $this->has_many_and_belongs_to(
            __NAMESPACE__.'\User',
            $this->prefix.'role_user'
        );
    }

    public function permissions()
    {
        return $this->has_many_and_belongs_to(
            __NAMESPACE__.'\Permission',
            $this->prefix.'permission_role'
        );
    }
}
