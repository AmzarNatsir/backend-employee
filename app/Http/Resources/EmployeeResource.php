<?php

namespace App\Http\Resources;

use App\Models\JabatanModel;
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
        $direct_supervisor = [];
        $gakom = JabatanModel::find($this->id_jabatan);
        $gakom_l1 = JabatanModel::find($gakom->id_gakom);
        $direct_supervisor = [
            'id' => $gakom_l1->id ?? null,
            'jabatan' => $gakom_l1->nm_jabatan ?? null,
        ];
        return [
            'id'        => $this->id,
            'nik'       => $this->nik,
            'name'      => $this->nm_lengkap,
            'departemen'=> $this->getJabatan->getDepartemen->nm_dept ?? null,
            'subdepartemen'=> $this->getJabatan->getSubdepartemen->nm_subdept ?? null,
            'Jabatan'  => $this->getJabatan->nm_jabatan ?? null,
            "direct_supervisor"     => $direct_supervisor,
        ];
    }
}
