<?php

defined('DS') or exit('No direct script access.');

class Access_Create_Tables
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
        Schema::create($this->prefix.'permissions', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 100)->index();
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });

        Schema::create($this->prefix.'roles', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 100)->index();
            $table->string('description', 255)->nullable();
            $table->integer('level');
            $table->timestamps();
        });

        Schema::create($this->prefix.'users', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('username', 30)->index();
            $table->string('password', 60)->index();
            $table->string('email', 255)->index();
            $table->integer('role_id')->unsigned()->index();
            $table->boolean('verified');
            $table->boolean('disabled');
            $table->boolean('deleted');
            $table->timestamps();
            $table->foreign('role_id')->references('id')->on($this->prefix.'roles');
        });

        Schema::create($this->prefix.'permission_role', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('permission_id')->unsigned()->index();
            $table->integer('role_id')->unsigned()->index();
            $table->timestamps();
            $table->foreign('permission_id')->references('id')->on($this->prefix.'permissions');
            $table->foreign('role_id')->references('id')->on($this->prefix.'roles');
        });

        DB::table($this->prefix.'roles')->insert([
            'name' => Config::get('access::access.superadmin'),
            'level' => 10,
            'created_at' => Date::now(),
            'updated_at' => Date::now(),
        ]);

        DB::table($this->prefix.'users')->insert([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role_id' => 1,
            'email' => 'admin@situsku.com',
            'created_at' => Date::now(),
            'updated_at' => Date::now(),
            'verified' => 1,
            'disabled' => 0,
            'deleted' => 0,
        ]);
    }

    public function down()
    {
        Schema::drop_if_exists($this->prefix.'permission_role');
        Schema::drop_if_exists($this->prefix.'users');
        Schema::drop_if_exists($this->prefix.'roles');
        Schema::drop_if_exists($this->prefix.'permissions');
    }
}
