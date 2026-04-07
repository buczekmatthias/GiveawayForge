<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasSlug
{
	public function getRouteKeyName(): string
	{
		return 'slug';
	}

	protected static function bootHasSlug(): void
	{
		static::creating(function (self $model) {
			$model->slug = Str::orderedUuid();
		});
	}
}
