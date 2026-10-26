<?php

namespace Tests\Feature;

use App\Models\Mobil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobilApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mobil::create([
            'nama' => 'Avanza 1.5 G CVT',
            'brand' => 'Toyota',
            'harga' => 275000000,
            'tahun' => 2024,
            'transmisi' => 'CVT',
            'bahan_bakar' => 'Bensin',
            'status' => 'tersedia',
            'foto' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341',
        ]);

        Mobil::create([
            'nama' => 'Civic RS Turbo',
            'brand' => 'Honda',
            'harga' => 615000000,
            'tahun' => 2024,
            'transmisi' => 'CVT',
            'bahan_bakar' => 'Bensin',
            'status' => 'sold',
            'foto' => 'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2',
        ]);
    }

    public function test_can_get_all_mobils(): void
    {
        $response = $this->getJson('/api/mobil');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'nama',
                        'brand',
                        'harga',
                        'tahun',
                        'transmisi',
                        'bahan_bakar',
                        'status',
                        'foto',
                    ]
                ]
            ]);

        $this->assertEquals('Daftar mobil berhasil diambil', $response->json('message'));
        $this->assertCount(2, $response->json('data'));
    }

    public function test_can_search_and_filter_mobils(): void
    {
        // Filter by brand
        $resBrand = $this->getJson('/api/mobil?brand=Toyota');
        $resBrand->assertStatus(200);
        $this->assertCount(1, $resBrand->json('data'));
        $this->assertEquals('Toyota', $resBrand->json('data.0.brand'));

        // Filter by status
        $resStatus = $this->getJson('/api/mobil?status=sold');
        $resStatus->assertStatus(200);
        $this->assertCount(1, $resStatus->json('data'));
        $this->assertEquals('sold', $resStatus->json('data.0.status'));

        // Search query
        $resSearch = $this->getJson('/api/mobil?search=Civic');
        $resSearch->assertStatus(200);
        $this->assertCount(1, $resSearch->json('data'));
        $this->assertEquals('Civic RS Turbo', $resSearch->json('data.0.nama'));
    }

    public function test_can_get_single_mobil_detail(): void
    {
        $mobil = Mobil::first();

        $response = $this->getJson('/api/mobil/' . $mobil->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Detail data mobil berhasil diambil',
                'data' => [
                    'id' => $mobil->id,
                    'nama' => $mobil->nama,
                    'brand' => $mobil->brand,
                ]
            ]);
    }

    public function test_get_single_mobil_not_found_returns_404(): void
    {
        $response = $this->getJson('/api/mobil/99999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Data mobil tidak ditemukan',
            ]);
    }

    public function test_can_create_new_mobil(): void
    {
        $payload = [
            'nama' => 'Ioniq 5 Signature Long Range',
            'brand' => 'Hyundai',
            'harga' => 785000000,
            'tahun' => 2023,
            'transmisi' => 'Matic',
            'bahan_bakar' => 'Listrik',
            'status' => 'tersedia',
            'foto' => 'https://images.unsplash.com/photo-1563720223185-11003d516935',
        ];

        $response = $this->postJson('/api/mobil', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'nama',
                    'brand',
                    'harga',
                    'tahun',
                    'transmisi',
                    'bahan_bakar',
                    'status',
                    'foto',
                ]
            ]);

        $this->assertEquals('Data mobil berhasil ditambahkan', $response->json('message'));
        $this->assertDatabaseHas('mobils', [
            'nama' => 'Ioniq 5 Signature Long Range',
            'brand' => 'Hyundai',
        ]);
    }

    public function test_create_mobil_validation_error_returns_422(): void
    {
        $response = $this->postJson('/api/mobil', []);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['nama', 'brand', 'harga', 'tahun', 'transmisi', 'bahan_bakar', 'status', 'foto']
            ]);
    }

    public function test_can_update_mobil(): void
    {
        $mobil = Mobil::first();

        $payload = [
            'nama' => 'Avanza 1.5 G CVT Facelift',
            'harga' => 280000000,
            'status' => 'sold',
        ];

        $response = $this->putJson('/api/mobil/' . $mobil->id, $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Data mobil berhasil diperbarui',
                'data' => [
                    'id' => $mobil->id,
                    'nama' => 'Avanza 1.5 G CVT Facelift',
                    'harga' => 280000000,
                    'status' => 'sold',
                ]
            ]);

        $this->assertDatabaseHas('mobils', [
            'id' => $mobil->id,
            'nama' => 'Avanza 1.5 G CVT Facelift',
            'status' => 'sold',
        ]);
    }

    public function test_update_mobil_not_found_returns_404(): void
    {
        $response = $this->putJson('/api/mobil/99999', [
            'nama' => 'Non Existent Car',
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Data mobil tidak ditemukan',
            ]);
    }

    public function test_can_delete_mobil(): void
    {
        $mobil = Mobil::first();

        $response = $this->deleteJson('/api/mobil/' . $mobil->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Data mobil berhasil dihapus',
            ]);

        $this->assertDatabaseMissing('mobils', [
            'id' => $mobil->id,
        ]);
    }

    public function test_delete_mobil_not_found_returns_404(): void
    {
        $response = $this->deleteJson('/api/mobil/99999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Data mobil tidak ditemukan',
            ]);
    }

    public function test_docs_api_route_is_accessible(): void
    {
        $response = $this->get('/docs/api');

        $response->assertStatus(200);
    }
}
