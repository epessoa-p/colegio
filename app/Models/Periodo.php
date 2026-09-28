<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Periodo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'periodos';

    protected $fillable = [
        'company_id',
        'gestion_id',
        'name',
        'order',
        'active',
    ];

    protected $casts = [
        'order' => 'integer',
        'active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function gestion(): BelongsTo
    {
        return $this->belongsTo(Gestion::class);
    }
}
