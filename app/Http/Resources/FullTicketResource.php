<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FullTicketResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => !is_null($this->created_by) ? [
                'id' => $this->createdBy->id,
                'first_name' => $this->createdBy->first_name,
                'last_name' => $this->createdBy->last_name,
            ] : null,
            'organization' => !is_null($this->organization_id) ? [
                'id' => $this->organization_id,
                'title' => $this->organization->title
            ] : null,
            'assigned' => !is_null($this->assigned_to) ? [
                'id' => $this->assignedTo->id,
                'first_name' => $this->assignedTo->first_name,
                'last_name' => $this->assignedTo->last_name,
            ] : null,
            'threads' => ThreadResource::collection($this->threads)
        ];
    }
}
