<?php

namespace App\Models\Tenant\Accounting\Accountants;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Journal extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'type',
        'date',
        'reference',
        'notes',
    ];

    public function journal_line_items(): HasMany
    {
        return $this->hasMany(JournalLineItem::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('manual_journal')->singleFile();
    }
}
