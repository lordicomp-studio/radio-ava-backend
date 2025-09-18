<?php

namespace App\Helpers;

use App\Models\Feature;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PaymentHelper
{

    public static function makePaymentables(array $productIds, Payment $payment):int{
        $totalPrice = 0;
        foreach ($productIds as $product){
            $productModel = Product::find($product['id']);

            // add product price to $totalPrice.
            $totalPrice += $productModel->price;
            // insert the product payment to paymentable and get the id as parent_id.
            $parent_id = DB::table('paymentables')->insertGetId([
                'payment_id' => $payment->id,
                'paymentable_id' => $product['id'],
                'paymentable_type' => Product::class,
                'parent_id' => 0,
            ]);

            // handle features if they exist.
            if (isset($product['features'])){
                $featurePaymentablesData = [];

                $features =
                    DB::table('feature_product')
                        ->where('product_id', $product['id'])
                        ->whereIn('feature_id', $product['features'])
                        ->get();

                // add feature price to $totalPrice
                $totalPrice += $features->sum('price');
                // push the data to $featurePaymentablesData array.
                foreach($features as $feature) {
                    $featurePaymentablesData[] = [
                        'payment_id' => $payment->id,
                        'paymentable_id' => $feature->feature_id,
                        'paymentable_type' => Feature::class,
                        'parent_id' => $parent_id,
                    ];
                }
                // bulk insert the features.
                DB::table('paymentables')->insert($featurePaymentablesData);
            }
        }

        return $totalPrice;
    }

    public static function putChildFeaturesUnderRespectiveProducts(Payment $payment): Payment {
        $payment->features->map(function ($item) use ($payment/*, &$test*/) {
            $parentProduct = $payment->products->where('pivot.id', $item->pivot->parent_id)->first();

            if (isset($parentProduct->children)){
                $parentProduct->children = [...$parentProduct->children, $item];
            }else{
                $parentProduct->children = [$item];
            }
        });

        return $payment; // this method works by reference, and it changes the input Payment object. Receiving the return value is optional.
    }

    /**
     * this method can only be used after 'putChildFeaturesUnderRespectiveProducts()' has been used
     */
    public static function findPriceForChildrenOfProducts(Payment $payment): Payment{
        foreach ($payment->products as $product){
            // if product has no bought features, skip
            if (!isset($product->children))
                continue;

            // get all the bought features from db
            $productChildrenIds = array_map(fn($child) => $child->id, $product->children); // children = features
            $boughtFeatures = DB::table('feature_product')
                ->where('product_id', $product->id)
                ->whereIn('feature_id', $productChildrenIds)
                ->get();

            // place price values inside the $payment object
            array_map(function ($child) use ($boughtFeatures){
                $price = $boughtFeatures->where('feature_id', $child->id)->first()->price;
                $child->price = $price;
            }, $product->children);
        }

        return $payment; // this method works by reference, and it changes the input Payment object. Receiving the return value is optional.
    }

    public static function canUserDownloadProduct(int $productId, int $userId) : bool
    {
        // check if product is free
        if (Product::find($productId)->price === 0)
            return true;

        // check if user has bought the product
        return DB::table('payments')
            ->join('paymentables', 'payments.id', '=', 'paymentables.payment_id')
            ->where([
                'payments.payer_id' => $userId,
                'paymentables.paymentable_id' => $productId,
                'paymentables.paymentable_type' => Product::class,
            ])->exists();
    }

}
