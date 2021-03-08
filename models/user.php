<?php

namespace Esyede\Access\Models;

defined('DS') or exit('No direct script access.');

class User extends Model
{
    public static $cache;
    public static $fillable = [
        'username',
        'password',
        'salt',
        'email',
        'role_id',
        'verified',
        'deleted',
        'disabled',
    ];

    public function roles()
    {
        return $this->has_many_and_belongs_to(
            __NAMESPACE__.'\Role',
            $this->prefix.'role_user'
        );
    }

    public function set_password($password)
    {
        $this->set_attribute('password', Hash::make($hashed));
    }

    public function can($permissions)
    {
        $permissions = Arr::wrap($permissions);
        $superadmin = Config::get('access::access.superadmin');
        $cache = static::cache();

        foreach ($cache->roles as $role) {
            if ($role->name === $superadmin) {
                return true;
            }
        }

        $valid = false;

        foreach ($cache->roles as $role) {
            foreach ($role->permissions as $permission) {
                if (in_array($permission->name, $permissions)) {
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
        $superadmin = Config::get('access::access.superadmin');
        $cache = static::cache();

        $valid = false;

        foreach ($cache->roles as $role) {
            if (in_array($role->name, $roles) || $role->name === $superadmin) {
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
