<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class VerificationCodeController extends Controller
{
    // public function getCode()
    // {
    //     try {
    //         $today = now()->format('Y-m-d');

    //         $todayCode = DB::table('verification_codes')
    //             ->where('date', $today)
    //             ->first();

    //         if (!$todayCode) {
    //             $newCode = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

    //             $codeId = DB::table('verification_codes')->insertGetId([
    //                 'code' => $newCode,
    //                 'date' => $today,
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             ]);

    //             $todayCode = DB::table('verification_codes')->find($codeId);
    //         }

    //         return response()->json([
    //             'status' => true,
    //             'data' => [
    //                 'code' => $todayCode->code,
    //                 'date' => $todayCode->date,
    //                 'created_at' => $todayCode->created_at->format('Y-m-d H:i:s'),
    //                 'updated_at' => $todayCode->updated_at->format('Y-m-d H:i:s')
    //             ]
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Gagal mendapatkan kode verifikasi',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function getCode(Request $request)
    {
        try {
            $date = $request->input('date', now()->format('Y-m-d'));

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid date format. Use Y-m-d format.'
                ], 400);
            }

            $todayCode = DB::table('verification_codes')
                ->where('date', $date)
                ->first();

            if (!$todayCode) {
                $newCode = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

                $codeId = DB::table('verification_codes')->insertGetId([
                    'code' => $newCode,
                    'date' => $date,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $todayCode = DB::table('verification_codes')->find($codeId);
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'code' => $todayCode->code,
                    'date' => $todayCode->date,
                    'created_at' => $todayCode->created_at,
                    'updated_at' => $todayCode->updated_at
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
}
