<?php

namespace App\Http\Resources;

class ProgramParticipantResource extends BaseResource
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
            'id'         => $this->id,
            'email'      => $this->email,
            'created_at' => $this->asDate('created_at'),
            'program'    => new ProgramResource($this->whenLoaded('program')),
        ];
    }
}
