<?php

namespace App\Models;

use App\Models\RecordItem;
use App\Models\Scopes\SemesterScope;
use App\Http\Resources\RecordResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[ScopedBy([SemesterScope::class])]
class Record extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type', //financial, counting, text
        'createable_type',
        'createable_id',
        'user_id',
        'semester_id',
    ];

    // Override the toArray method
    public function toArray()
    {
        // Use RecordResource to transform the model's array
        return (new RecordResource($this))->resolve();
    }

    // Override the toJson method
    public function toJson($options = 0)
    {
        // Use RecordResource to transform the model's JSON representation
        return (new RecordResource($this))->toJson($options);
    }

    // RELATIONSHIPS
    // RecordItem
    public function record_items()
    {
        return $this->hasMany(RecordItem::class);
    }

    // createable
    public function createable()
    {
        return $this->morphTo();
    }

}
