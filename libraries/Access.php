<?php

namespace Esyede\Access\Libraries;

defined('DS') or exit('No direct script access.');

use Arr;
use Hash;
use Config;

class Access extends \Authenticator
{
    public function __construct()
    {
        parent::__construct();
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

        $fields = Config::get('access::access.column');
        $fields = Arr::wrap($fields);

        foreach ($fields as $field) {
            $user = $this->model()
                ->where($field, '=', Arr::get($arguments, $field))
                ->first();

            if (! is_null($user)) {
                if (! Hash::check(Arr::get($arguments, 'password'), $user->password)) {
                    throw new Exceptions\WrongPassword('User password is incorrect');
                }

                if (! $user->verified) {
                    throw new Exceptions\UserUnverified('User is unverified');
                }

                if ($user->disabled) {
                    throw new Exceptions\UserDisabled('User is disabled');
                }

                if ($user->deleted) {
                    throw new Exceptions\UserDeleted('User is deleted');
                }

                $valid = true;
                break;
            }
        }

        if ($valid) {
            return $this->login($user->get_key(), Arr::get($arguments, 'remember'));
        }
        throw new Exceptions\UserNotFound('User can not be found');
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
