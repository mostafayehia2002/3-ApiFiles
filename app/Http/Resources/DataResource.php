<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'url' => asset('Files/'.$this->name),
            'name'=>$this->name,
            'type'=>$this->type,
            'extension'=>$this->extension,
            'size'=>$this->size.'MB',
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
        ];
    }
}
