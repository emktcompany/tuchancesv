<?php

namespace App\Http\Resources;

class ProgramResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'participants_count' => $this->participants_count ?? 0,
            'is_active'          => $this->is_active,
            'created_at'         => $this->asDate('created_at'),
            'participants'       => ProgramParticipantResource::collection(
                $this->whenLoaded('participants')
            ),
        ];
    }
}
