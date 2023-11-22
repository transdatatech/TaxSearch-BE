<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileBatchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "batch_no"=>$this->name,
            'created_at' => Carbon::parse($this->created_at)->format('d-M-y H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('d-M-y H:i:s'),
            "property_files_data"=>$this->whenLoaded('propertyFilesData')?PropertyFilesDataResource::collection($this->propertyFilesData ?? []):[]
        ];
    }
}
