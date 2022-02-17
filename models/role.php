<?php

namespace Esyede\Access\Models;

defined('DS') or exit('No direct script access.');

use System\Str;

class Role extends Model
{
    public static $fillable = [
        'name',
        'slug',
        'deletable',
        'description',
        'level',
    ];

    public function set_slug($slug)
    {
        $this->set_attribute('slug', Str::slug($slug));
    }

    public function users()
    {
        return $this->belongs_to_many(
            '\Esyede\Access\Models\User',
            $this->prefix.'role_user'
        );
    }

    public function permissions()
    {
        return $this->belongs_to_many(
            '\Esyede\Access\Models\Permission',
            $this->prefix.'permission_role'
        );
    }
}
