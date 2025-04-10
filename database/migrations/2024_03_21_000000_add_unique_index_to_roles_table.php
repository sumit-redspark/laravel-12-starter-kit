<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up()
     {
          Schema::table('roles', function (Blueprint $table)
          {
               $table->unique(['name', 'created_by'], 'roles_name_created_by_unique');
          });
     }

     public function down()
     {
          Schema::table('roles', function (Blueprint $table)
          {
               $table->dropUnique('roles_name_created_by_unique');
          });
     }
};
