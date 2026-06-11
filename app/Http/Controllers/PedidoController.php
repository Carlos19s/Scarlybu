<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    public function confirmacion(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        return view('pedidos.confirmacion', compact('order'));
    }

    public function historial()
    {
        $pedidos = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        return view('pedidos.historial', compact('pedidos'));
    }
}