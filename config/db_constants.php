<?php

$master = [
    'id' => 'id',
    'category' => 'category',
    'created' => 'created_at',
    'updated' => 'updated_at',
    'po_number' => 'po_number',
    'supplier_id' => 'supplier_id'
];

return [
    'table' => [
        'category'                  => $master['category'],
        'grn'                       => 'goods_receipt_not',
        'item'                      => 'item',
        'log_avg_base_price'        => 'log_avg_base_price',
        'log_base_price_supplier'   => 'log_base_price_supplier_product',
        'log_stock'                 => 'log_stock',
        'master_product'            => 'master_product',
        'po'                        => 'purchase_order',
        'po_detail'                 => 'purchase_order_detail',
        'product'                   => 'product',
        'supplier'                  => 'supplier',
        'supplier_pic'              => 'supplier_pic',
        'supplier_product'          => 'supplier_product',
    ],
    'column' => [
        'category' => [
            'id'                    => $master['id'],           #tinyInteger
            'category'              => $master['category'],     #string[50]
            'created_at'            => $master['created'],
            'updated_at'            => $master['updated']
        ],
        'grn' => [
            'id'                    => $master['id'],
            'po_number'             => $master['po_number'],
            'created_at'            => $master['created'],
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
            'supplier_id'           => $master['supplier_id'],
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
            'po_number'             => $master['po_number'],
            'supplier_id'           => $master['supplier_id'],
            'total'                 => 'total',
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ],
        'po_detail' => [
            'po_number'             => $master['po_number'],
            'product_id'            => 'product_id',
            'quantity'              => 'quantity',
            'amount'                => 'amount',
            'received_days'         => 'received_days',
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ],
        'product' => [
            'id'                    => 'product_id',            #char[6]
            'name'                  => 'product_name',          #string[35]
            'type'                  => 'product_type',          #finished, raw material
            'category'              => 'product_category',      #tinyInteger
            'desc'                  => 'product_description',   #string[255]
            'created'               => $master['created'],
            'updated'               => $master['updated']
        ],
        'item' => [
            'id'                    => 'id',
            'sku'                   => 'sku',
            'name'                  => 'item_name',
            // 'category_id'           => 'category_id',
            // 'description'           => 'description',
            'measurement'           => 'measurement_unit',
            //'stock'                 => 'current_stock',
            'base_price'            => 'avg_base_price', #raw material from supplier
            'selling_price'         => 'selling_price', #finished from bill of material
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ],
        'inventory' => [
            'sku'                   => 'sku'
        ],
        'supplier' => [
            'supplier_id'           => $master['supplier_id'],
            'company_name'          => 'company_name',
            'address'               => 'address',
            'phone_number'          => 'phone_number',
            'bank_account'          => 'bank_account',
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ],
        'supplier_pic' => [
            'supplier_id'           => $master['supplier_id'],
            'name'                  => 'name',
            'phone_number'          => 'phone_number',
            'email'                 => 'email',
            'assigned_date'         => 'assigned_date',
            'status'                => 'status',
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ],
        'supplier_product' => [
            'supplier_id'           => $master['supplier_id'],
            'company_name'          => 'company_name',
            'product_id'            => 'product_id',
            'product_name'          => 'product_name',
            'base_price'            => 'base_price',
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ]
    ]
];