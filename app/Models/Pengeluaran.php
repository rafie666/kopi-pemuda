<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $fillable = [
        'user_id',
        'description',
        'amount',
        'date',
        'category',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
