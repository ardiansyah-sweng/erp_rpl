<?php

namespace App\Data;

class DummySupplierPIC
{
    public static function getAll()
    {
        return [
            ['name' => 'Ahmad Faiz', 'email' => 'faiz@example.com', 'phone' => '0812-3456-7890'],
            ['name' => 'Budi Santoso', 'email' => 'budi@example.com', 'phone' => '0821-1234-5678'],
            ['name' => 'Citra Lestari', 'email' => 'citra@example.com', 'phone' => '0856-7890-1234'],
        ];
    }
}
