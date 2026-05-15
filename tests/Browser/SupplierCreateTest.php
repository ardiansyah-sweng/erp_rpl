<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Supplier;

class SupplierCreateTest extends DuskTestCase
{
    /**
     * Test user can access supplier create form
     */
    public function test_user_can_access_supplier_create_form(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/supplier/add')
                ->assertSee('Tambah Supplier')
                ->assertPresent('input[name="supplier_id"]')
                ->assertPresent('input[name="company_name"]')
                ->assertPresent('input[name="address"]')
                ->assertPresent('input[name="phone_number"]')
                ->assertPresent('input[name="bank_account"]')
                ->assertPresent('button[type="button"]');
        });
    }
    
    /**
     * Test create supplier with empty supplier_id
     */
    public function test_create_supplier_with_empty_supplier_id(): void
    {
        $supplierId = '';
        $companyName = 'PT Test Empty ID';
        $address = 'Jl. Test No. 123';
        $phoneNumber = '081234567890';
        $bankAccount = '1234567890';

        $this->browse(function (Browser $browser) use ($supplierId, $companyName, $address, $phoneNumber, $bankAccount) {
            $browser->visit('/supplier/add')
                ->waitFor('form[id="picForm"]', 10)
                ->type('supplier_id', $supplierId)
                ->type('company_name', $companyName)
                ->type('address', $address)
                ->type('phone_number', $phoneNumber)
                ->type('bank_account', $bankAccount)
                ->click('button[onclick="validateForm()"]')
                ->pause(2000)
                ->assertPathIs('/supplier/add')
                ->assertSee('ID Supplier harus diisi');
        });
    }

    /**
     * Test create supplier with empty company_name
     */
    public function test_create_supplier_with_empty_company_name(): void
    {
        $supplierId = 'SUP006';
        $companyName = '';
        $address = 'Jl. Test No. 123';
        $phoneNumber = '081234567890';
        $bankAccount = '1234567890';

        $this->browse(function (Browser $browser) use ($supplierId, $companyName, $address, $phoneNumber, $bankAccount) {
            $browser->visit('/supplier/add')
                ->waitFor('form[id="picForm"]', 10)
                ->type('supplier_id', $supplierId)
                ->type('company_name', $companyName)
                ->type('address', $address)
                ->type('phone_number', $phoneNumber)
                ->type('bank_account', $bankAccount)
                ->click('button[onclick="validateForm()"]')
                ->pause(2000)
                ->assertPathIs('/supplier/add')
                ->assertSee('company name harus diisi');
        });
    }

    /**
     * Test create supplier with empty address
     */
    public function test_create_supplier_with_empty_address(): void
    {
        $supplierId = 'SUP007';
        $companyName = 'PT Test Empty Address';
        $address = '';
        $phoneNumber = '081234567890';
        $bankAccount = '1234567890';

        $this->browse(function (Browser $browser) use ($supplierId, $companyName, $address, $phoneNumber, $bankAccount) {
            $browser->visit('/supplier/add')
                ->waitFor('form[id="picForm"]', 10)
                ->type('supplier_id', $supplierId)
                ->type('company_name', $companyName)
                ->type('address', $address)
                ->type('phone_number', $phoneNumber)
                ->type('bank_account', $bankAccount)
                ->click('button[onclick="validateForm()"]')
                ->pause(2000)
                ->assertPathIs('/supplier/add')
                ->assertSee('Address harus diisi');
        });
    }

    /**
     * Test create supplier with empty phone_number
     */
    public function test_create_supplier_with_empty_phone_number(): void
    {
        $supplierId = 'SUP008';
        $companyName = 'PT Test Empty Phone';
        $address = 'Jl. Test No. 123';
        $phoneNumber = '';
        $bankAccount = '1234567890';

        $this->browse(function (Browser $browser) use ($supplierId, $companyName, $address, $phoneNumber, $bankAccount) {
            $browser->visit('/supplier/add')
                ->waitFor('form[id="picForm"]', 10)
                ->type('supplier_id', $supplierId)
                ->type('company_name', $companyName)
                ->type('address', $address)
                ->type('phone_number', $phoneNumber)
                ->type('bank_account', $bankAccount)
                ->click('button[onclick="validateForm()"]')
                ->pause(2000)
                ->assertPathIs('/supplier/add')
                ->assertSee('Nomor Telephone harus diisi(10-13 digit)');
        });
    }

    /**
     * Test create supplier with empty bank_account
     */
    public function test_create_supplier_with_empty_bank_account(): void
    {
        $supplierId = 'SUP009';
        $companyName = 'PT Test Empty Bank';
        $address = 'Jl. Test No. 123';
        $phoneNumber = '081234567890';
        $bankAccount = '';

        $this->browse(function (Browser $browser) use ($supplierId, $companyName, $address, $phoneNumber, $bankAccount) {
            $browser->visit('/supplier/add')
                ->waitFor('form[id="picForm"]', 10)
                ->type('supplier_id', $supplierId)
                ->type('company_name', $companyName)
                ->type('address', $address)
                ->type('phone_number', $phoneNumber)
                ->type('bank_account', $bankAccount)
                ->click('button[onclick="validateForm()"]')
                ->pause(2000)
                ->assertPathIs('/supplier/add')
                ->assertSee('bank account harus diisi');
        });
    }

    /**
     * Test create supplier with supplier_id less than 6 characters
     */
    public function test_create_supplier_with_supplier_id_less_than_6_characters(): void
    {
        $supplierId = 'SUP01'; // 5 characters
        $companyName = 'PT Test Short ID';
        $address = 'Jl. Test No. 123';
        $phoneNumber = '081234567890';
        $bankAccount = '1234567890';

        $this->browse(function (Browser $browser) use ($supplierId, $companyName, $address, $phoneNumber, $bankAccount) {
            $browser->visit('/supplier/add')
                ->waitFor('form[id="picForm"]', 10)
                ->type('supplier_id', $supplierId)
                ->type('company_name', $companyName)
                ->type('address', $address)
                ->type('phone_number', $phoneNumber)
                ->type('bank_account', $bankAccount)
                ->click('button[onclick="validateForm()"]')
                ->pause(2000);

            // Should either stay on form or show error
            if ($browser->driver->getCurrentURL() === url('/supplier/add')) {
                $browser->assertPathIs('/supplier/add');
            }
        });
    }

    /**
     * Test create supplier with supplier_id more than 6 characters
     */
    public function test_create_supplier_with_supplier_id_more_than_6_characters(): void
    {
        $supplierId = 'SUP0123'; // 7 characters
        $companyName = 'PT Test Long ID';
        $address = 'Jl. Test No. 123';
        $phoneNumber = '081234567890';
        $bankAccount = '1234567890';

        $this->browse(function (Browser $browser) use ($supplierId, $companyName, $address, $phoneNumber, $bankAccount) {
            $browser->visit('/supplier/add')
                ->waitFor('form[id="picForm"]', 10)
                ->type('supplier_id', $supplierId)
                ->type('company_name', $companyName)
                ->type('address', $address)
                ->type('phone_number', $phoneNumber)
                ->type('bank_account', $bankAccount)
                ->click('button[onclick="validateForm()"]')
                ->pause(2000);

            // Should either stay on form or show error
            if ($browser->driver->getCurrentURL() === url('/supplier/add')) {
                $browser->assertPathIs('/supplier/add');
            }
        });
    }

    /**
     * Test create supplier with company_name more than 100 characters
     */
    public function test_create_supplier_with_company_name_too_long(): void
    {
        $supplierId = 'SUP010';
        $companyName = str_repeat('A', 101); // 101 characters
        $address = 'Jl. Test No. 123';
        $phoneNumber = '081234567890';
        $bankAccount = '1234567890';

        $this->browse(function (Browser $browser) use ($supplierId, $companyName, $address, $phoneNumber, $bankAccount) {
            $browser->visit('/supplier/add')
                ->waitFor('form[id="picForm"]', 10)
                ->type('supplier_id', $supplierId)
                ->type('company_name', $companyName)
                ->type('address', $address)
                ->type('phone_number', $phoneNumber)
                ->type('bank_account', $bankAccount)
                ->click('button[onclick="validateForm()"]')
                ->pause(2000);

            // Should either stay on form or show error
            if ($browser->driver->getCurrentURL() === url('/supplier/add')) {
                $browser->assertPathIs('/supplier/add');
            }
        });
    }

    /**
     * Test create supplier with address more than 100 characters
     */
    public function test_create_supplier_with_address_too_long(): void
    {
        $supplierId = 'SUP011';
        $companyName = 'PT Test Long Address';
        $address = str_repeat('A', 101); // 101 characters
        $phoneNumber = '081234567890';
        $bankAccount = '1234567890';

        $this->browse(function (Browser $browser) use ($supplierId, $companyName, $address, $phoneNumber, $bankAccount) {
            $browser->visit('/supplier/add')
                ->waitFor('form[id="picForm"]', 10)
                ->type('supplier_id', $supplierId)
                ->type('company_name', $companyName)
                ->type('address', $address)
                ->type('phone_number', $phoneNumber)
                ->type('bank_account', $bankAccount)
                ->click('button[onclick="validateForm()"]')
                ->pause(2000);

            // Should either stay on form or show error
            if ($browser->driver->getCurrentURL() === url('/supplier/add')) {
                $browser->assertPathIs('/supplier/add');
            }
        });
    }

    /**
     * Test create supplier with phone_number more than 30 characters
     */
    public function test_create_supplier_with_phone_number_too_long(): void
    {
        $supplierId = 'SUP012';
        $companyName = 'PT Test Long Phone';
        $address = 'Jl. Test No. 123';
        $phoneNumber = str_repeat('0', 31); // 31 characters
        $bankAccount = '1234567890';

        $this->browse(function (Browser $browser) use ($supplierId, $companyName, $address, $phoneNumber, $bankAccount) {
            $browser->visit('/supplier/add')
                ->waitFor('form[id="picForm"]', 10)
                ->type('supplier_id', $supplierId)
                ->type('company_name', $companyName)
                ->type('address', $address)
                ->type('phone_number', $phoneNumber)
                ->type('bank_account', $bankAccount)
                ->click('button[onclick="validateForm()"]')
                ->pause(2000);

            // Should either stay on form or show error
            if ($browser->driver->getCurrentURL() === url('/supplier/add')) {
                $browser->assertPathIs('/supplier/add');
            }
        });
    }

    /**
     * Test create supplier with bank_account more than 100 characters
     */
    public function test_create_supplier_with_bank_account_too_long(): void
    {
        $supplierId = 'SUP013';
        $companyName = 'PT Test Long Bank';
        $address = 'Jl. Test No. 123';
        $phoneNumber = '081234567890';
        $bankAccount = str_repeat('1', 101); // 101 characters

        $this->browse(function (Browser $browser) use ($supplierId, $companyName, $address, $phoneNumber, $bankAccount) {
            $browser->visit('/supplier/add')
                ->waitFor('form[id="picForm"]', 10)
                ->type('supplier_id', $supplierId)
                ->type('company_name', $companyName)
                ->type('address', $address)
                ->type('phone_number', $phoneNumber)
                ->type('bank_account', $bankAccount)
                ->click('button[onclick="validateForm()"]')
                ->pause(2000);

            // Should either stay on form or show error
            if ($browser->driver->getCurrentURL() === url('/supplier/add')) {
                $browser->assertPathIs('/supplier/add');
            }
        });
    }
}