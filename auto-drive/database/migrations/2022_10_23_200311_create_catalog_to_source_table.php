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
        Schema::create('AutoCatalogSource', function (Blueprint $table) {
            $table->unsignedBigInteger('idCatalog');
            $table->unsignedBigInteger('idSource');
            $table->timestamps();

            $table->foreign('idCatalog','AutoCatalogSourceIdCatalog_AutoCatalogId')
                ->references('id')->on('AutoCatalog');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog_to_source');
    }
};
