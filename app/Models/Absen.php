<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Absen extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'absen';
    protected $fillable = [
        'id_employee',
        'jam_absen',
        'lat_absen',
        'long_absen',
    ];

    protected $primaryKey = 'id_absen';

    public static function get_absen_all(){
        $result = Absen::select('absen.*', 'employee.name as nama_employee')
            ->join('employee', 'employee.id_employee', '=', 'absen.id_employee')
            ->get();
        return $result;
    }


}
