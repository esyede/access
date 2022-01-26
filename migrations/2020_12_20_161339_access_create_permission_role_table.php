<?php

defined('DS') or exit('No direct script access.');

class Access_Access_Create_Permission_Role_Table
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
        Schema::create($this->prefix.'permission_role', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('permission_id')->unsigned()->index();
            $table->integer('role_id')->unsigned()->index();
            $table->timestamps();
            $table->foreign('permission_id')->references('id')->on($this->prefix.'permissions');
            $table->foreign('role_id')->references('id')->on($this->prefix.'roles');
        });
    }

    public function down()
    {
        $prefix = $this->prefix;

        Schema::disable_fk_checks($prefix.'permission_role');
        Schema::drop_if_exists($prefix.'permission_role');
        Schema::enable_fk_checks($prefix.'permission_role');
    }
}
