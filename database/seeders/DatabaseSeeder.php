<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use App\Models\Broadcast;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductNominal;
use App\Models\Trancsaction;
use App\Models\TransactionItem;
use App\Models\User;
use App\Models\VoucherCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
        DemoSeeder::class,
        ]);
      
    }
}
