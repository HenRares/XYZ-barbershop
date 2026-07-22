<?php

namespace App\Http\Controllers;

use App\Services\BookingStatusReconciler;
use Illuminate\Http\Request;

class CronController extends Controller
{
    public function __invoke(Request $request, BookingStatusReconciler $reconciler)
    {
        $secret = (string) config('app.cron_secret');
        $authorization = (string) $request->header('Authorization', '');

        abort_unless(
            $secret !== '' && hash_equals('Bearer '.$secret, $authorization),
            401,
            'Unauthorized'
        );

        return response()->json([
            'success' => true,
            'updated' => $reconciler->reconcileAllExpired(),
        ]);
    }
}
