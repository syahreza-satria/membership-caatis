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

            $lastCode = DB::table('verification_codes')
                ->latest('created_at')
                ->first();

            if (!$lastCode) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ditemukan adanya kode verifikasi'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'code' => $lastCode->code,
                    'date' => $lastCode->date,
                    'created_at' => $lastCode->created_at
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to get verification code',
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
