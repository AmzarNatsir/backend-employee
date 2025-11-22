<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JabatanModel extends Model
{
    protected $table = 'mst_hrd_jabatan';

    public function getDepartemen()
    {
        return $this->belongsTo(DepartemenModel::class, 'id_dept', 'id');
    }

    public function getSubdepartemen()
    {
        return $this->belongsTo(SubDepartemenModel::class, 'id_subdept', 'id');
    }
}
