<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Model
{
    use SoftDeletes;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';
       public $incrementing = false;
    protected $keyType = 'string';

      protected $fillable = ['id_mahasiswa', 'id_users', 'prodi'];

    public function users()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function article()
    {
        return $this->hasMany(Article::class, 'id_mahasiswa');
    }
}