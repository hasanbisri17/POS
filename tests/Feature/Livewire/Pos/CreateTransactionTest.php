<?php

namespace Tests\Feature\Livewire\Pos;

use App\Livewire\Pos\CreateTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTransactionTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(CreateTransaction::class)
            ->assertStatus(200);
    }
}
