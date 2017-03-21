<?php
/**
 * User: Rishabh
 * Date: 10/12/15
 * Time: 12:46 AM
 */

namespace App\Models;
use Illuminate\Support\Facades\DB;


class Customer {
    public function getCustomerId($email,$name,$phone){
        $row=DB::table('users')
            ->select('id','phone')
            ->where('email',$email)
            ->get();
        if(count($row)) {
            if($row[0]->phone != $phone) {
                DB::table('users')
                ->where('id', $row[0]->id)
                ->update([
                    'name' => $name,
                    'phone' => $phone,
                    'is_phone_verified' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            return $row[0]->id;
        }

        $row=DB::table('users')->insertGetId([
            'name'=>$name,
            'email'=>$email,
            'phone'=>$phone,
            'created_at'=> date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s')
        ]);
        $row=DB::table('users')
            ->select('id')
            ->where('email',$email)
            ->get();
        if(count($row))
            return $row[0]->id;
        return -1;
    }

    public function insertAddress($customerId, $address, $address_name = '') {

        $row=DB::table('address')->insertGetId([
            'address'=>$address,
            'address_name'=>$address_name,
            'customer_id'=>$customerId,
            'is_default' => 1,
        ]);

        return $row;
    }
    
    public function setDefaultAddress($addressId, $customerId) {

        $row = DB::table('address')
                   ->where('customer_id' , $customerId)
                   ->where('id' , $addressId)
                   ->update(['is_default' => 1]);
        return $row;
    }
	
} 