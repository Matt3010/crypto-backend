<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TContoTrading extends Model
{
    use HasFactory;

    protected $table = 'TContoTrading';

    protected $primaryKey = 'ContoTradingID';

    protected $fillable = [
        'Titolare',
        'Saldo'
    ];

    public function movimenti()
    {
        return $this->hasMany(TMovimenti::class, 'ContoTradingID', 'ContoTradingID');
    }

    public function portafoglioAzioni()
    {
        return $this->hasMany(PortafoglioAzioni::class, 'ContoTradingID', 'ContoTradingID');
    }
}
