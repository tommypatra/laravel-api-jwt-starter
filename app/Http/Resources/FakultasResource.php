<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FakultasResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'fakultas_siakad_id' => $this->fakultas_siakad_id,
            'nama_fakultas' => $this->nama_fakultas,
            'fakultas_singkatan' => $this->fakultas_singkatan,
            'is_aktif' => $this->is_aktif,
            'updated_at_siakad' => $this->updated_at_siakad,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
