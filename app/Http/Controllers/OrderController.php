<?php

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Create a new order.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|integer',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            $order = $this->orderService->createOrder($validated, $request->user()->id);

            // Dispatch the OrderPlaced event
            event(new OrderPlaced($order));

            return response()->json([
                'message' => 'Order created successfully',
                'data' => [
                    'id' => $order->id,
                    'user_id' => $order->user_id,
                    'total_price' => $order->total_price,
                    'status' => 'pending',
                    'created_at' => $order->created_at,
                    'items' => $order->orderItems->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'product_id' => $item->product_id,
                            'product_name' => $item->product->name,
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                            'subtotal' => $item->quantity * $item->price,
                        ];
                    }),
                ],
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all orders for the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $orders = $this->orderService->getUserOrders($request->user()->id);

            return response()->json([
                'message' => 'Orders retrieved successfully',
                'data' => $orders->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'user_id' => $order->user_id,
                        'total_price' => $order->total_price,
                        'status' => 'pending',
                        'created_at' => $order->created_at,
                        'items_count' => $order->orderItems->count(),
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve orders',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a specific order.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $order = $this->orderService->getOrder($id, $request->user()->id);

            if (!$order) {
                return response()->json([
                    'message' => 'Order not found',
                ], 404);
            }

            return response()->json([
                'message' => 'Order retrieved successfully',
                'data' => [
                    'id' => $order->id,
                    'user_id' => $order->user_id,
                    'user_name' => $order->user->name,
                    'total_price' => $order->total_price,
                    'status' => 'pending',
                    'created_at' => $order->created_at,
                    'items' => $order->orderItems->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'product_id' => $item->product_id,
                            'product_name' => $item->product->name,
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                            'subtotal' => $item->quantity * $item->price,
                        ];
                    }),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}