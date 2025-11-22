<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeModel extends Model
{
    protected $table = 'hrd_karyawan';


    public function getJabatan()
    {
        return $this->belongsTo(JabatanModel::class, 'id_jabatan', 'id');
    }
}
