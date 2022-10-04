<?php

namespace App\Imports;

use App\TuChance\Models\User;
use App\TuChance\Models\Program;
use App\TuChance\Models\ProgramParticipant;

class ImportProgramParticipants extends BaseImport
{
    /**
     * Program to wich import the participants
     * @var \App\TuChance\Models\Program
     */
    protected $program;

    /**
     * @param \App\TuChance\Models\Program $program
     */
    public function __construct(Program $program)
    {
        $this->program = $program;
    }
    /**
     * @param  array  $row
     * @param  int    $index
     * @return void
     */
    protected function onRow($row, $index)
    {
        $particpant = (new ProgramParticipant)->firstOrCreate([
            'program_id' => $this->program->id,
            'email'      => $row['email'],
        ]);

        $user = (new User)->where('email', $row['email'])->first();
        if ($user) {
            $user->programs()->syncWithoutDetaching([$this->program->id]);
        }
    }
}
