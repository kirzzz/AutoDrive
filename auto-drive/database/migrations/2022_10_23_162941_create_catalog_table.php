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
        Schema::create('AutoCatalog', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idMark');
            $table->unsignedBigInteger('idModel');
            $table->unsignedBigInteger('idSpecification');
            $table->unsignedBigInteger('idEngineType');
            $table->unsignedBigInteger('idTransmission');
            $table->unsignedBigInteger('idGearType');
            $table->integer('idGeneration');
            $table->timestamps();

            $table->foreign('idMark','AutoCatalogIdMark_MarkID')
                ->references('id')->on('Mark');
            $table->foreign('idModel','AutoCatalogIdModel_ModelID')
                ->references('id')->on('Model');
            $table->foreign('idSpecification','AutoCatalogIdSpecification_SpecificationID')
                ->references('id')->on('Specification');
            $table->foreign('idEngineType','AutoCatalogIdEngineType_EngineTypeID')
                ->references('id')->on('EngineType');
            $table->foreign('idTransmission','AutoCatalogIdTransmission_TransmissionID')
                ->references('id')->on('Transmission');
            $table->foreign('idGearType','AutoCatalogIdGearType_GearTypeID')
                ->references('id')->on('GearType');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('AutoCatalog');
    }
};
