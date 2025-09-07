<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PatientResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function getOrderListAction(): JsonResponse
    {
        $patient = Auth::user();
        $orders = Order::with('items.item')
            ->where('patient_id', $patient->getAuthIdentifier())
            ->get();
        /*
         * For a correct 404 return in this case,
         * I could separate the patient data and the patient results,
         * but that would contradict the task's assumptions.
         * I'll leave it as is.
         */
        return response()->json([
            'patient' => new PatientResource($patient),
            'orders' => OrderResource::collection($orders),
        ]);
    }
}
