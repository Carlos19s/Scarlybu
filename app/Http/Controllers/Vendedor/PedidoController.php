<?php
namespace App\Http\Controllers\Vendedor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function __construct()
    {
        abort_unless(auth()->check() && in_array(auth()->user()->role, ['vendedor','gerente']), 403);
    }

    public function index()
    {
        $pedidos = Order::with('items.product','user')->latest()->paginate(15);
        return view('vendedor.pedidos.index', compact('pedidos'));
    }

    public function show(Order $pedido)
    {
        $pedido->load('items.product','user');
        return view('vendedor.pedidos.show', compact('pedido'));
    }

    public function cambiarEstado(Request $request, Order $pedido)
    {
        $request->validate(['estado' => 'required|in:no_revisado,pendiente,revisado,cancelado']);
        $pedido->update(['estado' => $request->estado]);
        return redirect()->back()->with('mensaje', 'Estado actualizado.');
    }

    public function whatsapp(Order $pedido)
    {
        $tel = preg_replace('/\D/', '', $pedido->cliente_telefono);
        $msg = urlencode("Hola {$pedido->cliente_nombre}, tu pedido {$pedido->numero_pedido} está en estado: {$pedido->estado}. Total: \${$pedido->total}");
        return redirect("https://wa.me/{$tel}?text={$msg}");
    }
}