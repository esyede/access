<?php

namespace Esyede\Access\Models;

defined('DS') or exit('No direct script access.');

class Permission extends Model
{
    public static $fillable = ['name', 'description'];

    public function roles()
    {
        return $this->has_many_and_belongs_to(
            __NAMESPACE__.'\Role',
            $this->prefix.'permission_role'
        );
    }
}
