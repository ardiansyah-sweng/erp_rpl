/**
     * Tes untuk fungsi getMerkByID() yang usang.
     * @test
     */
    public function deprecated_getMerkByID_function_still_works(): void
    {
        // 1. PERSIAPAN: Buat merk dummy
        $merk = Merk::factory()->create();

        // 2. AKSI: Panggil fungsi getMerkByID()
        // Pastikan nama fungsinya SAMA PERSIS dengan yang di Merk.php
        $foundMerk = (new \App\Models\Merk())->getMerkByID($merk->id); // Panggil sebagai non-static jika perlu

        // 3. PENILAIAN: Pastikan hasilnya benar
        $this->assertNotNull($foundMerk);
        $this->assertEquals($merk->id, $foundMerk->id);
    }