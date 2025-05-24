<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('producto', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->decimal('precio', 10, 2);
            $table->integer('stock');
            $table->string('imagen', 255)->nullable();
            $table->foreignId('categoria_id')->constrained('categoria');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('producto');
    }
};