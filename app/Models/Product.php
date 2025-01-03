<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Product extends Model
{
    public function category() {
        return $this->belongsTo('\App\Models\Category');
    }

    public static function getProductsById($ids) {
        $string = implode(",",$ids);
        $results = DB::select( DB::raw("SELECT * FROM products WHERE id IN (".$string).")" );
        return $results;

    }
}
