<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gtk extends Model
{
    use HasFactory;

    protected $table = 'gtk'; // nama tabel di database
    protected $fillable = ['nama', 'jabatan', 'foto']; // sesuaikan field
}
