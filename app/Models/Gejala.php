<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gejala extends Model
{
    public $timestamps = false;
    protected $table = 'gejala';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['kode_gejala', 'detail_gejala'];

    public function penyakits()
{
    return $this->belongsToMany(Penyakit::class, 'aturan', 'kode_gejala', 'kode_penyakit', 'kode_gejala', 'kode_penyakit');
}


    public function aturan()
    {
        return $this->hasMany(Aturan::class, 'kode_gejala', 'kode_gejala');
    }
}
