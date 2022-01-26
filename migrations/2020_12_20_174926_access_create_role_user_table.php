<?php

defined('DS') or exit('No direct script access.');

class Access_Access_Create_Role_User_Table
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

        Schema::create($prefix.'role_user', function ($table) use ($prefix) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('role_id')->unsigned()->index();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on($prefix.'users');
            $table->foreign('role_id')->references('id')->on($prefix.'roles');
        });
    }

    public function down()
    {
        $prefix = $this->prefix;
        Schema::disable_fk_checks($prefix.'role_user');
        Schema::drop_if_exists($prefix.'role_user');
        Schema::enable_fk_checks($prefix.'role_user');
    }
}
