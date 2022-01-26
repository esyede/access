<?php

namespace Esyede\Access\Models;

defined('DS') or exit('No direct script access.');

use Arr;
use Config;
use Hash;

class User extends Model
{
    public static $cache;
    public static $fillable = [
        'fullname',
        'username',
        'password',
        'email',
        'role_id',
        'verified_at',
        'deleted_at',
        'disabled_at',
    ];

    public function roles()
    {
        return $this->belongs_to_many(
           '\Esyede\Access\Models\Role',
            $this->prefix.'role_user'
        );
    }

    public function set_password($password)
    {
        $this->set_attribute('password', Hash::make($password));
    }

    public function can($permissions)
    {
        $permissions = Arr::wrap($permissions);
        $cache = static::cache();

        foreach ($cache->roles as $role) {
            if ($role->slug === 'admin') {
                return true;
            }
        }

        $valid = false;

        foreach ($cache->roles as $role) {
            foreach ($role->permissions as $permission) {
                if (in_array($permission->slug, $permissions)
                || in_array($permission->name, $permissions)) {
                    $valid = true;
                    break;
                }
            }

            if ($valid) {
                break;
            }
        }

        return $valid;
    }

    public function is($roles)
    {
        $roles = Arr::wrap($roles);
        $cache = static::cache();

        $valid = false;

        foreach ($cache->roles as $role) {
            if (in_array($role->slug, $roles)
            || in_array($role->name, $roles)
            || $role->slug === 'admin') {
                $valid = true;
                break;
            }
        }

        return $valid;
    }

    public function level($level, $modifier = '>=')
    {
        $cache = static::cache();

        $max = -1;
        $min = 100;
        $levels = [];

        foreach ($cache->roles as $role) {
            $max = ($role->level > $max) ? $role->level : $max;
            $min = ($role->level < $min) ? $role->level : $min;
            $levels[] = $role->level;
        }

        switch ($modifier) {
            case '=':  return in_array($level, $levels);
            case '>=': return $max >= $level;
            case '>':  return $max > $level;
            case '<=': return $min <= $level;
            case '<':  return $min < $level;
            default:   return false;
        }
    }

    private function cache()
    {
        if (static::$cache) {
            return static::$cache;
        }

        $class = get_class($this);
        static::$cache = $class::with(['roles', 'roles.permissions'])
            ->where_id($this->get_attribute('id'))
            ->first();

        return static::$cache;
    }
}
