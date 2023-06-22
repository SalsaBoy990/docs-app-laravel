<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts_categories', function (Blueprint $table) {
            $table->unsignedBigInteger( 'post_id' );
            $table->unsignedBigInteger( 'category_id' );

            $table->foreign( 'post_id' )
                  ->references( 'id' )
                  ->on( 'posts' )
                  ->onDelete( 'cascade' );

            $table->foreign( 'category_id' )
                  ->references( 'id' )
                  ->on( 'categories' )
                  ->onDelete( 'cascade' );

            $table->primary( [ 'post_id', 'category_id' ] );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts_categories');
    }
};
