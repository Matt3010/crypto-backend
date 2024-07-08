<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTMovimenti extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TMovimenti', function (Blueprint $table) {
            $table->id('MovimentoID'); // Numero intero generato dal sistema (auto-incremento, chiave primaria)
            $table->integer('ContoTradingID'); // Foreign key verso TContoTrading
            $table->date('DataMovimento'); // Data del movimento
            $table->integer('TipoMovimento'); // Tipo di movimento (1=Vendita di criptovalute, 2=Acquisto di criptovalute)
            $table->string('CriptoValutaID'); // Identificativo della criptovaluta
            $table->decimal('Quantita', 24, 10); // Quantità della criptovaluta (precision: 18, scale: 8)
            $table->decimal('PrezzoUnitario', 24, 10);
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
        Schema::dropIfExists('_t_movimenti');
    }
}
