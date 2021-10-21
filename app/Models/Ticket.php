<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Infrastructure\Traits\Sortable\SortableTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Infrastructure\Traits\Searchable\SearchableTrait;

class Ticket extends Model
{
    use HasFactory, SoftDeletes, SearchableTrait, SortableTrait;

    protected $table = 'tickets';

    protected $fillable = [
        'title',
        'mobile',
        'email',
        'created_by',
        'organization_id',
        'priority'
    ];

    public $searchable = ['id', 'title', 'mobile'];

    public $sortable = ['id', 'created_at', 'updated_at', 'created_by'];


    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
