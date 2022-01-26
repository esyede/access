<?php

defined('DS') or exit('No direct script access.');

class Access_Access_Seed_Role_User_Table
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
        $prefix = $this->prefix;
        $users = DB::table($prefix.'users')->get();
        $accounts = [];

        foreach ($users as $user) {
            $accounts[] = [
                'user_id' => $user->id,
                'role_id' => $user->role_id,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];
        }

        DB::table($prefix.'role_user')->insert($accounts);
    }

    public function down()
    {
        // ..
    }
}
