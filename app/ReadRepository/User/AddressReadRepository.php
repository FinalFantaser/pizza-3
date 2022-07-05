<?php


namespace App\ReadRepository\User;




use App\Models\User\Address;

class AddressReadRepository
{
    public function findByUserId(int $userId)
    {
        $address = Address::where('user_id', $userId)->get();
        return $address;
    }

    public function findById(int $id)
    {
        $address = Address::findOrFail($id);
        return $address;
    }
}
