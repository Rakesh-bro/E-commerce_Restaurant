<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded=['id'];
   protected $fillable = ['name','description','price','stock','image','category_id'];

public function category()
{
    return $this->belongsTo(\App\Models\Category::class);
}

}
