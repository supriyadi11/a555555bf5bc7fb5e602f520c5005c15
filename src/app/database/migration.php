<?php
error_reporting(E_ERROR | E_PARSE);
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/boot.php';
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

DB::schema()->create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->timestamps();
});

DB::schema()->create('mails', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');
    $table->text('to');
    $table->string('subject')->nullable();
    $table->text('body')->nullable();
    $table->enum('status', ['draf', 'send']);
    $table->dateTime('send_at')->nullable();
    $table->timestamps();
    $table->foreign('user_id')->references('id')->on('users');
});

DB::schema()->create('queues', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->string('name')->index();
    $table->longText('payload');
    $table->unsignedTinyInteger('attempts');
    $table->dateTime('success_at')->nullable();
    $table->dateTime('failed_at')->nullable();
    $table->timestamps();
});