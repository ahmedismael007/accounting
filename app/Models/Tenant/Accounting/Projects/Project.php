<?php

namespace App\Models\Tenant\Accounting\Projects;

use App\Models\Tenant\Accounting\Accountants\JournalLineItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = ['name'];

    public function journal_line_items(): HasMany
    {
        return $this->hasMany(JournalLineItem::class);
    }
}
