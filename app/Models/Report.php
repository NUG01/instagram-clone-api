<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory;

		protected $guarded = ['id'];



    public function reportImages(): HasMany
	{
		return	$this->hasMany(ReportImage::class);
	}

    public function user(): BelongsTo
	{
		return	$this->belongsTo(User::class);
	}
}
