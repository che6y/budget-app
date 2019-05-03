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
        $this->icon = $this->category->icon;
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'cost'       => $this->cost,
            'amount'     => $this->amount,
            'user_id'    => $this->user_id,
            'category_id'=> $this->category_id,
            'created_at' => $this->created_at,
            'icon'       => $this->icon,
        ];
    }
}
