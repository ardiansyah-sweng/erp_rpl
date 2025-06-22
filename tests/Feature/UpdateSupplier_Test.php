<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Supplier;
use Faker\Factory as Faker;// Import Library Faker untuk menghasilkan data acak

class UpdateSupplier_Test extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_modelUpdate(): void
    {
        $faker = Faker::create();//inisiasi faker agar data yang dihasilkan acak bukan statis
        $supplier = Supplier::inRandomOrder()->first();
        dump($supplier);
        $newData = [
            'company_name' => $faker->company,
            'address' => $faker->address,
            'phone_number' => $faker->phoneNumber,
            'bank_account' => $faker->bankAccountNumber,
        ];
        $updateSupplier = Supplier::updateSupplier($supplier->supplier_id, $newData);
    }
    //Make Controller Test UpdateSupplier_Test
    public function test_controllerUpdate(): void
    {
        $faker = Faker::create();//inisiasi faker agar data yang dihasilkan acak bukan statis
        $supplier = Supplier::inRandomOrder()->first();
        dump($supplier);
        $UpdateNama = $faker->company;
        $updateAddress = $faker->address;
        $updatePhoneNumber = $faker->phoneNumber;
        $updateBankAccount = $faker->bankAccountNumber;
        $newData = [
            'company_name' => $UpdateNama,
            'address' => $updateAddress,
            'phone_number' => $updatePhoneNumber,
            'bank_account' => $updateBankAccount
        ];
        
        $response = $this->put('/supplier/update/' . $supplier->supplier_id, $newData);
    }
}
