<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyFilesDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $invoiceDetails=$this->invoiceDetails->toArray();
        $state=$this->state->toArray();
        $fileStatus=$this->fileStatus->toArray();
        return [
            "id"=>$this->id,
            "property_id"=>$this->property_id,
            "area"=>$this->area,
            "address"=>$this->address,
            "zip_code"=>$this->zip_code,
            "state"=>!empty($invoiceDetails)?ucwords($state['name']):null,
            "country"=>$this->country,
            "price"=>!empty($invoiceDetails)?number_format($invoiceDetails[0]['price'],'2'):0.00,
            "status"=>!empty($fileStatus)?ucwords($fileStatus['name']):null,
            'created_at' => Carbon::parse($this->created_at)->format('d-M-y H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('d-M-y H:i:s'),
        ];
    }
}
