<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Constants\CategoryColumns;
use App\Constants\Messages;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        // $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');
        
        // if ($isApiRequest) {
        //     // API Response with advanced filtering
        //     $filters = [
        //         'search' => $search,
        //         'name' => $request->get('name'),
        //         'description' => $request->get('description'),
        //         'is_active' => $request->has('is_active') ? $request->boolean('is_active') : null,
        //         'sort_by' => $request->get('sort_by', CategoryColumns::CREATED_AT),
        //         'sort_order' => $request->get('sort_order', 'desc'),
        //     ];

        //     $query = Merk::searchWithFilters($filters);
        //     $merks = $query->paginate($request->get('per_page', 15));
            
        //     return new MerkCollection($merks);
        // }

        // Web Response with PDF export support
        $categories = Category::getAllCategory($search);

        // if ($request->has('export') && $request->input('export') === 'pdf') {
        //     $pdf = Pdf::loadView('category.report', compact('categories'));
        //     return $pdf->stream('report-category.pdf');
        // }

        return view('category.index', compact('categories', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::getParentCategories(); // Only parent categories (parent_id = null)
        return view('category.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $validatedData = $request->getValidatedData();
            $category = Category::create($validatedData);

            return redirect()->route('categories.index')->with('success', Messages::CATEGORY_CREATED);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', Messages::ACTION_FAILED);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::with('parent', 'children')->find($id);
        
        if (!$category) {
            return redirect()->route('categories.index')->with('error', Messages::CATEGORY_NOT_FOUND);
        }
        
        return view('category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = Category::find($id);
        
        if (!$category) {
            return redirect()->route('categories.index')->with('error', Messages::CATEGORY_NOT_FOUND);
        }
        
        // Only get parent categories (parent_id = null) excluding current category
        // $categories = Category::whereNull('parent_id')
        //                     ->where('is_active', 1)
        //                     ->where('id', '!=', $id)
        //                     ->orderBy('category', 'asc')
        //                     ->get();
        $categories = Category::getParentCategories()->where('id', '!=', $id);
        return view('category.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        try {
            $category = Category::find($id);
            
            if (!$category) {
                return redirect()->route('categories.index')->with('error', Messages::CATEGORY_NOT_FOUND);
            }
            
            $validatedData = $request->getValidatedData();
            $category->update($validatedData);

            return redirect()->route('categories.index')->with('success', Messages::CATEGORY_UPDATED);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', Messages::ACTION_FAILED);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $category = Category::find($id);
            
            if (!$category) {
                return redirect()->route('categories.index')->with('error', Messages::CATEGORY_NOT_FOUND);
            }
            
            // Check if category has children
            if ($category->children()->count() > 0) {
                return redirect()->route('categories.index')->with('error', Messages::CATEGORY_HAS_CHILDREN);
            }
            
            // Check if category is used by products
            if ($category->products()->count() > 0) {
                return redirect()->route('categories.index')->with('error', Messages::CATEGORY_IN_USE);
            }

            $category->delete();
            return redirect()->route('categories.index')->with('success', Messages::CATEGORY_DELETED);
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', Messages::CATEGORY_DELETE_FAILED);
        }
    }

    public function addCategory(Request $request)
    {
        $request->validate([
            'category' => 'required|string|min:3|unique:category,category',
            'parent_id' => 'nullable|integer',
            'active' => 'required|boolean'
        ]);
        $category = new Category();
        $category->addCategory([
            'category' => $request->category,
            'parent_id' => $request->parent_id ?? 0,
            'active' => $request->active,
        ]);

        return redirect()->route('category.list')->with('success', 'Kategori berhasil ditambahkan!');
    }
    public function getCategoryList()
    {
        $category = Category::with('parent')->paginate(10);
        return view('product.category.list', compact('category'));
    }
    public function printCategoryPDF()
    {
        $categories = Category::getCategory(); // kita tambahkan method ini di bawah
        $pdf = Pdf::loadView('product.category.pdf', compact('categories'));
        return $pdf->stream('laporan_kategori.pdf');
    }

    public function updateCategory(Request $request, $id)
    {
        $validated = $request->validate([
            'category' => 'required|string|min:3',
            'parent_id' => 'nullable|integer|exists:category,id',
            'active' => 'required|boolean'
        ]);

        $updatedCategory = Category::updateCategory($id, $request->only(['category', 'parent_id', 'active']));

        if (!$updatedCategory) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        return redirect()->route('category.edit', $id)->with('success', 'Kategori berhasil diupdate');
    }

    public function updateCategoryById($id)
    {
        $category = Category::getCategoryById($id);
        if (!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        return view('category.edit', compact('category'));
    }

    public function getCategoryById($id)
    {
        $category = Category::getCategoryById($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        //return response()->json($category);
        return view('product.category.detail', compact('category'));
        //apabila halaman detail kategori sudah ada harap untuk di uncomment return view
        //dan return response nya di hapus
    }
    //Search Category 
    public function searchCategory(Request $request)
    {
        $keyword = $request->input('q');

        $category = Category::when($keyword, function ($query) use ($keyword) {
            $query->where('category', 'like', '%' . $keyword . '%');
        })->get();

        return view('category.list', compact('category'));
    }


    // delete category
    public function deleteCategory($id)
    {
        $deleted = Category::deleteCategoryById($id);

        if ($deleted) {
            return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
        } else {
            return redirect()->back()->with('error', 'Kategori tidak ditemukan atau gagal dihapus.');
        }
    }
    public function getCategoryByParent($parentId)
    {
        // Panggil method yang diizinkan
        $allCategories = Category::getCategory();
        // Filter data yang parent_id-nya sesuai
        $filtered = $allCategories->where('parent_id', $parentId)->values();

        if ($filtered->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada kategori dengan parent ID tersebut'
            ], 404);
        }

        return response()->json($filtered, 200);
    }

}