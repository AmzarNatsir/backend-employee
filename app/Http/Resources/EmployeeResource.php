<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="EmployeeResource",
 *     title="Employee Resource",
 *     description="Employee data representation",
 *
 *     @OA\Property(property="id", type="integer", example=12),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="position", type="string", example="Cashier"),
 *     @OA\Property(property="created_at", type="string", example="2025-11-21 14:30:00"),
 * )
 */

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'nik'       => $this->nik,
            'name'      => $this->nm_lengkap,
            'departemen'=> $this->getJabatan->getDepartemen->nm_dept ?? null,
            'subdepartemen'=> $this->getJabatan->getSubdepartemen->nm_subdept ?? null,
            'Jabatan'  => $this->getJabatan->nm_jabatan ?? null,
            'created_at'=> $this->created_at?->toDateTimeString(),
        ];
    }
}
