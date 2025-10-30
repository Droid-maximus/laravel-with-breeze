<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();     // INV-0001 u.tml.
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            $table->string('currency', 3)->default('EUR');
            $table->string('status')->default('draft'); // 'draft'|'sent'|'paid'|'cancelled'

            // kopsummas (glabājam, lai ātri rādīt sarakstos)
            $table->decimal('total_net', 12, 2)->default(0);
            $table->decimal('total_vat', 12, 2)->default(0);
            $table->decimal('total_gross', 12, 2)->default(0);

            // momentuzņēmums (snapshot) par klientu rēķina brīdī
            $table->string('buyer_name');
            $table->string('buyer_reg_no')->nullable();
            $table->string('buyer_vat_no')->nullable();
            $table->string('buyer_address')->nullable();

            $table->timestamps();
            $table->index(['client_id', 'issue_date']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('invoices');
    }
};
