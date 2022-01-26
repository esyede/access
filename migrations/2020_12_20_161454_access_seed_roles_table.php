<?php

defined('DS') or exit('No direct script access.');

class Access_Access_Seed_Roles_Table
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
        $roles = [
            [
                'name' => 'Admin Role',
                'slug' => 'admin-role',
                'deletable' => 0,
                'description' => 'Full access',
                'level' => 10,
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ],
            [
                'name' => 'Staff Role',
                'slug' => 'staff-role',
                'deletable' => 1,
                'description' => 'Staff access',
                'level' => 5,
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ],
            [
                'name' => 'Member Role',
                'slug' => 'member-role',
                'deletable' => 1,
                'description' => 'Member access',
                'level' => 2,
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ],
        ];

        DB::table($this->prefix.'roles')->insert($roles);
    }

    public function down()
    {
        // ..
    }
}
