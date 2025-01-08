<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
       Schema::create('gifts', function (Blueprint $table) {
           $table->id();
           $table->string('name');
           $table->decimal('price', 8, 2);
           $table->string('image_url');
           $table->boolean('is_purchased')->default(false);
           $table->timestamp('purchased_at')->nullable();
           $table->string('purchaser_name')->nullable();
           $table->string('purchaser_email')->nullable();
           $table->string('store')->nullable();
           $table->string('order_number')->nullable();
           $table->string('unique_code', 9)->nullable()->unique();
           $table->timestamps();
       });
    }

    // Gift.php model update
    protected $fillable = [
       'name',
       'price',
       'image_url',
       'is_purchased',
       'purchased_at',
       'purchaser_name',
       'purchaser_email',
       'store',
       'order_number',
       'unique_code'
    ];

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gifts');
    }
};
