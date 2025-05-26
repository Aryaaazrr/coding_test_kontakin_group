<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Article extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'article';
    protected $primaryKey = 'id_article';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id_article', 'id_mahasiswa', 'id_dosen', 'judul', 'tipe', 'file'];

    public function transformAudit(array $data): array
{
    $event = $data['event'] ?? 'unknown';
    $modelName = strtolower(class_basename($this));

    switch ($event) {
        case 'created':
            $tags = [ "create $modelName"];
            break;
        case 'updated':
            $tags = [ "update $modelName"];
            break;
        case 'deleted':
            $tags = [ "delete $modelName"];
            break;
        case 'restored':
            $tags = [ "restore $modelName"];
            break;
        default:
            $tags = [ 'unknown'];
            break;
    }

    $data['tags'] = implode(', ', $tags);

    return $data;
}

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }
}
