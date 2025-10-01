<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('node_edges', function (Blueprint $table) {
            $table->foreign('from_node_id')->references('id')->on('nodes')->onDelete('cascade');
            $table->foreign('to_node_id')->references('id')->on('nodes')->onDelete('cascade');
        });

        Schema::table('node_metas', function (Blueprint $table) {
            $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
        });

        Schema::table('node_groups', function (Blueprint $table) {
            $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
            $table->foreign('child_node_id')->references('id')->on('nodes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('node_edges', function (Blueprint $table) {
            $table->dropForeign(['from_node_id']);
            $table->dropForeign(['to_node_id']);
        });

        Schema::table('node_metas', function (Blueprint $table) {
            $table->dropForeign(['node_id']);
        });

        Schema::table('node_groups', function (Blueprint $table) {
            $table->dropForeign(['node_id']);
            $table->dropForeign(['child_node_id']);
        });
    }
};
