<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('courier_name')->nullable()->after('status'); // Reksy, Adit, dll
            $table->string('courier_message')->nullable()->after('courier_name'); // Pesan update status
            $table->string('payment_method')->default('Card')->after('price');
            $table->string('promo_code')->nullable()->after('payment_method');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['courier_name', 'courier_message', 'payment_method', 'promo_code', 'discount_amount']);
        });
    }
};
