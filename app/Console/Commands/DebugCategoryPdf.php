<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use Barryvdh\DomPDF\Facade\Pdf;

class DebugCategoryPdf extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:debug-category-pdf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug the category PDF generation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Category::getCategory()...');

        try {
            $categories = Category::getCategory();
            $this->info('Categories count: ' . $categories->count());

            if ($categories->count() > 0) {
                $this->info('First category: ' . $categories->first()->category);
                $this->info('Parent relationship: ' . ($categories->first()->parent ? $categories->first()->parent->category : 'No parent'));
            }

            $this->info('Testing PDF generation...');
            $pdf = Pdf::loadView('product.category.pdf', compact('categories'));
            $this->info('PDF generated successfully');

            // Save PDF to storage for inspection
            $pdf->save(storage_path('app/debug_category_report.pdf'));
            $this->info('PDF saved to storage/app/debug_category_report.pdf');

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ':' . $e->getLine());
        }
    }
}
