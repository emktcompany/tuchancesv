<?php

namespace App\TuChance\Models;

use Illuminate\Database\Eloquent\Model;

class ContactForm extends Model
{
    /**
     * @inheritDoc
     */
    protected $fillable = [
        'name', 'email', 'phone', 'message',
    ];
}
