<?php

namespace App\Services\Midtrans;

use App\Models\Product;
use App\Models\Sku;
use Illuminate\Database\Eloquent\Collection;
use Midtrans\Snap;

class CreatePaymentUrlService extends Midtrans
{
    protected $order;

    public function __construct()
    {
        parent::__construct();
    }

    public function getPaymentUrl($order)
    {

        $item_details = new Collection();

        foreach ($order->orderItems as $item) {

            $sku = Sku::find($item['sku_id']);
            $item_details->push([
                'id' => $sku->id,
                'price' => $sku->price,
                'quantity' => $item['qty'],
                'name' => $sku->name,
            ]);
        }

        //random orderId
        $orderId = rand(1000, 9999);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $order->total_price,
            ],
            'item_details' => $item_details,
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,

            ]
        ];

        $paymentUrl = Snap::createTransaction($params)->redirect_url;

        return $paymentUrl;
    }
}
