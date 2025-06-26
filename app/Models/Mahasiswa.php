<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'nim', 'prodi', 'image'];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => url('/storage/mahasiswas/' . $image),
        );
    }
}