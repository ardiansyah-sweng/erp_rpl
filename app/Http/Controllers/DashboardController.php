<?php

namespace App\Http\Controllers;

use App\Constants\ItemColumns;
use App\Models\AssortmentProduction;
use App\Models\Branch;
use App\Models\Category;
use App\Models\GoodsReceiptNote;
use App\Models\GoodsReturn;
use App\Models\Item;
use App\Models\Merk;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $totalPO = PurchaseOrder::countPurchaseOrder();
        $grnThisMonth = GoodsReceiptNote::whereMonth('delivery_date', $currentMonth)
            ->whereYear('delivery_date', $currentYear)
            ->count();
        $productionStats = AssortmentProduction::countProduction();
        $returnsThisMonth = GoodsReturn::whereMonth('return_date', $currentMonth)
            ->whereYear('return_date', $currentYear)
            ->count();

        $poStatusBreakdown = PurchaseOrder::countStatusPO();
        $monthlyTrend = PurchaseOrder::getMonthlyTrend(6);
        $topSuppliers = Supplier::getTopSuppliersByPoCount(5);

        $totalSupplier = Supplier::countSupplier();
        $totalBranch = Branch::countBranch();
        $branchStats = Branch::getStatistics();
        $totalWarehouse = Warehouse::countWarehouse();
        $activeWarehouse = Warehouse::countActiveWarehouse();
        $merkStats = Merk::getStatistics();
        $totalItem = Item::countItem();
        $totalCategory = Category::count();
        $totalUsers = User::count();

        $lowStockItems = Item::where(ItemColumns::STOCK_UNIT, '<', 10)
            ->orderBy(ItemColumns::STOCK_UNIT, 'asc')
            ->limit(5)
            ->get([ItemColumns::SKU, ItemColumns::NAME, ItemColumns::STOCK_UNIT]);

        $lowStockCount = Item::where(ItemColumns::STOCK_UNIT, '<', 10)->count();

        $totalStockValue = Item::sum(DB::raw('stock_unit * ' . ItemColumns::BASE_PRICE));

        $logTable = config('db_constants.table.log_matory', 'log_material_inventory');
        $logCol = config('db_constants.column.log_matory', []);
        $recentStockLogs = DB::table($logTable)
            ->latest($logCol['created'] ?? 'created_at')
            ->limit(5)
            ->get([
                $logCol['log_id'] ?? 'log_id',
                $logCol['sku'] ?? 'sku',
                $logCol['old_stock'] ?? 'old_stock',
                $logCol['new_stock'] ?? 'new_stock',
                $logCol['created'] ?? 'created_at',
            ]);

        $outstandingPOs = PurchaseOrder::with('supplier')
            ->whereIn('status', ['Approved', 'Partially Delivered'])
            ->where('order_date', '<', Carbon::now()->subDays(7))
            ->orderBy('order_date', 'asc')
            ->limit(5)
            ->get(['po_number', 'supplier_id', 'order_date', 'status', 'total']);

        return view('dashboard', compact(
            'totalPO',
            'grnThisMonth',
            'productionStats',
            'returnsThisMonth',
            'poStatusBreakdown',
            'monthlyTrend',
            'topSuppliers',
            'totalSupplier',
            'totalBranch',
            'branchStats',
            'totalWarehouse',
            'activeWarehouse',
            'merkStats',
            'totalItem',
            'totalCategory',
            'totalUsers',
            'lowStockItems',
            'lowStockCount',
            'totalStockValue',
            'recentStockLogs',
            'outstandingPOs'
        ));
    }
}