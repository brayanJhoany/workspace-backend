<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public const DEFAULT_ROLE = 'REGULAR';

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
