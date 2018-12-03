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
                ->default(0)
                ->comment('Parent node in the tree view');
            $table->string('type', 255)
                ->comment('Class name of node type');
            $table->string('name', 255)
                ->comment('The node name to view in tree');
            $table->timestamps();

            $table->unique(['name', 'parent_id']);
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
