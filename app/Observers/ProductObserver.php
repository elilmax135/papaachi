<?php

namespace App\Observers;

use App\Models\Product;


use App\Models\Box;
use App\Models\Flower;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product)
    {
        if ($product->product_type === 'box') {
            // Update related BoxInfo entry
            Box::where('box_unique_id', $product->product_id)
                ->update([
                    'box_name' => $product->product_name,
                    'box_image' => $product->product_image,
                    'bx_type_id' => $product->product_boxtype_id,
                    'bx_color_id' => $product->color_id,
                    'price_purchase' => $product->price_purchase,
                    'price_selling' => $product->price_selling,
                ]);
        } elseif ($product->product_type === 'flower') {
            // Update related FlowerInfo entry
            Flower::where('flower_unique_id', $product->product_id)
                ->update([
                    'flower_name' => $product->product_name,
                    'flower_image' => $product->product_image,
                    'fw_color_id' => $product->color_id,
                    'price_purchase' => $product->price_purchase,
                    'price_selling' => $product->price_selling,
                ]);
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        if ($product->product_type === 'box') {
            // Delete related BoxInfo entry
            Box::where('box_unique_id', $product->product_id)->delete();
        } elseif ($product->product_type === 'flower') {
            // Delete related FlowerInfo entry
            Flower::where('flower_unique_id', $product->product_id)->delete();
        }
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
