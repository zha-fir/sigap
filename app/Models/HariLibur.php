<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HariLibur extends Model
{
    protected $fillable = ['tanggal', 'keterangan'];

    protected function casts(): array { return ['tanggal' => 'date']; }
}