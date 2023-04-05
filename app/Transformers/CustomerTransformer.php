<?php

namespace App\Transformers;

use App\Entities\Customer;
use League\Fractal\TransformerAbstract;

class CustomerTransformer extends TransformerAbstract
{


    public function summary(Customer $customer): array
    {
        return [
            'fullname' => $customer->getFullName(),
            'email' => $customer->getEmail(),
            'country' => $customer->getCountry()
        ];
    }

    public function details(Customer $customer): array
    {
        return [
            'fullname' => $customer->getFullName(),
            'email' => $customer->getEmail(),
            'username' => $customer->getUsername(),
            'gender' => $customer->getGender(),
            'country' => $customer->getCountry(),
            'city' => $customer->getCity(),
            'phone' => $customer->getPhone(),
        ];
    }

    public function transform(Customer $customer): array
    {
        return [
            'id' => $customer->getId(),
            'fullname' => $customer->getFullName(),
            'email' => $customer->getEmail(),
            'username' => $customer->getUsername(),
            'gender' => $customer->getGender(),
            'country' => $customer->getCountry(),
            'city' => $customer->getCity(),
            'phone' => $customer->getPhone(),
        ];
    }
}
