<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grado extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'grados';

    protected $fillable = [
        'company_id',
        'nivel_id',
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

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class);
    }
}
