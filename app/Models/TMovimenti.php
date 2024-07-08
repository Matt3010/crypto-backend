<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TMovimenti extends Model
{
    use HasFactory;

    protected $table = 'TMovimenti';

    protected $primaryKey = 'MovimentoID';

    protected $fillable = [
        'ContoTradingID',
        'DataMovimento',
        'TipoMovimento',
        'CriptoValutaID',
        'Quantita',
        'PrezzoUnitario'
    ];

    public function contoTrading()
    {
        return $this->belongsTo(TContoTrading::class, 'ContoTradingID', 'ContoTradingID');
    }
}
