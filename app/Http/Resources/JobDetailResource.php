<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobDetailResource extends JsonResource
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
            'title' => $this->title,
            'company' => [
                'id' => $this->company_id,
                'name' => $this->company->name ?? $this->company,
                'logo' => $this->company_logo,
            ],
            'details' => $this->details,
            'location' => $this->location,
            'salary' => $this->salary,
            'type' => $this->type,
            'is_available' => (bool) $this->is_available,
            'posted_date' => $this->created_at->format('Y-m-d'),
            'last_update' => $this->updated_at->format('Y-m-d'),
        ];
    }
}
