<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeDependenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_dependencies', function (Blueprint $table)
        {
            $table->unsignedInteger('node_id')->comment('Node to be related');
            $table->unsignedInteger('dependent_node_id')->comment('Related node');
            $table->timestamps();
            $table->primary(['node_id', 'dependent_node_id']);
            $table->foreign('node_id')
                ->references('id')
                ->on('nodes')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('dependent_node_id')
                ->references('id')
                ->on('nodes')
                ->onDelete('cascade')
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
        Schema::dropIfExists('node_dependencies');
    }
}
