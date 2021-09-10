<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->string('title');
            $table->text('description');
            $table->decimal('lat', 11, 8);
            $table->decimal('lng', 11, 8);
            $table->text('address_line1');
            $table->text('address_line2')->nullable();
            $table->tinyInteger('approval_status')->default(1);
            $table->boolean('hidden')->default(false);
            $table->integer('price_per_day');
            $table->integer('monthly_discount')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('offices_tags', function (Blueprint $table) {
            $table->foreignId('office_id')->index();
            $table->foreignId('tag_id')->index();

            $table->unique(['office_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offices');
    }
}
