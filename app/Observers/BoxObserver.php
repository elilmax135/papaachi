<?php
namespace App\Observers;

use App\Models\Box;
use Illuminate\Support\Facades\DB;

class BoxObserver
{
    /**
     * Handle the Box "created" event.
     *
     * @param  \App\Models\Box  $box
     * @return void
     */
    public function created(Box $box)
    {
        // Insert related data into the box_stock table
        DB::table('box_stock')->insert([
            'stock_name' => $box->box_name,  // Use box_name as stock_name
            'price' => $box->price,         // Optional, use box price
            'quantity' => $box->quantity,                // Default quantity as 0
            'box_id' => $box->id,           // Reference to box_id
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function updated(Box $box)
    {
    DB::table('box_stock')->where('box_id', $box->id)->update([
        'stock_name' => $box->box_name,
        'price' => $box->price,
        'quantity' => $box->quantity,
        'updated_at' => now(),
    ]);
   }
   public function deleted(Box $box)
     {
    // Delete the related record from the box_stock table
    DB::table('box_stock')->where('box_id', $box->id)->delete();
    }

}
