<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MahasiswaResource extends JsonResource
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
            'user_id' => $this->user_id,
            'user_siakad_id' => $this->user?->user_siakad_id,
            'name' => $this->user?->name,
            'email' => $this->user?->email,
            'nim' => $this->nim,
            'program_studi' => $this->program_studi,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
