<?php

namespace App\Models;

use App\Models\RecordItem;
use App\Models\Scopes\SemesterScope;
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

    // RELATIONSHIPS
    // RecordItem
    public function record_items()
    {
        return $this->hasMany(RecordItem::class);
    }

}
