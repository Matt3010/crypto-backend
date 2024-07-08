<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortafoglioAzioniTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portafoglio_azioni', function (Blueprint $table) {
            $table->id('ID'); // Numero intero generato dal sistema (auto-incremento, chiave primaria)
            $table->integer('ContoTradingID'); // Foreign key verso TContoTrading
            $table->string('CriptoValutaID'); // Identificativo della criptovaluta
            $table->decimal('Quantita', 24, 10); //Quantità della criptovaluta
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('ContoTradingID')->references('ContoTradingID')->on('TContoTrading')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portafoglio_azioni');
    }
}
