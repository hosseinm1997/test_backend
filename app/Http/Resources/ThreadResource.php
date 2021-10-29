<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThreadResource extends JsonResource
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
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'send_type' => $this->send_type,
            'send_user' => !is_null($this->sender_user_id) ? [
                'id' => $this->sendUserRelation->id,
                'first_name' => $this->sendUserRelation->first_name,
                'last_name' => $this->sendUserRelation->last_name,
            ] : null,
            'file' => !is_null($this->attachment_file_id)
                ? route('document.show', ['id' => $this->attachment_file_id])
                : null,
        ];
    }
}
