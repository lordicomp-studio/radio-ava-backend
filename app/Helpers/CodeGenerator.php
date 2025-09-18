<?php

namespace App\Helpers;

use App\Models\DiscountCode;
use App\Models\Payment;
use App\Models\Product;

class CodeGenerator
{
    public const productCodePrefix = 'lrd';

    public static function makeUniqueProductCode($length = 6): string {
        $code = CodeGenerator::productCodePrefix . CodeGenerator::generateRandomString($length);
        while (Product::where('code', $code)->first()){
            $code = CodeGenerator::generateRandomString($length);
        }
        return $code;
    }

    public static function makeUniquePaymentCode($length = 10): string {
        $code = CodeGenerator::generateRandomString($length);
        while (Payment::where('code', $code)->first()){
            $code = CodeGenerator::generateRandomString($length);
        }
        return $code;
    }

    public static function makeUniqueDiscountCodeCode($length = 8): string {
        $code = CodeGenerator::generateRandomString($length, false);
        while (DiscountCode::where('code', $code)->first()){
            $code = CodeGenerator::generateRandomString($length, false);
        }
        return $code;
    }

    public static function generateRandomString($length = 10, bool $isNumeric = true): string {
        if ($isNumeric) {
            $characters = '123456789'; //'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }else{
            $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


}
