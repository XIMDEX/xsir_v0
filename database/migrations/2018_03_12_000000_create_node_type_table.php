<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')
                ->comment('Class name of parent node type')
                ->nullable();
            $table->string('type', 255)
                ->comment('Class name of node type');
            $table->string('namespace', 255)
                ->comment('Namespace of node type');
            $table->timestamps();

            $table->unique(['type', 'parent_id']);
            
            $table->foreign('parent_id')
            ->references('id')
            ->on('node_types')
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
        Schema::dropIfExists('node_types');
    }
}
