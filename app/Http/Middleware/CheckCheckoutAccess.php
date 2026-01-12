<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class CheckCheckoutAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Jika user sudah login, lanjutkan
        if (auth()->check()) {
            return $next($request);
        }

        // Simpan data checkout ke session
        $checkoutData = [
            'product_slug' => $request->route('product_slug'),
            'return_url' => URL::full()
        ];

        // Tambahkan parameter GET ke session
        foreach (['nominal_id', 'phone', 'customer_id'] as $param) {
            if ($request->has($param)) {
                $checkoutData[$param] = $request->get($param);
            }
        }

        session()->put('pending_checkout', $checkoutData);

        return redirect()->route('login')
            ->with('info', 'Silakan login terlebih dahulu untuk melanjutkan pembayaran');
    }
}
