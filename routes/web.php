<?php

use Barryvdh\DomPDF\Facade\Pdf;
use Domain\SaleSessions\DataTransferObjects\SaleSessionPDFResponse;
use Illuminate\Support\Facades\Route;
use Support\DataTransferObjects\Contracts\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/production-report-pdf', function () {
    $searchRequest = new \Domain\Orders\DataTransferObjects\OrderSearchRequest([
        'filters' => ['created_at_from' => '2023-01-01', 'created_at_to' => '2023-02-12'],
    ]);
    $response = app(\Domain\Orders\Contracts\Services\OrdersService::class)->productionReport($searchRequest);
    return response()->make(base64_decode($response->getData()['content']), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="'.$response->getData()['title'].'"'
    ]);
});

Route::get('/schedules-pdf/{date}', function ($date) {
    $response = app(\Domain\CircuitReservations\Contracts\Services\CircuitReservationsService::class)->schedulesPdf($date);
    return response()->make(base64_decode($response->getData()['content']), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="'.$response->getData()['title'].'"'
    ]);
});

Route::post('/website/create-order', function () {
    $data = app(\Illuminate\Http\Request::class)->all();
    logger('Request', $data);

    if (!array_key_exists('customer', $data)) {
        return 'Customer is required.';
    }

    $reservations = [];
    $reservationsTotalPrice = 0;
    $purchases = [];
    $purchasesTotalPrice = 0;
    $orders = [];

    if (array_key_exists('details', $data)) {
        foreach ($data['details'] as $detail) {
            if (empty($detail['date'])) {
                $purchases[] = $detail;
                $purchasesTotalPrice += $detail['qty'] * $detail['price'];
            } else {
                $reservations[] = $detail;
                $reservationsTotalPrice += $detail['qty'] * $detail['price'];
            }
        }
    } else {
        return 'Error. Nothing to create.';
    }

    if (count($purchases)) {
        $orderData = $data;
        $orderData['details'] = $purchases;
        $orderData['total_price'] = $purchasesTotalPrice;
        $orders[] = [
            'is_reservation' => false,
            'order_data' => $orderData,
        ];
    }

    if (count($reservations)) {
        $orderData = $data;
        $orderData['details'] = $reservations;
        $orderData['total_price'] = $reservationsTotalPrice;
        $orders[] = [
            'is_reservation' => true,
            'order_data' => $orderData,
        ];
    }

    $order = \Domain\Orders\Models\Order::where('locator', $data['beeper'])->first();

    foreach ($orders as $record) {
        $orderApproval = \Domain\Orders\Models\OrderApproval::query()->firstOrNew(['locator' => $data['beeper'], 'is_reservation' => $record['is_reservation']]);
        $orderApproval->order_data = $record['order_data'];
        $orderApproval->is_duplicated = (bool)$order;
        $orderApproval->is_reservation = (bool)$record['is_reservation'];
        $orderApproval->save();
    }

    return 'Created!';
});
