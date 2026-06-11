<?php
namespace App\Livewire;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CarritoComponent extends Component
{
    public array $carrito = [];
    public bool  $mostrarCheckout = false;

    // Datos checkout
    public string $cliente_nombre    = '';
    public string $cliente_telefono  = '';
    public string $cliente_correo    = '';
    public string $cliente_direccion = '';
    public string $cliente_documento = '';

    public function mount()
    {
        $this->carrito = session('carrito', []);
        if (Auth::check()) {
            $this->cliente_nombre  = Auth::user()->name;
            $this->cliente_correo  = Auth::user()->email;
        }
    }

    public function agregar(int $productoId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('info', 'Inicia sesión para agregar productos.');
        }

        $producto = Product::findOrFail($productoId);
        $id = $producto->id;

        if (isset($this->carrito[$id])) {
            $this->carrito[$id]['cantidad']++;
        } else {
            $this->carrito[$id] = [
                'nombre'   => $producto->nombre,
                'precio'   => (float) $producto->precio_venta,
                'iva'      => (float) $producto->iva_porcentaje,
                'imagen'   => $producto->imagen,
                'cantidad' => 1,
            ];
        }

        session(['carrito' => $this->carrito]);
        $this->dispatch('carritoActualizado', count($this->carrito));
    }

    public function incrementar(int $id)
    {
        if (isset($this->carrito[$id])) {
            $this->carrito[$id]['cantidad']++;
            session(['carrito' => $this->carrito]);
        }
    }

    public function decrementar(int $id)
    {
        if (isset($this->carrito[$id])) {
            $this->carrito[$id]['cantidad']--;
            if ($this->carrito[$id]['cantidad'] <= 0) {
                unset($this->carrito[$id]);
            }
            session(['carrito' => $this->carrito]);
        }
    }

    public function eliminar(int $id)
    {
        unset($this->carrito[$id]);
        session(['carrito' => $this->carrito]);
    }

    public function vaciar()
    {
        $this->carrito = [];
        session(['carrito' => []]);
    }

    public function getSubtotalProperty(): float
    {
        return collect($this->carrito)->sum(fn($i) => $i['precio'] * $i['cantidad']);
    }

    public function getTotalIvaProperty(): float
    {
        return collect($this->carrito)->sum(fn($i) => $i['precio'] * $i['cantidad'] * ($i['iva'] / 100));
    }

    public function getTotalProperty(): float
    {
        return $this->subtotal + $this->totalIva;
    }

    public function confirmarPedido()
    {
        $this->validate([
            'cliente_nombre'    => 'required|string|max:150',
            'cliente_telefono'  => 'required|string|max:20',
            'cliente_correo'    => 'nullable|email',
            'cliente_direccion' => 'required|string',
            'cliente_documento' => 'nullable|string|max:20',
        ]);

        if (empty($this->carrito)) {
            $this->addError('carrito', 'El carrito está vacío.');
            return;
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id'           => Auth::id(),
                'cliente_nombre'    => $this->cliente_nombre,
                'cliente_telefono'  => $this->cliente_telefono,
                'cliente_correo'    => $this->cliente_correo,
                'cliente_direccion' => $this->cliente_direccion,
                'cliente_documento' => $this->cliente_documento,
                'estado'            => 'no_revisado',
                'total'             => $this->total,
                'total_iva'         => $this->totalIva,
                'es_vip'            => false,
            ]);

            foreach ($this->carrito as $producto_id => $item) {
                $sub = $item['precio'] * $item['cantidad'];
                OrderItem::create([
                    'order_id'        => $order->id,
                    'product_id'      => $producto_id,
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'iva_porcentaje'  => $item['iva'],
                    'subtotal'        => $sub + ($sub * $item['iva'] / 100),
                ]);
                Product::where('id', $producto_id)->decrement('stock', $item['cantidad']);
            }

            DB::commit();
            $this->vaciar();
            $this->mostrarCheckout = false;

            return redirect()->route('pedidos.confirmacion', $order->id);

        } catch (\Throwable $e) {
            DB::rollBack();
            $this->addError('general', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.carrito-component');
    }
}