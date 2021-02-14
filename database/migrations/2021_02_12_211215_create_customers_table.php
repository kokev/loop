<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('job_title')->comment('Customer job title');
            $table->string('email_address')->unique()->comment('Customer email address');
            $table->string('first_name')->comment('Custromer first name');
            $table->string('last_name')->comment('Customer last name');
            $table->date('registered_since')->comment('Customer registration date');
            $table->string('phone',20)->comment('Customer phone number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
