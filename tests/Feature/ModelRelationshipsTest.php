<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\CashCategory;
use App\Models\CashTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_relationships()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $variant = ProductVariant::factory()->create(['product_id' => $product->id]);
        $stockMovement = StockMovement::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertInstanceOf(ProductVariant::class, $product->variants->first());
        $this->assertInstanceOf(StockMovement::class, $product->stockMovements->first());
    }

    public function test_transaction_relationships()
    {
        $user = User::factory()->create();
        $paymentMethod = PaymentMethod::factory()->create();
        $transaction = Transaction::factory()->create([
            'created_by' => $user->id,
            'payment_method_id' => $paymentMethod->id
        ]);
        $transactionItem = TransactionItem::factory()->create(['transaction_id' => $transaction->id]);

        $this->assertInstanceOf(User::class, $transaction->createdBy);
        $this->assertInstanceOf(PaymentMethod::class, $transaction->paymentMethod);
        $this->assertInstanceOf(TransactionItem::class, $transaction->items->first());
    }

    public function test_cash_transaction_relationships()
    {
        $user = User::factory()->create();
        $category = CashCategory::factory()->create();
        $transaction = Transaction::factory()->create(['created_by' => $user->id]);
        $cashTransaction = CashTransaction::factory()->create([
            'cash_category_id' => $category->id,
            'created_by' => $user->id,
            'transaction_id' => $transaction->id
        ]);

        $this->assertInstanceOf(CashCategory::class, $cashTransaction->category);
        $this->assertInstanceOf(User::class, $cashTransaction->createdBy);
        $this->assertInstanceOf(Transaction::class, $cashTransaction->transaction);
    }

    public function test_product_scopes()
    {
        $activeProduct = Product::factory()->create(['is_active' => true]);
        $inactiveProduct = Product::factory()->create(['is_active' => false]);
        $lowStockProduct = Product::factory()->create(['stock' => 5]);
        $outOfStockProduct = Product::factory()->create(['stock' => 0]);

        $this->assertTrue(Product::active()->get()->contains($activeProduct));
        $this->assertFalse(Product::active()->get()->contains($inactiveProduct));
        $this->assertTrue(Product::lowStock()->get()->contains($lowStockProduct));
        $this->assertTrue(Product::outOfStock()->get()->contains($outOfStockProduct));
    }

    public function test_cash_category_scopes()
    {
        $incomeCategory = CashCategory::factory()->create(['type' => 'income']);
        $expenseCategory = CashCategory::factory()->create(['type' => 'expense']);
        $systemCategory = CashCategory::factory()->create(['is_system' => true]);
        $activeCategory = CashCategory::factory()->create(['is_active' => true]);
        $inactiveCategory = CashCategory::factory()->create(['is_active' => false]);

        $this->assertTrue(CashCategory::income()->get()->contains($incomeCategory));
        $this->assertTrue(CashCategory::expense()->get()->contains($expenseCategory));
        $this->assertTrue(CashCategory::system()->get()->contains($systemCategory));
        $this->assertTrue(CashCategory::active()->get()->contains($activeCategory));
        $this->assertTrue(CashCategory::inactive()->get()->contains($inactiveCategory));
    }

    public function test_cash_transaction_scopes()
    {
        $incomeTransaction = CashTransaction::factory()->create(['type' => 'income']);
        $expenseTransaction = CashTransaction::factory()->create(['type' => 'expense']);
        $salesTransaction = CashTransaction::factory()->create(['transaction_id' => Transaction::factory()->create()->id]);

        $this->assertTrue(CashTransaction::income()->get()->contains($incomeTransaction));
        $this->assertTrue(CashTransaction::expense()->get()->contains($expenseTransaction));
        $this->assertTrue(CashTransaction::fromSales()->get()->contains($salesTransaction));
        $this->assertFalse(CashTransaction::fromSales()->get()->contains($incomeTransaction));
    }
}
