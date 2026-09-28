<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gestiones';

    protected $fillable = [
        'company_id',
        'year',
        'status',
        'active',
    ];

    protected $casts = [
        'year' => 'integer',
        'active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function periodos(): HasMany
    {
        return $this->hasMany(Periodo::class);
    }
}
