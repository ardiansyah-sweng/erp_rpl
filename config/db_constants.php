<?php

$master = [
    'id'        => 'id',
    'category'  => 'category',
    'created'   => 'created_at',
    'updated'   => 'updated_at',
    'po_number' => 'po_number',
    'supplier_id' => 'supplier_id'
];

return [
    'table' => [
        'bom'                       => 'bill_of_material',
        'bom_detail'                => 'bom_detail',
        'assort_prod'               => 'assortment_production',
        'assort_prodetail'          => 'assortment_production_detail',
        'branch'                    => 'branch',
        'category'                  => $master['category'],
        'cu'                        => 'conversion_unit',
        'grn'                       => 'goods_receipt_note',
        'item'                      => 'item',
        'log_avg_base_price'        => 'log_avg_base_price',
        'log_base_price_supplier'   => 'log_base_price_supplier_product',
        'master_product'            => 'master_product',
        'log_matory'                => 'log_material_inventory', 
        'matory'                    => 'material_inventory',
        'merk'                      => 'merks',
        'mu'                        => 'measurement_unit',
        'po'                        => 'purchase_order',
        'po_detail'                 => 'purchase_order_detail',
        'products'                   => 'products',
        'supplier'                  => 'supplier',
        'supplier_pic'              => 'supplier_pic',
        'supplier_product'          => 'supplier_product',
        'unit'                      => 'item_unit',
        'whouse'                    => 'warehouse'
    ],
    'column' => [
        'bom' => [
            'id'                    => 'id',
            'bom_id'                => 'bom_id', #char[7] berformat: BOM-001
            'name'                  => 'bom_name',  #string[100]. Disebut juga nama resep.
            'unit'                  => 'measurement_unit', #chat[6]. Satuan dari hasil produksi.
            'sku'                   => 'sku', #string[50]. Kode item sebagai hasil produksi. #ambil dari tabel item
            'total'                 => 'total_cost', #integer. HPP resep/bom ini. Dihitung dari biaya di tabel bom_detail.
            'active'                => 'active', #boolean. Status aktif resep/bom ini.
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ],
        'bom_detail' => [
            'id'                    => 'id',
            'bom_id'                => 'bom_id', #integer. FK dari ID resep/bom.
            'sku'                   => 'sku',   #string[50]. Kode item. #ambil dari tabel item
            'qty'                   => 'quantity', #integer
            'cost'                  => 'cost', #integer. Biaya per item per satuan.
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ],

        'assort_prod' => [
            'id'                    => 'id',
            'prod_no'               => 'production_number',
            'sku'                   => 'sku', #ambil dari tabel item #harus dari tipe produk finished goods (FG)
            'branch'                => 'branch_id', #char[4]. FK dari tabel branch
            'rm_whouse'             => 'rm_whouse_id', #char[4]. FK dari tabel warehouse
            'fg_whouse'             => 'fg_whouse_id', #char[4]. FK dari tabel warehouse
            'prod_date'             => 'production_date',
            'finished_date'         => 'finished_date', #default null
            'in_production'         => 'in_production',
            'desc'                  => 'description',
            'created'               => 'created_at',
            'updated'               => 'updated_at'
        ],

        'assort_prodetail' => [
            'id'                    => 'id',
            'prod_no'               => 'production_number',
            'bom_id'                => 'bom_id',
            'bom_qty'               => 'bom_quantity',
            'desc'                  => 'description',
            'created'               => 'created_at',
            'updated'               => 'updated_at'
        ],

        'branch' => [
            'id'                    => 'id',
            'branch_name'           => 'branch_name',
            'branch_address'        => 'branch_address',
            'branch_telephone'      => 'branch_telephone',
            'branch_status'         => 'branch_status',
        ],
        'category' => [
            'id'                    => $master['id'],           #tinyInteger
            'category'              => $master['category'],     #string[50]
            'parent_id'             => 'parent_id',              #tinyInteger
            'active'                => 'active',                 #boolean
            'created_at'            => $master['created'],
            'updated_at'            => $master['updated']
        ],

        'cu' => [
            'id'                    => $master['id'],
            'sku'                   => 'sku', #ambil SKU item
            'muid'                  => 'measurement_unit',
            'val'                   => 'value',
            'isBU'                  => 'is_base_unit',
            'created'               => $master['created'],
            'updated'               => $master['updated']
        ],

        'grn' => [
            'id'                    => $master['id'],
            'po_number'             => $master['po_number'],
            'product_id'            => 'product_id',
            'date'                  => 'delivery_date',
            'qty'                   => 'delivered_quantity',
            'comments'              => 'comments',
            'created_at'            => $master['created'],
            'updated_at'            => 'updated_at'
        ],

        'item' => [
            'id'                    => 'id',
            'prod_id'               => 'product_id', #char[4]. Diambil dari product_id tabel products.
            'sku'                   => 'sku',
            'name'                  => 'item_name',
            // 'category_id'           => 'category_id',
            // 'description'           => 'description',
            'measurement'           => 'measurement_unit',
            //'stock'                 => 'current_stock',
            'base_price'            => 'avg_base_price', #raw material from supplier
            'selling_price'         => 'selling_price', #finished from bill of material
            'purchase_unit'         => 'purchase_unit',
            'sell_unit'             => 'sell_unit',
            'stock_unit'            => 'stock_unit',
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
            'supplier_id'           => $master['supplier_id'],
            'product_id'            => 'product_id',
            'old_base_price'        => 'old_base_price',
            'new_base_price'        => 'new_base_price',
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ],

        'log_matory' => [
            'id'                    => 'id',
            'log_id'                => 'log_id', #grn_number or production_id
            'sku'                   => 'sku',
            'old_stock'             => 'old_stock',
            'new_stock'             => 'new_stock',
            'created_at'            => $master['created'],
            'updated_at'            => $master['updated']
        ],

        'matory' => [
            'id'                    => 'id',
            'sku'                   => 'sku', #ambil dari tabel products
            'stock'                 => 'stock',
            'created_at'            => $master['created'],
            'updated_at'            => $master['updated']
        ],

        'merk' => [
            'id'                    => 'id',
            'merk'                  => 'merk',
            'active'                => 'active',
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ],

        'mu' => [
            'id'                    => 'id',
            'unit'                  => 'unit_name',
            'abbr'                  => 'abbreviation',
            'created'               => 'created_at',
            'updated'               => 'updated_at'
        ],

        'po' => [
            'po_number'             => $master['po_number'],
            'supplier_id'           => $master['supplier_id'],
            'total'                 => 'total',
            'branch_id'             => 'branch_id',
            'order_date'            => 'order_date',
            'status'                => 'status',
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ],
        'po_detail' => [
            'po_number'             => $master['po_number'],
            'product_id'            => 'product_id',
            'base_price'            => 'base_price',
            'quantity'              => 'quantity',
            'amount'                => 'amount',
            'received_days'         => 'received_days',
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ],
        'products' => [
            'id'                    => 'product_id',            #char[6]
            'name'                  => 'product_name',          #string[35]
            'type'                  => 'product_type',          #finished, raw material
            'category'              => 'product_category',      #tinyInteger
            'desc'                  => 'product_description',   #string[255]
            'created'               => $master['created'],
            'updated'               => $master['updated']
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
            'created'            => 'created_at',
            'updated'            => 'updated_at'
        ],
        'supplier_pic' => [
            'id'                    => 'id',
            'supplier_id'           => $master['supplier_id'],
            'name'                  => 'name',
            'phone_number'          => 'phone_number',
            'email'                 => 'email',
            'assigned_date'         => 'assigned_date',
            'active'                => 'active',
            'avatar'                => 'avatar',
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
        ],

        'unit' => [
            'id'                    => 'id',
            'sku'                   => 'sku',
            'unit_id'               => 'unit_id',
            'conversion'            => 'conversion_factor',
            'created_at'            => 'created_at',
            'updated_at'            => 'updated_at'
        ],

        'whouse' => [
            'id'                    => 'id',
            'name'                  => 'warehouse_name',
            'address'               => 'warehouse_address',
            'phone'                 => 'warehouse_telephone',
            'is_rm_whouse'          => 'is_rm_whouse', #boolean
            'is_fg_whouse'          => 'is_fg_whouse', #boolean
            'is_active'             => 'is_active',
            'created'               => 'created_at',
            'updated'               => 'updated_at'
        ]

    ]
];