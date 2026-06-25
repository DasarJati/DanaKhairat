<?php
// database/migrations/xxxx_xx_xx_add_columns_to_activity_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToActivityLogsTable extends Migration
{
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('ip_address', 45)->nullable()->after('description');
            $table->text('user_agent')->nullable()->after('ip_address');
            $table->string('url')->nullable()->after('user_agent');
            $table->string('method', 10)->nullable()->after('url');
            $table->json('additional_data')->nullable()->after('method');

            // Add indexes for better performance
            $table->index('entity_type');
            $table->index('created_at');
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'user_agent', 'url', 'method', 'additional_data']);
            $table->dropIndex(['entity_type']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['user_id', 'created_at']);
        });
    }
}