<?php

namespace App\TuChance\Models;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class ProgramParticipant extends Model
{
    use SearchableTrait;

    /**
     * @inheritdoc
     */
    protected $fillable = ['email', 'program_id'];

    /**
     * Searchable rules.
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'email' => 10,
        ]
    ];

    /**
     * Program the participant is in
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
}
