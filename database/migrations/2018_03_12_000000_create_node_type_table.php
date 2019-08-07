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
                ->default(0)
                ->comment('Class name of parent node type');
            $table->string('type', 255)
                ->comment('Class name of node type');
            $table->string('namespace', 255)
                ->comment('Namespace of node type');
            $table->timestamps();

            $table->unique(['type', 'parent_id']);
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
