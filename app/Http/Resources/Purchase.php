<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Purchase extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->created_at = date('j M y', strtotime($this->created_at));
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'cost'       => $this->cost,
            'amount'     => $this->amount,
            'created_at' => $this->created_at,
        ];
    }
}
