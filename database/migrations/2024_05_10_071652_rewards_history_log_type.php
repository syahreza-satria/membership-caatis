<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RewardsHistoryLogType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rewards_history_log_type', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description');
            $table->timestamps();
        });

        DB::table("rewards_history_log_type")->insert([
            "name" => "Tambah",
            "description" => "Menambahkan data poin",
            "created_at" => now(),
            "updated_at" => now()
        ]);

        DB::table("rewards_history_log_type")->insert([
            "name" => "Kurang",
            "description" => "Mengurangi data poin",
            "created_at" => now(),
            "updated_at" => now()
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rewards_history_log_type');
    }
}
