<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    /**
     * Create a new order with items.
     *
     * @param array $data
     * @param int $userId
     * @return Order
     * @throws ValidationException
     */
    public function createOrder(array $data, int $userId): Order
    {
        return DB::transaction(function () use ($data, $userId) {
            // Validate and check stock availability
            $totalPrice = 0;
            $orderItems = [];

            foreach ($data['items'] as $item) {
                $product = Product::find($item['product_id']);

                if (!$product) {
                    throw ValidationException::withMessages([
                        'items' => ['Product with ID ' . $item['product_id'] . ' not found.'],
                    ]);
                }

                if ($product->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => ['Insufficient stock for product: ' . $product->name . '. Available: ' . $product->stock],
                    ]);
                }

                $itemTotal = $product->price * $item['quantity'];
                $totalPrice += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];

                // Reduce stock
                $product->decrement('stock', $item['quantity']);
            }

            // Create order
            $order = Order::create([
                'user_id' => $userId,
                'total_price' => $totalPrice,
            ]);

            // Create order items
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            return $order->load(['orderItems.product', 'user']);
        });
    }

    /**
     * Get orders for a user.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserOrders(int $userId)
    {
        return Order::with(['orderItems.product'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get a specific order.
     *
     * @param int $orderId
     * @param int $userId
     * @return Order|null
     */
    public function getOrder(int $orderId, int $userId): ?Order
    {
        return Order::with(['orderItems.product', 'user'])
            ->where('id', $orderId)
            ->where('user_id', $userId)
            ->first();
    }
}