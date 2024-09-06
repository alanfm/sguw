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
        Schema::create('client_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('file');
            $table->boolean('keep_clients')->default(false);
            $table->integer('total_records')->default(0);
            $table->integer('inserted_records')->default(0);
            $table->text('observations')->nullable();
            $table->foreignId('client_bond_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_uploads');
    }
};
