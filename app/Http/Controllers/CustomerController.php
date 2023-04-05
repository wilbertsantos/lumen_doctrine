<?php

namespace App\Http\Controllers;
use App\Transformers\CustomerTransformer;
use App\Entities\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Spatie\Fractalistic\Fractal;


class CustomerController extends Controller
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function index()
    {
        $customers = $this->em->getRepository(Customer::class)->findAll();
        $transformer = new CustomerTransformer();
        $customerData = [];

        foreach ($customers as $customer) {
            $customerData[] = $transformer->summary($customer);
        }

        return response()->json($customerData);
    }

    public function show($id)
    {
        $customer = $this->em->getRepository(Customer::class)->find($id);

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        $transformer = new CustomerTransformer();        
        $customerData = $transformer->details($customer);

        return response()->json($customerData);
    }

}
