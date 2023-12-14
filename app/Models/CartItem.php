<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;

class CartItem extends Model
{
    use HasFactory;
    protected $fillable = ['user_id' , 'product_id', 'amount'];

    public function user()
    {
        $this->belongsTo(User::class);
    }
    public function product()
    {
        $this->belongsTo(Product::class);
    }
}
