<?php

namespace Tests\Feature;

use App\Models\Pengguna;
use Database\Seeders\PerpustakaanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PerpustakaanSeeder::class);
        Storage::fake('public');
    }

    /**
     * Tamu yang belum login tidak dapat mengakses halaman lihat dan edit profil.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('profile.show'))->assertRedirect(route('login'));
        $this->get(route('profile.edit'))->assertRedirect(route('login'));
    }

    /**
     * Mahasiswa yang terautentikasi dapat melihat profil dan form edit dengan field NIM.
     */
    public function test_mahasiswa_sees_nim_field_in_profile_edit(): void
    {
        $mahasiswa = Pengguna::where('email', 'fauzi.mhs@mncu.ac.id')->firstOrFail();

        $response = $this->actingAs($mahasiswa)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Nomor Induk Mahasiswa (NIM)');
        $response->assertDontSee('Nomor Induk Dosen Nasional (NIDN)');
        $response->assertSee('name="nim"', false);
        $response->assertDontSee('name="nidn"', false);
    }

    /**
     * Dosen yang terautentikasi melihat field NIDN dan tidak melihat field NIM.
     */
    public function test_dosen_sees_nidn_field_in_profile_edit(): void
    {
        $dosen = Pengguna::where('email', 'hendra.gunawan@mncu.ac.id')->firstOrFail();

        $response = $this->actingAs($dosen)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Nomor Induk Dosen Nasional (NIDN)');
        $response->assertDontSee('Nomor Induk Mahasiswa (NIM)');
        $response->assertSee('name="nidn"', false);
        $response->assertDontSee('name="nim"', false);
    }

    /**
     * Admin yang terautentikasi tidak melihat field NIM maupun NIDN.
     */
    public function test_admin_does_not_see_nim_or_nidn_fields(): void
    {
        $admin = Pengguna::where('email', 'arifin@mncu.ac.id')->firstOrFail();

        $response = $this->actingAs($admin)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertDontSee('name="nim"', false);
        $response->assertDontSee('name="nidn"', false);
        $response->assertSee('Peran Administrator Sistem');
    }

    /**
     * Validasi required_if: Mahasiswa wajib mengisi NIM saat memperbarui profil.
     */
    public function test_mahasiswa_requires_nim(): void
    {
        $mahasiswa = Pengguna::where('email', 'fauzi.mhs@mncu.ac.id')->firstOrFail();

        $response = $this->actingAs($mahasiswa)->put(route('profile.update'), [
            'role' => 'mahasiswa',
            'nama_lengkap' => 'Ahmad Fauzi Update',
            'email' => 'fauzi.mhs@mncu.ac.id',
            'alamat_lengkap' => 'Jl. Kebon Jeruk No. 10 Jakarta Barat',
            'no_wa' => '081234567890',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2004-05-12',
            'nik' => '3171010101010001',
            'nim' => '', // Kosong -> harus gagal
        ]);

        $response->assertSessionHasErrors(['nim']);
    }

    /**
     * Validasi required_if: Dosen wajib mengisi NIDN saat memperbarui profil.
     */
    public function test_dosen_requires_nidn(): void
    {
        $dosen = Pengguna::where('email', 'hendra.gunawan@mncu.ac.id')->firstOrFail();

        $response = $this->actingAs($dosen)->put(route('profile.update'), [
            'role' => 'dosen',
            'nama_lengkap' => 'Prof. Dr. Hendra Gunawan',
            'email' => 'hendra.gunawan@mncu.ac.id',
            'alamat_lengkap' => 'Jl. Panjang No. 5 Jakarta Barat',
            'no_wa' => '081298765432',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1985-08-20',
            'nik' => '3273010101010002',
            'nidn' => '', // Kosong -> harus gagal
        ]);

        $response->assertSessionHasErrors(['nidn']);
    }

    /**
     * Validasi foto profil: menolak format di luar jpg, jpeg, png atau ukuran lebih dari 200 KB.
     */
    public function test_foto_profil_validation_enforces_mimes_and_max_200kb(): void
    {
        $mahasiswa = Pengguna::where('email', 'fauzi.mhs@mncu.ac.id')->firstOrFail();

        // 1. Uji file bukan gambar (.txt / .pdf)
        $invalidMime = UploadedFile::fake()->create('dokumen.pdf', 50, 'application/pdf');

        $response = $this->actingAs($mahasiswa)->put(route('profile.update'), [
            'role' => 'mahasiswa',
            'nama_lengkap' => 'Ahmad Fauzi',
            'email' => 'fauzi.mhs@mncu.ac.id',
            'alamat_lengkap' => 'Jl. Kebon Jeruk No. 10',
            'no_wa' => '081234567890',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2004-05-12',
            'nik' => '3171010101010001',
            'nim' => '202401001',
            'foto_profil' => $invalidMime,
        ]);

        $response->assertSessionHasErrors(['foto_profil']);

        // 2. Uji file gambar lebih dari 200 KB (misal 300 KB)
        $oversizedPhoto = UploadedFile::fake()->create('foto_besar.png', 300, 'image/png');

        $response2 = $this->actingAs($mahasiswa)->put(route('profile.update'), [
            'role' => 'mahasiswa',
            'nama_lengkap' => 'Ahmad Fauzi',
            'email' => 'fauzi.mhs@mncu.ac.id',
            'alamat_lengkap' => 'Jl. Kebon Jeruk No. 10',
            'no_wa' => '081234567890',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2004-05-12',
            'nik' => '3171010101010001',
            'nim' => '202401001',
            'foto_profil' => $oversizedPhoto,
        ]);

        $response2->assertSessionHasErrors(['foto_profil']);
    }

    /**
     * Memperbarui profil berhasil dengan upload foto dan foto lama terhapus dari storage.
     */
    public function test_profile_update_uploads_new_photo_and_deletes_old_photo(): void
    {
        $mahasiswa = Pengguna::where('email', 'fauzi.mhs@mncu.ac.id')->firstOrFail();

        // Unggah foto profil pertama (<= 200 KB)
        $firstPhoto = UploadedFile::fake()->create('avatar1.jpg', 150, 'image/jpeg');

        $response1 = $this->actingAs($mahasiswa)->put(route('profile.update'), [
            'role' => 'mahasiswa',
            'nama_lengkap' => 'Ahmad Fauzi M.Kom',
            'email' => 'fauzi.mhs@mncu.ac.id',
            'alamat_lengkap' => 'Jl. Anggrek Cendrawasih No. 25, Palmerah',
            'no_wa' => '081233445566',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '2003-10-15',
            'nik' => '3578010101010003',
            'nim' => '202401001',
            'foto_profil' => $firstPhoto,
        ]);

        $response1->assertRedirect(route('profile.edit'));
        $response1->assertSessionHas('success');

        $mahasiswa->refresh();
        $firstPath = $mahasiswa->foto_profil;
        $this->assertNotNull($firstPath);
        Storage::disk('public')->assertExists($firstPath);
        $this->assertSame('Ahmad Fauzi M.Kom', $mahasiswa->nama_lengkap);
        $this->assertSame('202401001', $mahasiswa->nim);
        $this->assertSame('3578010101010003', $mahasiswa->nik);

        // Unggah foto profil kedua untuk menggantikan foto pertama
        $secondPhoto = UploadedFile::fake()->create('avatar2.png', 120, 'image/png');

        $response2 = $this->actingAs($mahasiswa)->put(route('profile.update'), [
            'role' => 'mahasiswa',
            'nama_lengkap' => 'Ahmad Fauzi M.Kom',
            'email' => 'fauzi.mhs@mncu.ac.id',
            'alamat_lengkap' => 'Jl. Anggrek Cendrawasih No. 25, Palmerah',
            'no_wa' => '081233445566',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '2003-10-15',
            'nik' => '3578010101010003',
            'nim' => '202401001',
            'foto_profil' => $secondPhoto,
        ]);

        $response2->assertRedirect(route('profile.edit'));
        $mahasiswa->refresh();
        $secondPath = $mahasiswa->foto_profil;

        $this->assertNotEquals($firstPath, $secondPath);
        Storage::disk('public')->assertMissing($firstPath); // Foto lama wajib terhapus
        Storage::disk('public')->assertExists($secondPath); // Foto baru tersimpan
    }
}
