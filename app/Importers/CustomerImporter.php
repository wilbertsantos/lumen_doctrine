<?php

namespace App\Importers;

use App\Entities\Customer;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Facades\Http;

class CustomerImporter implements ImporterInterface
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function import(array $data): void
    {
        $results = $data;

        foreach ($results as $result) {
            $nationality = $result['nat'] ?? null;
            if ($nationality !== 'AU') {
                continue; // Skip customers with nationalities other than Australia
            }

            $email = $result['email'] ?? null;
            if (!$email) {
                continue; // Skip customers without an email address
            }

            // Check if customer already exists
            $customer =$this->checkUserEmail($email,$this->em);
            //$customer = $this->em->getRepository(Customer::class)->findOneBy(['email' => $email]);

            if (!$customer) {
                // Create new customer
                $customer = new Customer(
                                $result['name']['first'],
                                $result['name']['last'],
                                $result['email'],
                                $result['login']['username'],
                                md5($result['login']['password']),
                                $result['gender'],
                                $result['location']['country'],
                                $result['location']['city'],
                                $result['phone']
                            );
            }else{
                // Set customer data
                $customer->setFirst($result['name']['first'] ?? '');
                $customer->setLast($result['name']['last'] ?? '');
                $customer->setEmail($email);
                $customer->setUsername($result['login']['username'] ?? '');
                $customer->setPassword($result['login']['sha256'] ?? '');
                $customer->setGender($result['gender'] ?? '');
                $customer->setCountry($result['location']['country'] ?? '');
                $customer->setCity($result['location']['city'] ?? '');
                $customer->setPhone($result['phone'] ?? '');

            } 


            // Save customer to database
            $this->em->persist($customer);
        }

        $this->em->flush();
    }
    public function getData(): array
    {
        $response = Http::get('https://randomuser.me/api/', [
            'nat' => 'au',
            'results' => 100,
        ]);

        if ($response->ok()) {
            $data = $response->json();
            return $data['results'];
        }

        throw new \RuntimeException('Failed to retrieve data from data provider.');
    }

    protected function checkUserEmail($email,$eM)
    {
        $customer   = $eM->getRepository(Customer::class)
                        ->findOneBy(['email' => $email]);

        return $customer;
    }

}
