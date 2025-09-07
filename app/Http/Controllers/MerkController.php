<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMerkRequest;
use App\Http\Requests\UpdateMerkRequest;
use App\Http\Resources\MerkCollection;
use App\Http\Resources\MerkResource;
use App\Models\Merk;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Constants\Messages;
use App\Constants\MerkColumns;

/**
 * MerkController
 *
 * Handles all CRUD operations for Merk (Brand) management
 * following Laravel best practices and industry standards.
 */
class MerkController extends Controller
{
    public function getMerkById($id)
    {
        $merk = (new Merk())->getMerkByID($id);

        if (!$merk)
        {
            return abort(404, 'Merk tidak ditemukan');
        }

        return view('merk.detail', compact('merk'));

    }

    public function updateMerk(Request $request, $id)
    {
       // Validasi input
       $request->validate([
        'id' => 'required|integer',
        'merk' => 'required|string|max:100',
        ]);

           // Update data merk
        $updatedMerk = Merk::updateMerk($request->id, $request->only(['merk']));

        if (!$updatedMerk)
        {
            return response()->json(['message' => 'Data Merk Tidak Tersedia'], 404);
        }

        return response()->json([ 'message' => 'Data Merk berhasil diperbarui','data' => $updatedMerk, ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMerkRequest $request, $id)
    {
        try {
            $merk = Merk::find($id);

            if (!$merk) {
                $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');

                if ($isApiRequest) {
                    return response()->json([
                        'success' => false,
                        'message' => Messages::MERK_NOT_FOUND,
                    ], 404);
                }
                return redirect()->back()->withInput()->with('error', Messages::MERK_NOT_FOUND);
            }

            $merk->update([
                MerkColumns::MERK => $request->input('merk'),
                MerkColumns::IS_ACTIVE => $request->boolean('is_active'),
            ]);

            $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');

            if ($isApiRequest) {
                return response()->json([
                    'success' => true,
                    'message' => Messages::MERK_UPDATED,
                    'data' => new MerkResource($merk->fresh())
                ]);
            }

            return redirect()->route('merk.index')->with('success', Messages::MERK_UPDATED);

        } catch (\Exception $e) {
            $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');

            if ($isApiRequest) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $isApiRequest = $request->wantsJson() || str_starts_with($request->route()->getName() ?? '', 'api.');

        $merk = Merk::find($id);

        if (!$merk) {
            if ($isApiRequest) {
                return response()->json([
                    'success' => false,
                    'message' => Messages::MERK_NOT_FOUND
                ], 404);
            }
            return redirect()->route('merk.index')->with('error', Messages::MERK_NOT_FOUND);
        }

        $deleted = $merk->delete();

        if ($deleted) {
        return redirect()->back()->with('success', 'Merk berhasil dihapus.');
        }
        else {
        return redirect()->back()->with('error', 'Merk gagal dihapus.');
        }
    }
    public function addMerk(Request $request)
    {
        // Convert to use proper validation
        $validatedData = $request->validate([
            'merk' => 'required|string|max:100',
            'active' => 'nullable|boolean',
        ]);

        return $this->store(new StoreMerkRequest([
            'merk_name' => $validatedData['merk'],
            'is_active' => $validatedData['active'] ?? true
        ]));
    }

    public function updateMerk(Request $request, $id)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'merk' => 'required|string|max:100',
        ]);

        return $this->update(new UpdateMerkRequest([
            'merk_name' => $validatedData['merk']
        ]), $id);
    }

    public function deleteMerk($id)
    {
        return $this->destroy(request(), $id);
    }
}
