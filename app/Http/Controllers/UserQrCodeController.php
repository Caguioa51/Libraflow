<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Response;

class UserQrCodeController extends Controller
{
    /**
     * Display the QR code for a specific user
     */
    public function show(User $user)
    {
        // Check if the user has QR code (non-admin)
        if ($user->isAdmin()) {
            abort(403, 'Admin users do not have QR codes');
        }

        // Get user information for QR code (static data only)
        $userInfo = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'lrn_number' => $user->lrn_number,
            'barcode' => $user->barcode,
            'user_id' => $user->id,
            'generated_at' => now()->toISOString()
        ];

        // Generate QR code
        $qrCode = QrCode::format('png')
            ->size(200)
            ->generate(json_encode($userInfo));

        // Return QR code image
        return Response::make($qrCode, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'inline; filename="qr_code_' . $user->id . '.png"'
        ]);
    }

    /**
     * Generate QR code data for display in modal
     */
    public function generateQrData(User $user)
    {
        if ($user->isAdmin()) {
            return response()->json(['error' => 'Admin users do not have QR codes'], 403);
        }

        return response()->json([
            'qr_data' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'lrn_number' => $user->lrn_number,
                'barcode' => $user->barcode,
                'user_id' => $user->id,
                'generated_at' => now()->toISOString()
            ],
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'lrn_number' => $user->lrn_number,
                'barcode' => $user->barcode
            ]
        ]);
    }
}
