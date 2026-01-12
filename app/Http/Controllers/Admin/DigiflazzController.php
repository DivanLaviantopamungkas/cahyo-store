<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DigiflazzService;
use Illuminate\Http\Request;

class DigiflazzController extends Controller
{
    public function syncPriceList(DigiflazzService $digiflazz)
    {
        // Nanti: mapping ke products/product_nominals.
        $data = $digiflazz->priceList();

        return response()->json([
            'message' => 'Fetched (mapping later)',
            'data' => $data,
        ]);
    }
}
