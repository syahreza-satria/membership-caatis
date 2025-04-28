<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Log;

class ResetVerificationCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verification:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset and generate a new verification code every midnight.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        $code = VerificationCode::where('date', $today)->first();

        if (!$code) {
            VerificationCode::create([
                'code' => str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT),
                'date' => $today,
            ]);

            Log::info('Verification code generated successfully at ' . now());
            $this->info('Verification code generated successfully.');
        } else {
            Log::info('Verification code already exists for today.');
            $this->info('Verification code already exists.');
        }
    }
}
