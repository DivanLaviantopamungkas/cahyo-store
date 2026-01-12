<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Broadcast;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductNominal;
use App\Models\Trancsaction;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use App\Models\VoucherCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ===== Admin login =====
        Admin::updateOrCreate(
            ['email' => 'admin@local.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'is_active' => 1,
            ]
        );

        // ===== Members =====
        $members = collect();
        for ($i = 1; $i <= 12; $i++) {
            $members->push(
                User::updateOrCreate(
                    ['whatsapp' => '628111111' . str_pad((string)$i, 2, '0', STR_PAD_LEFT)],
                    [
                        'name' => "Member {$i}",
                        'email' => "member{$i}@local.test",
                        'password' => Hash::make('password123'),
                        'balance' => 0,
                        'is_active' => $i % 9 === 0 ? 0 : 1,
                    ]
                )
            );
        }

        // ===== Categories =====
        $catGames = Category::updateOrCreate(
            ['slug' => 'games'],
            ['name' => 'Games', 'icon' => 'game', 'color' => '4F46E5', 'is_active' => 1, 'order' => 1]
        );

        $catEwallet = Category::updateOrCreate(
            ['slug' => 'e-wallet'],
            ['name' => 'E-Wallet', 'icon' => 'wallet', 'color' => '0EA5E9', 'is_active' => 1, 'order' => 2]
        );

        $catVoucher = Category::updateOrCreate(
            ['slug' => 'voucher'],
            ['name' => 'Voucher', 'icon' => 'ticket', 'color' => '22C55E', 'is_active' => 1, 'order' => 3]
        );

        // ===== Products =====
        $pMl = Product::updateOrCreate(
            ['slug' => 'mobile-legends'],
            [
                'category_id' => $catGames->id,
                'name' => 'Mobile Legends',
                'description' => 'Topup diamond ML.',
                'type' => 'multiple',
                'is_active' => 1,
                'is_featured' => 1,
                'order' => 1,
            ]
        );

        $pDana = Product::updateOrCreate(
            ['slug' => 'dana-topup'],
            [
                'category_id' => $catEwallet->id,
                'name' => 'DANA Topup',
                'description' => 'Topup DANA via provider.',
                'type' => 'multiple',
                'is_active' => 1,
                'is_featured' => 0,
                'order' => 2,
            ]
        );

        $pSteam = Product::updateOrCreate(
            ['slug' => 'steam-wallet'],
            [
                'category_id' => $catVoucher->id,
                'name' => 'Steam Wallet',
                'description' => 'Voucher Steam Wallet (manual).',
                'type' => 'multiple',
                'is_active' => 1,
                'is_featured' => 0,
                'order' => 3,
            ]
        );

        // ===== Nominals (price/discount/stock) =====
        $ml86 = ProductNominal::updateOrCreate(
            ['product_id' => $pMl->id, 'name' => '86 Diamonds'],
            ['price' => 20000, 'discount_price' => 19500, 'stock' => 0, 'available_stock' => 0, 'is_active' => 1, 'order' => 1]
        );
        $ml172 = ProductNominal::updateOrCreate(
            ['product_id' => $pMl->id, 'name' => '172 Diamonds'],
            ['price' => 39000, 'discount_price' => 37500, 'stock' => 0, 'available_stock' => 0, 'is_active' => 1, 'order' => 2]
        );

        $dana10 = ProductNominal::updateOrCreate(
            ['product_id' => $pDana->id, 'name' => '10.000'],
            ['price' => 11000, 'discount_price' => null, 'stock' => 0, 'available_stock' => 0, 'is_active' => 1, 'order' => 1]
        );
        $dana50 = ProductNominal::updateOrCreate(
            ['product_id' => $pDana->id, 'name' => '50.000'],
            ['price' => 52000, 'discount_price' => 51000, 'stock' => 0, 'available_stock' => 0, 'is_active' => 1, 'order' => 2]
        );

        $steam60 = ProductNominal::updateOrCreate(
            ['product_id' => $pSteam->id, 'name' => 'Steam 60K'],
            ['price' => 65000, 'discount_price' => 63000, 'stock' => 10, 'available_stock' => 10, 'is_active' => 1, 'order' => 1]
        );

        // ===== Voucher codes (manual stock) =====
        for ($i = 1; $i <= 10; $i++) {
            VoucherCode::updateOrCreate(
                ['code' => 'STEAM-' . strtoupper(Str::random(4)) . '-' . $i],
                [
                    'product_id' => $pSteam->id,
                    'product_nominal_id' => $steam60->id,
                    'status' => 'available',
                ]
            );
        }

        // ===== Broadcast dummy =====
        Broadcast::updateOrCreate(
            ['title' => 'Promo Mingguan'],
            [
                'admin_id' => Admin::where('email', 'admin@local.test')->value('id') ?? 1,
                'message' => 'Promo minggu ini: cashback 5% untuk transaksi tertentu.',
                'target' => 'active_users',
                'status' => 'draft',
            ]
        );

        // ===== Transactions dummy =====
        $u1 = $members->first();

        for ($i = 1; $i <= 8; $i++) {
            $invoice = 'INVDEMO' . now()->format('ymd') . str_pad((string)$i, 3, '0', STR_PAD_LEFT);

            $trx = Trancsaction::updateOrCreate(
                ['invoice' => $invoice],
                [
                    'user_id' => $u1->id,
                    'amount' => $i % 2 === 0 ? 63000 : 19500,
                    'total_paid' => $i % 3 === 0 ? 0 : ($i % 2 === 0 ? 63000 : 19500),
                    'payment_method' => 'qris',
                    'status' => $i % 3 === 0 ? 'pending' : 'paid',
                    'payment_reference' => null,
                    'payment_url' => 'https://example.test/pay/' . $invoice,
                ]
            );

            TransactionItem::updateOrCreate(
                ['transaction_id' => $trx->id, 'product_id' => $pSteam->id, 'product_nominal_id' => $steam60->id],
                [
                    'quantity' => 1,
                    'price' => 63000,
                    'total' => 63000,
                    'status' => $trx->status === 'pending' ? 'pending' : 'completed',
                ]
            );
        }
    }
}
