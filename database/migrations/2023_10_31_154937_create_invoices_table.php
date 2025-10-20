<?php

use App\Enums\PaymentStatuses;
use App\Models\Base\{
    InvoiceFile,
    PaymentType,
};
use App\Models\User;
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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignIdFor(User::class, 'buyer')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->tinyInteger('payment_status')->default(PaymentStatuses::PENDING);
            $table->date('issuance_date');
            $table->foreignIdFor(PaymentType::class, 'payment_type_id')->constrained('payment_types')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('exchange_rate', 8, 2);
            $table->foreignIdFor(InvoiceFile::class, 'invoice_file')->constrained('invoice_files')->cascadeOnUpdate()->cascadeOnDelete()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
