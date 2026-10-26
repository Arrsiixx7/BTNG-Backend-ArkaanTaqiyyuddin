<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MobilResource;
use App\Models\Mobil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobilController extends Controller
{
    /**
     * Mengambil seluruh daftar mobil.
     *
     * Mendukung query pencarian (?search=...) dan filter berdasarkan brand, status, bahan bakar, serta transmisi.
     *
     * @response 200 {
     *   "message": "Daftar mobil berhasil diambil",
     *   "data": [{
     *     "id": 1,
     *     "nama": "Avanza 1.5 G CVT",
     *     "brand": "Toyota",
     *     "harga": 275000000,
     *     "tahun": 2024,
     *     "transmisi": "CVT",
     *     "bahan_bakar": "Bensin",
     *     "status": "tersedia",
     *     "foto": "https://images.unsplash.com/photo-1549399542-7e3f8b79c341"
     *   }]
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $query = Mobil::query();

        // Search fleksibel: nama atau brand
        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan brand
        if ($request->filled('brand')) {
            $query->where('brand', $request->query('brand'));
        }

        // Filter berdasarkan status (tersedia/sold)
        if ($request->filled('status')) {
            $query->where('status', strtolower($request->query('status')));
        }

        // Filter berdasarkan jenis bahan bakar
        if ($request->filled('bahan_bakar')) {
            $query->where('bahan_bakar', $request->query('bahan_bakar'));
        }

        // Filter berdasarkan jenis transmisi
        if ($request->filled('transmisi')) {
            $query->where('transmisi', $request->query('transmisi'));
        }

        // Default urutan terbaru
        $mobils = $query->latest('id')->get();

        return response()->json([
            'message' => 'Daftar mobil berhasil diambil',
            'data' => MobilResource::collection($mobils),
        ], 200);
    }

    /**
     * Menambahkan unit mobil baru.
     *
     * Validasi otomatis seluruh atribut mobil sebelum disimpan ke basis data.
     *
     * @response 201 {
     *   "message": "Data mobil berhasil ditambahkan",
     *   "data": {
     *     "id": 2,
     *     "nama": "Civic RS Turbo",
     *     "brand": "Honda",
     *     "harga": 615000000,
     *     "tahun": 2024,
     *     "transmisi": "CVT",
     *     "bahan_bakar": "Bensin",
     *     "status": "tersedia",
     *     "foto": "https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2"
     *   }
     * }
     * @response 422 {
     *   "message": "The nama field is required.",
     *   "errors": {
     *     "nama": ["Kolom nama mobil wajib diisi."]
     *   }
     * }
     */
    public function store(Request $request): JsonResponse
    {
        $currentYear = (int) date('Y');

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'brand' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'tahun' => "required|integer|min:1900|max:" . ($currentYear + 1),
            'transmisi' => 'required|string|max:50',
            'bahan_bakar' => 'required|string|max:50',
            'status' => 'required|string|in:tersedia,sold,Tersedia,Sold',
            'foto' => 'required|string|url',
        ], [
            'nama.required' => 'Kolom nama mobil wajib diisi.',
            'brand.required' => 'Kolom brand mobil wajib diisi.',
            'harga.required' => 'Kolom harga wajib diisi.',
            'harga.numeric' => 'Kolom harga harus berupa angka.',
            'harga.min' => 'Kolom harga tidak boleh bernilai negatif.',
            'tahun.required' => 'Kolom tahun perakitan wajib diisi.',
            'tahun.integer' => 'Kolom tahun perakitan harus berupa angka bulat.',
            'transmisi.required' => 'Kolom transmisi wajib diisi.',
            'bahan_bakar.required' => 'Kolom bahan bakar wajib diisi.',
            'status.required' => 'Kolom status ketersediaan wajib diisi.',
            'status.in' => 'Status hanya boleh berisi tersedia atau sold.',
            'foto.required' => 'Kolom tautan foto wajib diisi.',
            'foto.url' => 'Format foto harus berupa tautan URL valid.',
        ]);

        $validated['status'] = strtolower($validated['status']);

        $mobil = Mobil::create($validated);

        return response()->json([
            'message' => 'Data mobil berhasil ditambahkan',
            'data' => new MobilResource($mobil),
        ], 201);
    }

    /**
     * Mengambil detail satu unit mobil berdasarkan ID.
     *
     * @response 200 {
     *   "message": "Detail data mobil berhasil diambil",
     *   "data": {
     *     "id": 1,
     *     "nama": "Avanza 1.5 G CVT",
     *     "brand": "Toyota",
     *     "harga": 275000000,
     *     "tahun": 2024,
     *     "transmisi": "CVT",
     *     "bahan_bakar": "Bensin",
     *     "status": "tersedia",
     *     "foto": "https://images.unsplash.com/photo-1549399542-7e3f8b79c341"
     *   }
     * }
     * @response 404 {
     *   "message": "Data mobil tidak ditemukan"
     * }
     */
    public function show(string|int $id): JsonResponse
    {
        $mobil = Mobil::find($id);

        if (!$mobil) {
            return response()->json([
                'message' => 'Data mobil tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'message' => 'Detail data mobil berhasil diambil',
            'data' => new MobilResource($mobil),
        ], 200);
    }

    /**
     * Memperbarui informasi data mobil yang tersimpan.
     *
     * Menerima pembaruan parsial maupun penuh untuk atribut mobil.
     *
     * @response 200 {
     *   "message": "Data mobil berhasil diperbarui",
     *   "data": {
     *     "id": 1,
     *     "nama": "Avanza 1.5 G CVT Facelift",
     *     "brand": "Toyota",
     *     "harga": 280000000,
     *     "tahun": 2024,
     *     "transmisi": "CVT",
     *     "bahan_bakar": "Bensin",
     *     "status": "tersedia",
     *     "foto": "https://images.unsplash.com/photo-1549399542-7e3f8b79c341"
     *   }
     * }
     * @response 404 {
     *   "message": "Data mobil tidak ditemukan"
     * }
     */
    public function update(Request $request, string|int $id): JsonResponse
    {
        $mobil = Mobil::find($id);

        if (!$mobil) {
            return response()->json([
                'message' => 'Data mobil tidak ditemukan',
            ], 404);
        }

        $currentYear = (int) date('Y');

        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'brand' => 'sometimes|required|string|max:100',
            'harga' => 'sometimes|required|numeric|min:0',
            'tahun' => "sometimes|required|integer|min:1900|max:" . ($currentYear + 1),
            'transmisi' => 'sometimes|required|string|max:50',
            'bahan_bakar' => 'sometimes|required|string|max:50',
            'status' => 'sometimes|required|string|in:tersedia,sold,Tersedia,Sold',
            'foto' => 'sometimes|required|string|url',
        ], [
            'nama.required' => 'Kolom nama mobil wajib diisi.',
            'brand.required' => 'Kolom brand mobil wajib diisi.',
            'harga.required' => 'Kolom harga wajib diisi.',
            'harga.numeric' => 'Kolom harga harus berupa angka.',
            'tahun.required' => 'Kolom tahun perakitan wajib diisi.',
            'tahun.integer' => 'Kolom tahun perakitan harus berupa angka bulat.',
            'transmisi.required' => 'Kolom transmisi wajib diisi.',
            'bahan_bakar.required' => 'Kolom bahan bakar wajib diisi.',
            'status.required' => 'Kolom status ketersediaan wajib diisi.',
            'status.in' => 'Status hanya boleh berisi tersedia atau sold.',
            'foto.required' => 'Kolom tautan foto wajib diisi.',
            'foto.url' => 'Format foto harus berupa tautan URL valid.',
        ]);

        if (isset($validated['status'])) {
            $validated['status'] = strtolower($validated['status']);
        }

        $mobil->update($validated);

        return response()->json([
            'message' => 'Data mobil berhasil diperbarui',
            'data' => new MobilResource($mobil->fresh()),
        ], 200);
    }

    /**
     * Menghapus data unit mobil dari basis data.
     *
     * @response 200 {
     *   "message": "Data mobil berhasil dihapus"
     * }
     * @response 404 {
     *   "message": "Data mobil tidak ditemukan"
     * }
     */
    public function destroy(string|int $id): JsonResponse
    {
        $mobil = Mobil::find($id);

        if (!$mobil) {
            return response()->json([
                'message' => 'Data mobil tidak ditemukan',
            ], 404);
        }

        $mobil->delete();

        return response()->json([
            'message' => 'Data mobil berhasil dihapus',
        ], 200);
    }
}
