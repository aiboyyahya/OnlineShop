<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignMessage extends Model
{
    protected $fillable = [
        'customer_id',
        'title',
        'message',
        'status',
        'fonnte_response',
        'sent_at',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class);
    }
}
