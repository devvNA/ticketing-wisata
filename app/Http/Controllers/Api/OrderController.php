<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Event;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\OrderTicket;
use App\Services\Midtrans\CreatePaymentUrlService;

/**
 * Controller untuk menangani operasi terkait pesanan (Order)
 *
 * @package App\Http\Controllers\Api
 */
class OrderController extends Controller
{
    /**
     * Membuat pesanan baru
     *
     * @param Request $request Request dari client yang berisi:
     *      - event_id: ID event yang dipesan
     *      - order_details: Array detail pesanan yang berisi:
     *          - sku_id: ID SKU yang dipesan
     *          - qty: Jumlah tiket yang dipesan
     *      - quantity: Total jumlah tiket
     *      - event_date: Tanggal event
     *
     * @return JsonResponse Response dengan format:
     *      - status: Status response (success/error)
     *      - message: Pesan response
     *      - data: Data pesanan yang berhasil dibuat
     */

    public function create(Request $request)
    {
        //validate the request
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'order_details' => 'required|array',
            'order_details.*.sku_id' => 'required|exists:skus,id',
            'quantity' => 'required|integer|min:1',
            'event_date' => 'required',
        ]);


        $total = 0;
        foreach ($request->order_details as $orderDetail) {
            $sku = Sku::find($orderDetail['sku_id']);
            $qty = $orderDetail['qty'];
            $total += $sku->price * $qty;
        }



        //create order
        $order = Order::create([
            'user_id' => $request->user()->id,
            'event_id' => $request->event_id,
            'event_date' => $request->event_date,
            'quantity' => $request->quantity,
            'total_price' => $total,
        ]);

        foreach ($request->order_details as $orderDetail) {
            $sku = Sku::find($orderDetail['sku_id']);
            $qty = $orderDetail['qty'];

            for ($i = 0; $i < $qty; $i++) {
                //ticket by sku and available
                $ticket = Ticket::where('sku_id', $sku->id)
                    ->where('status', 'available')
                    ->first();
                //insert order ticket
                OrderTicket::create([
                    'order_id' => $order->id,
                    'ticket_id' => $ticket->id,
                ]);
                //ticket status to sold
                $ticket->update([
                    'status' => 'booked',
                ]);
            }
        }

        $midtrans = new CreatePaymentUrlService();
        $order['orderItems'] = $request->order_details; //add response order items
        $paymentUrl = $midtrans->getPaymentUrl($order);
        $order['paymentUrl'] = $paymentUrl; //add response payment url

        // Simpan payment_url ke tabel order
        $order = Order::find($order->id);
        $order->payment_url = $paymentUrl;
        $order->save();

        //return response
        return response()->json([
            'status' => 'success',
            'message' => 'Order created successfully',
            'data' => [
                'id' => $order->id,
                'event_id' => $order->event_id,
                'event_date' => $order->event_date,
                'quantity' => $order->quantity,
                'total_price' => $order->total_price,
                'orderItems' => $request->order_details,
                'paymentUrl' => $order->payment_url,
                'user' => $request->user(),
            ],
        ], 201);
    }
}
