<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->patient_id,
            'name' => $this->name,
            'surname' => $this->surname,
            'sex' => $this->sex,
            'birthDate' => $this->birth->format('Y-m-d'),
        ];
    }
}
