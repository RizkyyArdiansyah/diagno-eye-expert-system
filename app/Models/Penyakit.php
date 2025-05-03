<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Penyakit extends Model
{
    public $timestamps = false;
    protected $table = 'penyakit';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['kode_penyakit', 'nama_penyakit', 'solusi'];

    public function gejalas()
{
    return $this->belongsToMany(Gejala::class, 'aturan', 'kode_penyakit', 'kode_gejala', 'kode_penyakit', 'kode_gejala');
}


    public function aturan()
    {
        return $this->hasMany(Aturan::class, 'kode_penyakit', 'kode_penyakit');
    }
}


