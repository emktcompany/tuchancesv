<?php

namespace App\TuChance\Repositories;

use App\Http\Resources\OpportunityResource;
use App\TuChance\Contracts\Repositories\Opportunities as OpportunitiesContract;
use App\TuChance\Models\Opportunity;
use Illuminate\Http\Request;

class Opportunities implements OpportunitiesContract
{
    /**
     * Opportunity model
     * @var \App\TuChance\Models\Opportunity
     */
    protected $opportunities;

    /**
     * Create a new AuthController instance.
     * @param  \App\TuChance\Models\Opportunity $opportunities
     * @return void
     */
    public function __construct(Opportunity $opportunities)
    {
        $this->opportunities = $opportunities;
    }

    /**
     * Search opportunities
     * @param  \Illuminate\Http\Request $terms
     * @param  boolean                  $active
     * @param  int                      $per_page
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function search(
        Request $terms = null,
        $active = true,
        $per_page = 12
    ) {
        $query    = $this->baseQuery($active);
        $per_page = 12;

        if ($active && $terms && ($user = $terms->user())) {
            $query->where(function ($query) use ($user) {
                $program_ids = $user->programs()->pluck('programs.id');

                $query->whereNull('program_id');

                if ($program_ids->count() > 0) {
                    $query->orWhereIn('program_id', $program_ids);
                }
            });
        }

        if ($terms) {
            if ($terms->has('country')) {
                $query->byCountry($terms->get('country'));
            }

            if ($terms->has('category_id')) {
                $query->where('opportunities.category_id', $terms->get('category_id'));
            }

            if ($terms->has('subcategory_id')) {
                $query->where('opportunities.subcategory_id', $terms->get('subcategory_id'));
            }

            if ($terms->has('bidder_id')) {
                $query->where('opportunities.bidder_id', $terms->get('bidder_id'));
            }

            if ($terms->has('country_id')) {
                $query->where('opportunities.country_id', $terms->get('country_id'));
            }

            if ($terms->has('state_id')) {
                $query->where('opportunities.state_id', $terms->get('state_id'));
            }

            if ($terms->has('city_id')) {
                $query->where('opportunities.city_id', $terms->get('city_id'));
            }

            if ($terms->has('term')) {
                $query->search($terms->get('term'), null, true, true);
            } else {
                if ($active) {
                    $query->orderBy('is_featured', 'desc')
                        ->orderBy('created_at', 'desc');
                } else {
                    $query->latest();
                }
            }

            $per_page = (int) $terms->get('per_page', $per_page) ?: $per_page;
        }

        return OpportunityResource::collection($query->paginate($per_page));
    }

    /**
     * Find given opportunity
     * @param  \Illuminate\Http\Request $id
     * @param  boolean                  $active
     * @return \App\Http\Resources\OpportunityResource
     */
    public function find($id, $active = true)
    {
        $query = $this->baseQuery($active);
        $query->where('opportunities.slug', $id);
        $query->with('bidder.user.avatar');
        return new OpportunityResource($query->firstOrFail());
    }

    /**
     * View active opportunities the candidate has enrolled to
     * @param  int  $candidate_id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function enrolled($candidate_id)
    {
        $query = $this->baseQuery(true);

        $query->enrolled($candidate_id);

        return OpportunityResource::collection($query->paginate(12));
    }

    /**
     * Create a base query builder object
     * @param  boolean $active
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function baseQuery($active)
    {
        $query = $this->opportunities
            ->with('bidder', 'city', 'state', 'country', 'image', 'skills');

        if ($active) {
            $query->active();
        }

        return $query;
    }
}
