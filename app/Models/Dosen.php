<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Dosen extends Model implements Auditable
{
   use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'dosen';
    protected $primaryKey = 'id_dosen';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id_dosen', 'nama_lengkap', 'keahlian'];

    protected $casts = [
        'keahlian' => 'array',
    ];

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
}
