<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Employee as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Employee extends Model
{
    use HasApiTokens, Notifiable;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'employee';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'tgl_lahir',
        'tempat_lahir',
        'alamat',
        'jabatan',
        'foto',
        'password',
    ];
    protected $primaryKey = 'id_employee';
    protected $hidden = [
        'password',
    ];

}
