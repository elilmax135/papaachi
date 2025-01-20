<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{

    protected $table = 'branches';

    // Define the fillable fields
    protected $fillable = [
        'branch_name',
        'address',
        'incharge',
        'contact_no',
    ];
    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }

    use HasFactory;
}
