<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, Auditable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, \OwenIt\Auditing\Auditable;

    public const ROLE_ADMIN = 'administrator';
    public const ROLE_MAHASISWA = 'mahasiswa';

    // protected $primariKey = 'id_users';
    // protected $keyType = 'string';
    // public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

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
        return $this->hasOne(Mahasiswa::class, 'id_users');
    }
}
