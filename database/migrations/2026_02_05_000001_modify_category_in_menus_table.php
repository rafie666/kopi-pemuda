<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            // Modify category to be a string instead of enum for flexibility
            $table->string('category')->change();
        });
    }

    public function down()
    {
        Schema::table('menus', function (Blueprint $table) {
            // Revert back to enum if necessary (approximate)
            $table->enum('category', ['makanan', 'minuman', 'snack'])->change();
        });
    }
};
