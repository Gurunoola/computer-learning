<?php

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
    Schema::table('qnas', function (Blueprint $table) {
        //$table->foreignId('topic_id')->after('id')->constrained()->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('qnas', function (Blueprint $table) {
        $table->dropForeign(['topic_id']);
        $table->dropColumn('topic_id');
    });
}

};
