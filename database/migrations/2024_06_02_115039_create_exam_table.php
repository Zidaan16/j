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
        Schema::create('exam', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('classroom_id');
            $table->string('title');
            $table->boolean('is_expired')->default(false);
            $table->timestamps();

            $table->foreign('classroom_id')->references('id')->on('classroom')->onDelete('cascade');
        });

        Schema::create('question', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('exam_id');
            $table->mediumInteger('point');
            $table->string('images')->nullable();
            $table->text('description');
            $table->string('option_1')->nullable();
            $table->string('option_2')->nullable();
            $table->string('option_3')->nullable();
            $table->string('option_4')->nullable();
            $table->string('correct_answer')->nullable();
            $table->boolean('auto')->default(true);
            $table->timestamps();

            $table->foreign('exam_id')->references('id')->on('exam')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam');
    }
};
