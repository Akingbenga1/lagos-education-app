<?php
/**
 * Created by PhpStorm.
 * User: noibilism
 * Date: 2/21/17
 * Time: 2:57 PM
 */

namespace App\Libraries;

/**
Aes encryption
 */
class Encryption {

    public static function shatter_string($item, $type = null){

        //convert item to an array
        $item_array = str_split($item);

        //grab the first item
        $first_item = $item_array[0];

        //Initialize respective arrays for odd & even indexes
        $odd_array = [];
        $even_array = [];

        //Count the number of items in the item array
        $count_item = count($item_array);

        //Loop into the item array
        for($i = 1; $i < $count_item; $i++){
            //check if index is odd or even
            if($i % 2 == 0){

                $even_array[$i] = $item_array[$i];
            }else{
                $odd_array[$i] = $item_array[$i];
            }
        }
        $rearranged_odd_array = self::rearrange_array($odd_array);
        $rearranged_even_array = self::rearrange_array($even_array);
        $new_array = self::array_interpolate(self::array_flatten($rearranged_odd_array), self::array_flatten
        ($rearranged_even_array));
        $final_string = $first_item.implode('',$new_array);
        return $final_string;
    }

    public static function encryptString($string){
        $enc = base64_encode(self::shatter_string($string));
        return $enc;
    }

    public static function decryptString($string){
        $dec = base64_decode($string);
        $str = self::shatter_string($dec);
        return $str;
    }

    public static function array_interpolate($array_1, $array_2) {
        $result = array();
        array_map(function ($map_1, $map_2) use (&$result) {
            array_push($result, $map_1, $map_2);
        }, $array_1, $array_2);
        return $result;
    }

    public static function rearrange_array($array){
        $chunked_array = array_chunk($array, 2);
        $new = [];
        foreach($chunked_array as $new_array){
            $new[] = array_reverse($new_array);
        }
        return $new;
    }

    public static function array_flatten($array) {
        if (!is_array($array)) {
            return FALSE;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, array_flatten($value));
            }
            else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

}