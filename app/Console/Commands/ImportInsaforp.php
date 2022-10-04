<?php

namespace App\Console\Commands;

use App\Console\MtpsCommand;
use App\TuChance\Models\Opportunity;
use Carbon\Carbon;

class ImportInsaforp extends MtpsCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tuchance:import:insaforp';

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
        $bidder_id  = 26;
        $country_id = 2;

        $params = [
            'filter' => [
                'available_at_scp' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];

        $this->import('courses', $params, function ($row) {
            $opportunity = (new Opportunity)->where('import_id', $row->id)
                ->firstOrNew([]);

            $opportunity->category_id     = 1;
            $opportunity->country_id      = 2;
            $opportunity->bidder_id       = 26;
            $opportunity->name            = $row->name;
            $opportunity->summary         = $row->summary;
            $opportunity->characteristics = $row->characteristics;
            $opportunity->description     = $row->description;
            $opportunity->requirements    = $row->requirements;
            $opportunity->begin_at        = new Carbon($row->starts_at);
            $opportunity->finish_at       = new Carbon($row->ends_at);
            $opportunity->name            = $row->name;
            $opportunity->is_active       = 1;
            $opportunity->is_insaforp     = 1;
            $opportunity->import_id       = $row->id;

            $opportunity->save();

            $this->info("Imported course: {$row->name}");
        });
    }
}
