<?php

defined('DS') or exit('No direct script access.');

class Access_Access_Seed_Users_Table
{
    private $prefix;

    public function __construct()
    {
        $prefix = Config::get('access::access.prefix', null);
        $prefix = is_null($prefix) ? '' : trim(rtrim($prefix, '_')).'_';
        $prefix = Str::replace_first('_', '', $prefix);

        $this->prefix = $prefix;
    }

    public function up()
    {
        $users = [
            [
                'fullname' => 'Admin Account',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'email' => 'admin@gmail.com',
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
                'verified_at' => Date::now(),
                'disabled_at' => null,
                'deleted_at' => null,
            ],
            [
                'fullname' => 'Staff Account',
                'username' => 'staff',
                'password' => Hash::make('password'),
                'role_id' => 2,
                'email' => 'staff@gmail.com',
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
                'verified_at' => Date::now(),
                'disabled_at' => null,
                'deleted_at' => null,
            ],
            [
                'fullname' => 'Member Account',
                'username' => 'member',
                'password' => Hash::make('password'),
                'role_id' => 3,
                'email' => 'member@gmail.com',
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
                'verified_at' => Date::now(),
                'disabled_at' => null,
                'deleted_at' => null,
            ],
        ];

        DB::table($this->prefix.'users')->insert($users);
    }

    public function down()
    {
        // ..
    }
}
