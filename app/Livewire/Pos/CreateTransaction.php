<?php

namespace App\Livewire\Pos;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Livewire\Component;
use Livewire\Attributes\Rule;

class CreateTransaction extends Component
{
    #[Rule('required|exists:payment_methods,id')]
    public $payment_method_id = '';

    #[Rule('required|numeric|min:0')]
    public $payment_amount = 0;

    public $notes = '';
    public $cart = [];
    public $products = [];
    public $categories = [];
    public $payment_methods = [];
    public $selected_product = null;
    public $selected_variant = null;
    public $quantity = 1;
    public $search = '';
    public $category_filter = '';
    public $showPaymentModal = false;
    public $showCart = true;

    public function mount()
    {
        $this->payment_methods = PaymentMethod::active()->get();
        $this->categories = Category::active()->get();
        $this->loadProducts();
    }

    public function closeProductModal()
    {
        $this->selected_product = null;
        $this->selected_variant = null;
        $this->quantity = 1;
    }

    public function loadProducts()
    {
        $this->products = Product::query()
            ->with(['category', 'variants'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhereHas('category', function ($query) {
                        $query->where('name', 'like', "%{$this->search}%");
                    });
            })
            ->when($this->category_filter, function ($query) {
                $query->where('category_id', $this->category_filter);
            })
            ->active()
            ->inStock()
            ->get();
    }

    public function updatedSearch()
    {
        $this->loadProducts();
    }

    public function updatedCategoryFilter()
    {
        $this->loadProducts();
    }

    public function selectProduct($productId, $variantId = null)
    {
        $this->selected_product = Product::with('variants')->find($productId);
        $this->selected_variant = $variantId ? ProductVariant::find($variantId) : null;
        $this->quantity = 1;
    }

    public function incrementQuantity()
    {
        $this->quantity++;
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function quickAddToCart($productId)
    {
        $product = Product::find($productId);
        if (!$product || $product->variants->isNotEmpty()) {
            return;
        }

        $key = "{$product->id}";
        if (isset($this->cart[$key])) {
            $this->cart[$key]['quantity'] += 1;
        } else {
            $this->cart[$key] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'variant_id' => null,
                'variant_name' => null,
                'price' => $product->base_price,
                'quantity' => 1,
            ];
        }
        
        $this->showCart = true;
    }

    public function addToCart()
    {
        if (!$this->selected_product) {
            return;
        }

        $variant = null;
        $price = $this->selected_product->base_price;

        if ($this->selected_variant) {
            $variant = ProductVariant::find($this->selected_variant);
            if ($variant) {
                $price += $variant->price_adjustment;
            }
        }

        $key = $variant
            ? "{$this->selected_product->id}-{$variant->id}"
            : "{$this->selected_product->id}";

        if (isset($this->cart[$key])) {
            $this->cart[$key]['quantity'] += $this->quantity;
        } else {
            $this->cart[$key] = [
                'product_id' => $this->selected_product->id,
                'product_name' => $this->selected_product->name,
                'variant_id' => $variant?->id,
                'variant_name' => $variant?->name,
                'price' => $price,
                'quantity' => $this->quantity,
            ];
        }

        $this->reset(['selected_product', 'selected_variant', 'quantity']);
    }

    public function removeFromCart($key)
    {
        unset($this->cart[$key]);
    }

    public function incrementCartItem($key)
    {
        if (isset($this->cart[$key])) {
            $this->cart[$key]['quantity']++;
        }
    }

    public function decrementCartItem($key)
    {
        if (isset($this->cart[$key]) && $this->cart[$key]['quantity'] > 1) {
            $this->cart[$key]['quantity']--;
        }
    }

    public function updateQuantity($key, $quantity)
    {
        if ($quantity > 0) {
            $this->cart[$key]['quantity'] = $quantity;
        }
    }

    public function getSubtotalProperty()
    {
        return collect($this->cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
    }

    public function getChangeAmountProperty()
    {
        return max(0, $this->payment_amount - $this->subtotal);
    }

    public function showPaymentModal()
    {
        $this->showPaymentModal = true;
    }

    public function processTransaction()
    {
        if (empty($this->cart)) {
            $this->addError('cart', 'Keranjang belanja kosong');
            return;
        }

        $this->validate();

        if ($this->payment_amount < $this->subtotal) {
            $this->addError('payment_amount', 'Jumlah pembayaran kurang dari total belanja');
            return;
        }

        $transaction = Transaction::create([
            'payment_method_id' => $this->payment_method_id,
            'total_amount' => $this->subtotal,
            'payment_amount' => $this->payment_amount,
            'change_amount' => $this->change_amount,
            'status' => 'completed',
            'notes' => $this->notes,
            'created_by' => auth()->id(),
        ]);

        foreach ($this->cart as $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item['product_id'],
                'product_variant_id' => $item['variant_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);
        }

        $this->reset(['cart', 'payment_method_id', 'payment_amount', 'notes', 'showPaymentModal']);
        $this->dispatch('transaction-completed', $transaction->id);
    }

    public function render()
    {
        return view('livewire.pos.create-transaction');
    }
}
