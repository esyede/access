<?php

namespace Esyede\Access\Models;

defined('DS') or exit('No direct script access.');

use Arr;
use Hash;

class User extends Model
{
    /**
     * Cache role per user, dikunci dengan id user.
     *
     * @var array
     */
    public static $cache = [];

    public static $fillable = [
        'name',
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
        $roles = $this->cached_roles();

        foreach ($roles as $role) {
            if ($role->slug === 'admin') {
                return true;
            }
        }

        $valid = false;

        foreach ($roles as $role) {
            foreach ((array) $role->permissions as $permission) {
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

        $valid = false;

        foreach ($this->cached_roles() as $role) {
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
        $max = -1;
        $min = 100;
        $levels = [];

        foreach ($this->cached_roles() as $role) {
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

    /**
     * Ambil daftar role milik user ini.
     *
     * Hasilnya disimpan per id user, bukan satu cache untuk semua user,
     * agar pengecekan hak akses user berikutnya tidak memakai data user
     * sebelumnya.
     *
     * @return array
     */
    private function cached_roles()
    {
        $id = $this->get_attribute('id');

        if (is_null($id)) {
            return [];
        }

        if (!isset(static::$cache[$id])) {
            $class = get_class($this);
            $user = $class::with(['roles', 'roles.permissions'])->where_id($id)->first();
            static::$cache[$id] = is_null($user) ? [] : (array) $user->roles;
        }

        return static::$cache[$id];
    }
}
