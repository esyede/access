<?php

namespace Esyede\Access\Models;

defined('DS') or exit('No direct script access.');

use System\Str;

class Permission extends Model
{
    public static $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function set_slug($slug)
    {
        $this->set_attribute('slug', Str::slug($slug));
    }

    public function roles()
    {
        return $this->belongs_to_many(
            '\Esyede\Access\Models\Role',
            $this->prefix.'permission_role'
        );
    }
}
