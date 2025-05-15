<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class VerificationCodeController extends Controller
{
    public function getCode()
    {
        try {
            // Ambil tanggal hari ini dalam format Y-m-d
            $today = now()->format('Y-m-d');

            // Cek apakah sudah ada kode untuk hari ini
            $todayCode = DB::table('verification_codes')
                ->where('date', $today)
                ->first();

            if (!$todayCode) {
                // Generate kode verifikasi baru 6 digit
                $newCode = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

                // Buat record baru
                $codeId = DB::table('verification_codes')->insertGetId([
                    'code' => $newCode,
                    'date' => $today,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Ambil data kode yang baru dibuat
                $todayCode = DB::table('verification_codes')->find($codeId);
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'code' => $todayCode->code,
                    'date' => $todayCode->date,
                    'created_at' => $todayCode->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $todayCode->updated_at->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mendapatkan kode verifikasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // public function testCode()
    // {
    //     // Logika untuk mendapatkan atau mengenerate kode verifikasi
    //     return response()->json([
    //         'code' => '123456',  // Contoh: Kode verifikasi statis
    //     ]);
    // }
}
