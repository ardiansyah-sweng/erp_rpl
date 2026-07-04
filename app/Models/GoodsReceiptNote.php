public static function addGoodsReceiptNote($data)
{
    return \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
        $grn = self::create($data);

        $poItem = \Illuminate\Support\Facades\DB::table('purchase_order_details')
                    ->where('po_number', $data['po_number'])
                    ->where('product_id', $data['product_id'])
                    ->first();

        $totalReceived = \Illuminate\Support\Facades\DB::table('goods_receipt_note_details')
                            ->where('po_number', $data['po_number'])
                            ->where('product_id', $data['product_id'])
                            ->sum('delivered_quantity');

        $status = ($totalReceived >= $poItem->quantity) ? 'Completed' : 'Partially Received';

        \Illuminate\Support\Facades\DB::table('purchase_orders')
            ->where('po_number', $data['po_number'])
            ->update(['status' => $status]);

        return $grn;
    });
}