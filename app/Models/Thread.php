<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Thread extends Model
{
    use HasFactory;

    protected $table = 'threads';

    protected $fillable = [
        'ticket_id',
        'description',
        'send_type',
        'sender_user_id',
        'attachment_file_id'
    ];
}
