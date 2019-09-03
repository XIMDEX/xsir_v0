<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVersionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('node_id')
                ->comment('id from node relation');
            $table->integer('major')
                ->default(0)
                ->comment('Major version number');
            $table->integer('minor')
                ->default(0)
                ->comment('Minor version number');
            $table->string('file', 255)
                ->unique()
                ->comment('Node content file');
            $table->timestamps();

            $table->unique(['node_id', 'major', 'minor']);

            $table->foreign('node_id')
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
        Schema::dropIfExists('versions');
    }
}
