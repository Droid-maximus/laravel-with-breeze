<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('line_no');      // 1,2,3...
            $table->string('description');           // prece/pakalpojums
            $table->string('unit')->default('pcs');  // mērv.
            $table->decimal('qty', 12, 3);
            $table->decimal('unit_price', 12, 4);
            $table->decimal('discount', 12, 4)->default(0); // vienības atlaide
            $table->decimal('net_amount', 12, 2);    // aprēķināts
            $table->decimal('vat_rate', 5, 2)->default(21);
            $table->decimal('vat_amount', 12, 2);    // aprēķināts
            $table->decimal('gross_amount', 12, 2);  // aprēķināts
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('invoice_items');
    }
};
