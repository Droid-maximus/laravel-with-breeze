<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'person' | 'company'
            $table->string('name'); // vārds vai uzņēmuma nosaukums
            $table->string('reg_no')->nullable(); // personas kods / reģ. nr.
            $table->string('vat_no')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();

            $table->index(['type', 'name']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('clients');
    }
};
