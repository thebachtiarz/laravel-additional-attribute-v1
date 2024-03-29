<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use TheBachtiarz\AdditionalAttribute\Interfaces\Model\AdditionalAttributeModelInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_attributes', function (Blueprint $table) {
            $table->id();
            $table->morphs('modelable');
            $table->string(AdditionalAttributeModelInterface::ADDITIONAL_ATTRIBUTE_NAME);
            $table->string(AdditionalAttributeModelInterface::ADDITIONAL_ATTRIBUTE_TYPE);
            $table->text(AdditionalAttributeModelInterface::ADDITIONAL_ATTRIBUTE_VALUE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('additional_attributes');
    }
};
