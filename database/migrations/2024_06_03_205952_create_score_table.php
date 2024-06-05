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
        // Schema::create('score', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('student_id');
        //     $table->unsignedBigInteger('exam_id');
        //     $table->integer('total');
        //     $table->timestamps();

        //     $table->foreign('student_id')->references('id')->on('student')->onDelete('cascade');
        //     $table->foreign('exam_id')->references('id')->on('exam')->onDelete('cascade');
        // });

        // Schema::create('answer', function (Blueprint $table){
        //     $table->id();
        //     $table->unsignedBigInteger('student_id');
        //     $table->unsignedBigInteger('exam_id');
        //     $table->text('question');
        //     $table->string('answer')->nullable();
        //     $table->boolean('status')->default(false);
        //     $table->timestamps();

        //     $table->foreign('student_id')->references('id')->on('student')->onDelete('cascade');
        //     $table->foreign('exam_id')->references('id')->on('exam')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('score');
    }
};