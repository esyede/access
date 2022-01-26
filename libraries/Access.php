<?php

namespace Esyede\Access\Libraries;

defined('DS') or exit('No direct script access.');

use System\Arr;
use System\Hash;
use System\Lang;
use System\Config;
use System\Auth\Drivers\Driver;

class Access extends Driver
{
    private $language;

    public function __construct()
    {
        parent::__construct();
        $this->language = Config::get('access::access.language');
        $this->user();
    }

    public function retrieve($id)
    {
        if (false !== filter_var($id, FILTER_VALIDATE_INT)) {
            return $this->model()->find($id);
        }
    }

    public function attempt($arguments = [])
    {
        $valid = false;
        $field = Config::get('auth.username');

        $user = $this->model()->where($field, '=', Arr::get($arguments, $field))->first();

        if (is_null($user)) {
            throw new Exceptions\UserNotFound(
                Lang::line('access::defaults.user_notfound', [], $this->language)
            );
        }

        if (! Hash::check(Arr::get($arguments, 'password'), $user->password)) {
            throw new Exceptions\WrongPassword(
                Lang::line('access::defaults.wrong_password', [], $this->language)
            );
        }

        if (! $user->verified) {
            throw new Exceptions\UserUnverified(
                Lang::line('access::defaults.unverified_user', [], $this->language)
            );
        }

        if ($user->disabled) {
            throw new Exceptions\UserDisabled(
                Lang::line('access::defaults.disabled_user', [], $this->language)
            );
        }

        if ($user->deleted) {
            throw new Exceptions\UserDeleted(
                Lang::line('access::defaults.deleted_user', [], $this->language)
            );
        }

        return $this->login($user->get_key(), Arr::get($arguments, 'remember'));
    }

    protected function model()
    {
        $model = Config::get('access::access.model');

        return is_null($model) ? null : new $model();
    }

    public function is($roles, $user = null)
    {
        $user = $this->populate($user);

        return is_null($user) ? false : $user->is($roles);
    }

    public function can($permissions, $user = null)
    {
        $user = $this->populate($user);

        return is_null($user) ? false : $user->can($permissions);
    }

    public function level($level, $modifier = '>=', $user = null)
    {
        $user = $this->populate($user);

        return is_null($user) ? false : $user->level($level, $modifier);
    }

    private function populate($user = null)
    {
        if (! is_null($user)) {
            if (is_numeric($user)) {
                $user = $this->retrieve($user);
            } elseif (! is_object($user)) {
                $user = null;
            }
        } else {
            $user = $this->user;
        }

        return $user;
    }
}
