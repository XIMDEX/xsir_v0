<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nodes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')
                ->comment('Parent node in the tree view')
                ->nullable();
            $table->unsignedInteger('node_type_id')
                ->comment('The node type relation');
            $table->string('name', 255)
                ->comment('The node name to view in tree');
            $table->timestamps();

            $table->unique(['name', 'parent_id']);

            $table->foreign('node_type_id')
                ->references('id')
                ->on('node_types')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            
            $table->foreign('parent_id')
                ->references('id')
                ->on('nodes')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nodes');
    }
}
