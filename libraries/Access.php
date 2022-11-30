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
        $user = $this->model()->where('email', '=', Arr::get($arguments, 'email'))->first();

        if (is_null($user)) {
            throw new Exceptions\UserNotFound(
                Lang::line('access::defaults.user_notfound')->get($this->language)
            );
        }

        if (! Hash::check(Arr::get($arguments, 'password'), $user->password)) {
            throw new Exceptions\WrongPassword(
                Lang::line('access::defaults.wrong_password')->get($this->language)
            );
        }

        if (! $user->verified_at) {
            throw new Exceptions\UserUnverified(
                Lang::line('access::defaults.unverified_user')->get($this->language)
            );
        }

        if ($user->disabled_at) {
            throw new Exceptions\UserDisabled(
                Lang::line('access::defaults.disabled_user')->get($this->language)
            );
        }

        if ($user->deleted_at) {
            throw new Exceptions\UserDeleted(
                Lang::line('access::defaults.deleted_user')->get($this->language)
            );
        }

        return $this->login($user->get_key(), Arr::get($arguments, 'remember'));
    }

    protected function model()
    {
        $model = Config::get('access::access.model');

        return is_null($model) ? null : new $model();
    }

    public function has_role($roles, $user = null)
    {
        $user = $this->populate($user);

        return is_null($user) ? false : $user->has_role($roles);
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
