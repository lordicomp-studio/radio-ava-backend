<?php

namespace App\Helpers;

use App\Http\Resources\Api\ProductWidgetResource;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DBHelper{

    /**
     * Gets the id and sales_count of the current highest selling user
     */
    public static function findHighestSellingUserInfo(){
        return DB::table('users')
            ->join('products', 'users.id', '=', 'products.creator_id')
            ->join('paymentables', 'products.id', '=', 'paymentables.paymentable_id')
            ->where('paymentables.paymentable_type', Product::class)
            ->groupBy('users.id')
            ->orderBy('sales_count', 'DESC')
            ->select('users.id', DB::raw('COUNT(paymentables.id) as sales_count'))->first();
    }

    /**
     * Gets the avg rate of all user's products
     * @param int $userId the seller's id
    */
    public static function findUserAvgProductRate(int $userId) : float|null{
        return DB::table('users')
            ->where('users.id', $userId)
            ->join('products', 'users.id', '=', 'products.creator_id')
            ->join('rates', 'products.id', '=', 'rates.ratable_id')
            ->where('rates.ratable_type', Product::class)
            ->avg('rate');
    }

    /**
     * Finds the highest selling products of a given user.
     * @param int $userId the seller's id
     * @param int $count the number of products to receive(defaults to 3)
     */
    public static function findHighestSellingUserProducts(int $userId, int $count = 3){
        return Product::where('creator_id', $userId)
            ->withCount('payments')
            ->withAvg('rates', 'rate')
            ->with(['rates', 'cover', 'categories'])
            ->orderBy('payments_count', 'DESC')
            ->take($count)->get();
    }


}
