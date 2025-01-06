<?php

namespace App\Http\Middleware;
use App\Http\Controllers\ProductController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Box;
use App\Models\Flower;
use App\Models\Product;

class SyncProductsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Synchronize Box Data
        $boxes = Box::all();
        foreach ($boxes as $box) {
            Product::updateOrCreate(
                ['product_id' => $box->box_unique_id], // Unique identifier
                [
                    'product_id' =>  $box->box_unique_id,
                    'product_name' => $box->box_name,
                    'product_image' => $box->box_image,
                    'product_type' => 'box',
                    'product_boxtype_id' => $box->bx_type_id,
                    'color_id' => $box->bx_color_id,
                    'price_purchase' => $box->price_purchase,
                    'price_selling' => $box->price_selling,
                ]
            );
        }

        // Synchronize Flower Data
        $flowers = Flower::all();
        foreach ($flowers as $flower) {
            Product::updateOrCreate(
                ['product_id' => $flower->flower_unique_id], // Unique identifier
                [
                    'product_id' => $flower->flower_unique_id,
                    'product_name' => $flower->flower_name,
                    'product_image' => $flower->flower_image,
                    'product_type' => 'flower',
                    'color_id' => $flower->fw_color_id,
                    'price_purchase' => $flower->price_purchase,
                    'price_selling' => $flower->price_selling,
                ]
            );
        }

        return $next($request);
    }
}
