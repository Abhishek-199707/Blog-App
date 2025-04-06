<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyContentColumnInBlogsTable extends Migration
{
    public function up()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->longText('content')->change(); // Change the column type to LONGTEXT
        });
    }

    public function down()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('content')->change(); // Revert back if needed
        });
    }
}
