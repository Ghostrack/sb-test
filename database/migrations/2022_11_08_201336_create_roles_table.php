<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->unsignedTinyInteger('id');
            $table->tinyText('name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('role_id')->after('id');
        });

        Role::create([
            'id'   => 1,
            'name' => 'Admin',
        ]);
        Role::create([
            'id'   => 2,
            'name' => 'User',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role_id');
        });
    }
};
