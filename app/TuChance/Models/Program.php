<?php

namespace App\TuChance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class Program extends Model
{
    use SoftDeletes, SearchableTrait;

    /**
     * @inheritdoc
     */
    protected $fillable = ['name', 'is_active'];

    /**
     * Searchable rules.
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'name' => 10,
        ]
    ];

    /**
     * Email participants
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participants()
    {
        return $this->hasMany(ProgramParticipant::class, 'program_id');
    }
}
