<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Ai\Concerns\HasConversations;

class ChatVisitor extends Model
{
    use HasConversations;

    protected $fillable = [
        'uuid',
    ];
}