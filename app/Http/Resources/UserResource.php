<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_no' => $this->phone_no,
            'mobile_no' => $this->mobile_no,
            'is_confirmed' => ($this->is_profile_completed) ? true : false,
            'is_email_verified' => (!is_null($this->email_verified_at)) ? true : false,
            'is_active'=>($this->is_active) ? "Active" : "In-Active",
            'created_at' => Carbon::parse($this->created_at)->format('d-M-y H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('d-M-y H:i:s'),
        ];
    }
}
