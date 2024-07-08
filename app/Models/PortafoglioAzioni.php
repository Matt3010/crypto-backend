<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortafoglioAzioni extends Model
{
    use HasFactory;

    protected $table = 'portafoglio_azioni';

    protected $primaryKey = 'ID';

    protected $fillable = [
        'ContoTradingID',
        'CriptoValutaID',
        'Quantita'
    ];

    public function contoTrading()
    {
        return $this->belongsTo(TContoTrading::class, 'ContoTradingID', 'ContoTradingID');
    }
}
