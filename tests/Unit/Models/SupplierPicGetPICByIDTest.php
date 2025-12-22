<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\SupplierPic;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupplierPicGetPICByIDTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_pic_by_id_returns_correct_record()
    {
        
        $pic = SupplierPic::create([
        
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone_number' => '081234567890',
            'supplier_id' => 'SUP001',
            'assigned_date' => '2024-01-01',
            'active' => 1,
            'avatar' => 'http://placehold.it/100x100', 
        ]);

        $result = SupplierPic::getPICByID($pic->id); 

        
        $this->assertNotNull($result);
        $this->assertEquals($pic->id, $result->id); 
        $this->assertEquals('John Doe', $result->name);
        $this->assertEquals('john@example.com', $result->email);
        $this->assertEquals('SUP001', $result->supplier_id);
    }

    /** @test */
    public function get_pic_by_id_returns_null_for_non_existent_id()
    {
        
        $result = SupplierPic::getPICByID(999999); 

        
        $this->assertNull($result);
    }
    
    /** @test */
    public function get_pic_by_id_works_with_integer_id()
    {
        
        $pic = SupplierPic::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone_number' => '081234567891',
            'supplier_id' => 'SUP002',
            'assigned_date' => '2024-02-01',
            'active' => 1,
            'avatar' => 'http://placehold.it/100x100',
        ]);

        // Act & Assert
        $result = SupplierPic::getPICByID($pic->id);
        $this->assertNotNull($result);
        $this->assertEquals($pic->id, $result->id);
        $this->assertEquals('Jane Smith', $result->name);
    }
    
    /** @test */
    public function get_pic_by_id_returns_complete_record()
    {
        // Arrange
        $picData = [
            'name' => 'Complete User',
            'email' => 'complete@example.com',
            'phone_number' => '081234567892',
            'supplier_id' => 'SUP003',
            'assigned_date' => '2024-03-15',
            'active' => 1,
            'avatar' => 'custom-avatar.jpg',
        ];
        
        $pic = SupplierPic::create($picData);

        // Act
        $result = SupplierPic::getPICByID($pic->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($picData['name'], $result->name);
        $this->assertEquals($picData['email'], $result->email);
        $this->assertEquals($picData['phone_number'], $result->phone_number);
        $this->assertEquals($picData['supplier_id'], $result->supplier_id);
        $this->assertEquals($picData['assigned_date'], $result->assigned_date);
        $this->assertEquals($picData['avatar'], $result->avatar);
        $this->assertEquals($picData['active'], $result->active);
    }
    
    /** @test */
    public function get_pic_by_id_with_zero_id()
    {
        $result = SupplierPic::getPICByID(0);
        $this->assertNull($result);
    }
    
    /** @test */
    public function get_pic_by_id_with_negative_id()
    {
        $result = SupplierPic::getPICByID(-1);
        $this->assertNull($result);
    }
}