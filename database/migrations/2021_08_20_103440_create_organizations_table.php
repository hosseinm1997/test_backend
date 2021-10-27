<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->text('description');

            $table->integer('type');
            $table->integer('category');

            $table->integer('created_by');
            $table->integer('owner_user_id');

            $table->string('telephone', 20);
            $table->string('address', 500);
            $table->string('website', 100)->nullable();
            $table->integer('logo_file_id');

            $table->string('manager_name', 50);
            $table->string('secretary_name', 50);
            $table->json('directors')->nullable();

            $table->integer('manager_user_id')->nullable();
            $table->integer('secretary_user_id')->nullable();

            $table->dateTime('established_at')->nullable();
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
        Schema::dropIfExists('organizations');
    }
}
