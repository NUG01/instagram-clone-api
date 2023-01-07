<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteCode extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'delete:code';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Delete expired codes';

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle()
	{
		DB::table('codes')->where('created_at', '<=', Carbon::now()->subMinutes(30)->toDateTimeString())->delete();
	}
}
