<?php

namespace App\Models;

use App\Models\User;
use App\Models\ReportRecord;
use App\Models\Scopes\SemesterScope;
use App\Http\Resources\ReportResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[ScopedBy([SemesterScope::class])]
class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',//(ministry,visitation,feedback,munite)
        'semester_id',
        'createable_type',
        'createable_id',
        'reportable_type',
        'reportable_id',
        'user_id',
    ];

    // Override the toArray method
    public function toArray()
    {
        // Use ReportResource to transform the model's array
        return (new ReportResource($this))->resolve();
    }

    // Override the toJson method
    public function toJson($options = 0)
    {
        // Use ReportResource to transform the model's JSON representation
        return (new ReportResource($this))->toJson($options);
    }

    // RELATIONSHIPS

    public function createable()
    {
        return $this->morphTo();
    }

    // ReportRecords
    public function report_records()
    {
        return $this->hasMany(ReportRecord::class)->orderBy('position');
    }

    // User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
