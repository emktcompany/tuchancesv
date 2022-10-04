<?php

namespace App\Console\Commands;

use App\Console\MtpsCommand;
use App\TuChance\Models\Opportunity;
use Carbon\Carbon;

class ImportMtps extends MtpsCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tuchance:import:mtps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $params = [
            'filter' => [
                'default_scp' => 1,
                'active_eq'   => 1,
            ],
        ];

        $this->import('vacancies', $params, function ($row) {
            dd($row);
            $opportunity = (new Opportunity)->where('import_id', $row->id)
                ->firstOrNew([]);

            $opportunity->country_id      = 2;
            $opportunity->bidder_id       = 24;
            $opportunity->name            = $row->position_offered;
            $opportunity->summary         = $row->summary;
            $opportunity->characteristics = $row->characteristics;
            $opportunity->description     = $row->description;
            $opportunity->requirements    = $row->requirements;
            $opportunity->starts_at       = new Carbon($row->starts_at);
            $opportunity->ends_at         = new Carbon($row->ends_at);
            $opportunity->name            = $row->name;
            $opportunity->import_id       = $row->id;

            $opportunity->save();

            $this->info("Imported course: {$row->name}");
        });
    }
}
