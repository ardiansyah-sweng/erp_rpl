<?php

$masterColumn = [
    'id' => 'id',
    'created' => 'created_at',
    'updated' => 'updated_at',
    'po_number' => 'po_number',
    'supplier_id' => 'supplier_id'
];

return [
    'table' => [
        'category'                  => 'category',
        'grn'                       => 'goods_receipt_not',
        'log_avg_base_price'        => 'log_avg_base_price',
        'log_base_price_supplier'   => 'log_base_price_supplier_product',
        'log_stock'                 => 'log_stock',
        'po'                        => 'purchase_order',
        'po_detail'                 => 'purchase_order_detail',
        'product'                   => 'product',
        'supplier'                  => 'supplier',
        'supplier_pic'              => 'supplier_pic',
        'supplier_product'          => 'supplier_product',
    ],
    'column' => [
        'category' => [
            'id'                    => $masterColumn['id'],
            'category'              => 'category',
            'created_at'            => $masterColumn['created'],
            'updated_at'            => $masterColumn['updated']
        ],
        'grn' => [
            'id'                    => $masterColumn['id'],
            'po_number'             => $masterColumn['po_number'],
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ],
        'log_avg_base_price' => [
            'id'                    => 'id',
            'product_id'            => 'product_id',
            'then_avg_base_price'   => 'then_avg_base_price',
            'now_avg_base_price'    => 'now_avg_base_price',
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ],
        'log_base_price_supplier' => [
            'id'                    => 'id',
            'supplier_id'           => $masterColumn['supplier_id'],
            'product_id'            => 'product_id',
            'old_base_price'        => 'old_base_price',
            'new_base_price'        => 'new_base_price',
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ],
        'log_stock' => [
            'id'                    => 'id',
            'log_id'                => 'log_id', #po_number or transaction_id
            'product_id'            => 'product_id',
            'old_stock'             => 'old_stock',
            'new_stock'             => 'new_stock',
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ],
        'po' => [
            'po_number'             => $masterColumn['po_number'],
            'supplier_id'           => $masterColumn['supplier_id'],
            'total'                 => 'total',
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ],
        'po_detail' => [
            'po_number'             => $masterColumn['po_number'],
            'product_id'            => 'product_id',
            'quantity'              => 'quantity',
            'amount'                => 'amount',
            'received_days'         => 'received_days',
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ],
        'product' => [
            'product_id'            => 'product_id',
            'name'                  => 'name',
            'category_id'           => 'category_id',
            'description'           => 'description',
            'measurement'           => 'measurement_unit',
            'stock'                 => 'current_stock',
            'base_price'            => 'avg_base_price',
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ],
        'supplier' => [
            'supplier_id'           => $masterColumn['supplier_id'],
            'company_name'          => 'company_name',
            'address'               => 'address',
            'phone_number'          => 'phone_number',
            'bank_account'          => 'bank_account',
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ],
        'supplier_pic' => [
            'supplier_id'           => $masterColumn['supplier_id'],
            'name'                  => 'name',
            'phone_number'          => 'phone_number',
            'email'                 => 'email',
            'assigned_date'         => 'assigned_date',
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ],
        'supplier_product' => [
            'supplier_id'           => $masterColumn['supplier_id'],
            'company_name'          => 'company_name',
            'product_id'            => 'product_id',
            'product_name'          => 'product_name',
            'base_price'            => 'base_price',
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ]
    ]
];