<?php

namespace App\Jobs;

use App\Enum\GiveawayStatus;
use App\Models\Giveaway;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\WithoutRelations;

class StartGiveaway implements ShouldQueue
{
	use Queueable;

	/**
	 * Create a new job instance.
	 */
	public function __construct(
		#[WithoutRelations]
		public Giveaway $giveaway
	) {}

	/**
	 * Execute the job.
	 */
	public function handle(): void
	{
		$this->giveaway->refresh();

		if ($this->giveaway->status !== GiveawayStatus::SCHEDULED) {
			return;
		}

		$this->giveaway->update([
			'status' => GiveawayStatus::ACTIVE
		]);
	}
}
