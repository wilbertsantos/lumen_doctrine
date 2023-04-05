<?php

namespace App\Importers;

use App\Models\Customer;
use GuzzleHttp\Client;
use App\Interfaces\ImporterInterface;

class RandomUserImporter implements ImporterInterface
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function import()
    {
        $response = $this->client->request('GET', 'https://randomuser.me/api/?results=100&nat=AU');

        $data = json_decode($response->getBody()->getContents(), true)['results'];

        foreach ($data as $item) {
            $email = $item['email'];
            $existingCustomer = Customer::where('email', $email)->first();

            if ($existingCustomer) {
                $existingCustomer->update([
                    'name' => $item['name']['first'] . ' ' . $item['name']['last'],
                    'phone' => $item['phone'],
                    'address' => $item['location']['street']['name'] . ', ' . $item['location']['city'] . ', ' . $item['location']['state'] . ', ' . $item['location']['postcode'],
                ]);
            } else {
                $customer = new Customer([
                    'email' => $email,
                    'name' => $item['name']['first'] . ' ' . $item['name']['last'],
                    'phone' => $item['phone'],
                    'address' => $item['location']['street']['name'] . ', ' . $item['location']['city'] . ', ' . $item['location']['state'] . ', ' . $item['location']['postcode'],
                ]);

                $customer->save();
            }
        }
    }
}
