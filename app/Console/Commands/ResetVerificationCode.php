<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VerificationCode;
use Carbon\Carbon;

class ResetVerificationCode extends Command
{
    protected $signature = 'verification:reset';

    protected $description = 'Reset verification codes daily';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = Carbon::today();

        $verificationCode = VerificationCode::firstOrNew(['date' => $today]);

        if (!$verificationCode->exists) {
            $verificationCode->code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $verificationCode->date = $today;
            $verificationCode->save();

            $this->info("Verification code for {$today} has been generated.");
        } else {
            $this->info("Verification code for {$today} already exists.");
        }
    }
}
