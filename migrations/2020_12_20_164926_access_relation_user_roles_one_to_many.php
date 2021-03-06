<?php

defined('DS') or exit('No direct script access.');

class Access_Relation_User_Roles_One_To_Many
{
    private $prefix;

    public function __construct()
    {
        $prefix = Config::get('access::access.prefix', null);
        $prefix = is_null($prefix) ? '' : trim(rtrim($prefix, '_')).'_';

        $this->prefix = $prefix;
    }

    public function up()
    {
        $prefix = $this->prefix;

        Schema::create($prefix.'role_user', function ($table) use ($prefix) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('role_id')->unsigned()->index();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on($prefix.'users');
            $table->foreign('role_id')->references('id')->on($prefix.'roles');
        });

        $users = DB::table($prefix.'users')->get();

        foreach ($users as $user) {
            DB::table($prefix.'role_user')->insert([
                'user_id' => $user->id,
                'role_id' => $user->role_id,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);
        }

        Schema::table($prefix.'users', function ($table) use ($prefix) {
            $table->drop_foreign($prefix.'users_role_id_foreign');
            $table->drop_index($prefix.'users_role_id_index');
            $table->drop_column('role_id');
        });
    }

    public function down()
    {
        $prefix = $this->prefix;

        Schema::table($prefix.'users', function ($table) use ($prefix) {
            $table->integer('role_id')->unsigned()->index();
        });

        $users = DB::table($prefix.'users')->get();

        foreach ($users as $user) {
            $role = DB::table($prefix.'role_user')
                ->where('user_id', '=', $user->id)
                ->order_by('created_at', 'DESC')
                ->first();

            DB::table($prefix.'users')
                ->where('id', '=', $user->id)
                ->update(['role_id' => $role->role_id]);
        }

        Schema::table($prefix.'users', function ($table) use ($prefix) {
            $table->foreign('role_id')->references('id')->on($prefix.'roles');
        });

        Schema::drop_if_exists($prefix.'role_user');
    }
}
