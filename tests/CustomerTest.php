<?php

use LaravelDoctrine\Migrations\Testing\DatabaseMigrations;
use LaravelDoctrine\ORM\Facades\EntityManager;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Tester\CommandTester;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Entities\Customer;
use Faker\Factory as FakerFactory;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;

class CustomerTest extends TestCase
{
    use Laravel\Lumen\Testing\DatabaseMigrations;

    /**
     * Test the import:customers console command
     */
    public function testImportCustomers()
    {
        $this->artisan('import:customers');

        $output = Illuminate\Support\Facades\Artisan::output();

        $this->assertStringContainsString('Customers imported successfully!', $output);
    }

    public function testGetAllCustomers()
    {

        $faker = Faker\Factory::create();

        // create a new customer
        $customer = new App\Entities\Customer(
            $faker->firstName,
            $faker->lastName,
            $faker->unique()->safeEmail,
            $faker->unique()->userName,
            $faker->password,
            $faker->randomElement(['male', 'female']),
            $faker->country,
            $faker->city,
            $faker->phoneNumber
        );

        $customer->setFirst($faker->firstName);
        $customer->setLast($faker->lastName);
        $customer->setEmail($faker->unique()->email);
        $customer->setUsername($faker->unique()->userName);
        $customer->setPassword($faker->password);
        $customer->setGender($faker->randomElement(['male', 'female']));
        $customer->setCountry($faker->country);
        $customer->setCity($faker->city);
        $customer->setPhone($faker->phoneNumber);
        $em = $this->app->make('Doctrine\ORM\EntityManagerInterface');
        $em->persist($customer);
        $em->flush();
        // make GET request to /customer
        $response = $this->get('/customers');

        // assert response has the correct JSON structure
        $response->seeJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'fullname',
                    'email',
                    'username',
                    'gender',
                    'country',
                    'city',
                    'phone'
                ]
            ]
        ]);


    }

    public function testGetCustomerById()
    {
        $faker = Faker\Factory::create();

        // create a new customer
        $customer = new App\Entities\Customer(
            $faker->firstName,
            $faker->lastName,
            $faker->unique()->safeEmail,
            $faker->unique()->userName,
            $faker->password,
            $faker->randomElement(['male', 'female']),
            $faker->country,
            $faker->city,
            $faker->phoneNumber
        );

        $customer->setFirst($faker->firstName);
        $customer->setLast($faker->lastName);
        $customer->setEmail($faker->unique()->email);
        $customer->setUsername($faker->unique()->userName);
        $customer->setPassword($faker->password);
        $customer->setGender($faker->randomElement(['male', 'female']));
        $customer->setCountry($faker->country);
        $customer->setCity($faker->city);
        $customer->setPhone($faker->phoneNumber);
        $em = $this->app->make('Doctrine\ORM\EntityManagerInterface');
        $em->persist($customer);
        $em->flush();

        $response = $this->get('/customers/' . $customer->getId());
        $response->seeJsonEquals(['data' => [
            'id' => $customer->getId(),
            'fullname' => $customer->getFullname(),
            'email' => $customer->getEmail(),
            'username' => $customer->getUsername(),
            'gender' => $customer->getGender(),
            'country' => $customer->getCountry(),
            'city' => $customer->getCity(),
            'phone' => $customer->getPhone(),
        ]])->assertResponseOk();
    }

    /**
     * Test that the /customer/{id} endpoint returns a 404 status code if the customer does not exist
     */
    public function testGetNonExistingCustomerById()
    {
        $response = $this->call('GET', '/customer/12345');

        $response->assertStatus(404);
    }

}
