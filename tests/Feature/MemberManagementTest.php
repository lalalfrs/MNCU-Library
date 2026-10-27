<?php

namespace Tests\Feature;

use App\Models\EksemplarBuku;
use App\Models\Pengguna;
use App\Services\PeminjamanService;
use Database\Seeders\PerpustakaanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberManagementTest extends TestCase
{
    use RefreshDatabase;

    protected Pengguna $admin;

    protected Pengguna $nonMemberDosen;

    protected Pengguna $memberWithLoan;

    protected Pengguna $memberWithoutLoan;

    protected EksemplarBuku $availableExemplar;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PerpustakaanSeeder::class);

        $this->admin = Pengguna::where('email', 'arifin@mncu.ac.id')->firstOrFail();
        $this->nonMemberDosen = Pengguna::where('email', 'hendra.gunawan@mncu.ac.id')->firstOrFail(); // MNCU00005
        $this->memberWithLoan = Pengguna::where('id_pengguna', 'MNCU00004')->firstOrFail(); // Fauzi has PJ00001
        $this->memberWithoutLoan = Pengguna::where('id_pengguna', 'MNCU00006')->firstOrFail(); // Nabila has returned loan
        $this->availableExemplar = EksemplarBuku::where('status', 'Tersedia')->firstOrFail();
    }

    /**
     * Pada dasarnya user baru yang mendaftar belum berstatus sebagai member.
     */
    public function test_default_newly_registered_users_are_not_members(): void
    {
        $response = $this->post(route('register.post'), [
            'nama_lengkap' => 'Mahasiswa Baru MNCU',
            'email' => 'mhs.baru@mncu.ac.id',
            'nomor_identitas' => '202699001',
            'kata_sandi' => 'password123',
            'kata_sandi_confirmation' => 'password123',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('beranda'));

        $newUser = Pengguna::where('email', 'mhs.baru@mncu.ac.id')->firstOrFail();
        $this->assertFalse($newUser->isMember());
        $this->assertNull($newUser->nomor_anggota);
        $this->assertNull($newUser->member_sejak);
    }

    /**
     * Super Admin dapat melihat daftar seluruh data member dan memfilter status keanggotaan.
     */
    public function test_super_admin_can_view_member_and_non_member_in_user_management(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen User & Keanggotaan Member');
        $response->assertSee('Tambah Data Member');
        $response->assertSee('Member Resmi Perpustakaan');
        $response->assertSee('Calon Member (Belum Dipromote)');

        // Filter Hanya Member
        $memberResponse = $this->actingAs($this->admin)->get(route('admin.users.index', ['status_member' => 'member']));
        $memberResponse->assertStatus(200);
        $memberResponse->assertSee($this->memberWithLoan->nama_lengkap);

        // Filter Non-Member
        $nonMemberResponse = $this->actingAs($this->admin)->get(route('admin.users.index', ['status_member' => 'non_member']));
        $nonMemberResponse->assertStatus(200);
        $nonMemberResponse->assertSee($this->nonMemberDosen->nama_lengkap);
    }

    /**
     * Super Admin dapat mem-promote user non-member menjadi member (sesuai flowchart: Menambahkan data member).
     */
    public function test_super_admin_can_promote_non_member_to_member(): void
    {
        $this->assertFalse($this->nonMemberDosen->isMember());

        $response = $this->actingAs($this->admin)->post(route('admin.users.promote-member', $this->nonMemberDosen->id_pengguna), [
            'nomor_anggota' => 'MBR-2026-99999',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->nonMemberDosen->refresh();
        $this->assertTrue($this->nonMemberDosen->isMember());
        $this->assertEquals('MBR-2026-99999', $this->nonMemberDosen->nomor_anggota);
        $this->assertNotNull($this->nonMemberDosen->member_sejak);
    }

    /**
     * Default nomor anggota otomatis disamakan dengan unique ID pengguna (id_pengguna).
     */
    public function test_promote_without_nomor_anggota_defaults_to_user_unique_id(): void
    {
        $this->assertFalse($this->nonMemberDosen->isMember());

        $response = $this->actingAs($this->admin)->post(route('admin.users.promote-member', $this->nonMemberDosen->id_pengguna), [
            'nomor_anggota' => '',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->nonMemberDosen->refresh();
        $this->assertTrue($this->nonMemberDosen->isMember());
        $this->assertEquals($this->nonMemberDosen->id_pengguna, $this->nonMemberDosen->nomor_anggota);
    }

    /**
     * Sesuai flowchart: Hapus data member -> Validasi apakah member masih ada buku yang dipinjam.
     * Jika Ya (masih ada pinjaman aktif) -> Tolak / tidak dapat dihapus.
     */
    public function test_super_admin_cannot_demote_member_if_they_have_active_unreturned_loans(): void
    {
        $this->assertTrue($this->memberWithLoan->isMember());
        $this->assertTrue($this->memberWithLoan->memilikiTanggunganPinjaman());

        $response = $this->actingAs($this->admin)->post(route('admin.users.demote-member', $this->memberWithLoan->id_pengguna));

        $response->assertSessionHasErrors('error');

        $this->memberWithLoan->refresh();
        $this->assertTrue($this->memberWithLoan->isMember(), 'Status member tidak boleh dicabut jika masih ada buku pinjaman aktif');
    }

    /**
     * Sesuai flowchart: Hapus data member -> Validasi apakah member masih ada buku yang dipinjam.
     * Jika Tidak (tidak ada tanggungan) -> Data status member berhasil dihapus.
     */
    public function test_super_admin_can_demote_member_if_they_have_no_active_loans(): void
    {
        $this->assertTrue($this->memberWithoutLoan->isMember());
        $this->assertFalse($this->memberWithoutLoan->memilikiTanggunganPinjaman());

        $response = $this->actingAs($this->admin)->post(route('admin.users.demote-member', $this->memberWithoutLoan->id_pengguna));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->memberWithoutLoan->refresh();
        $this->assertFalse($this->memberWithoutLoan->isMember());
        $this->assertNull($this->memberWithoutLoan->nomor_anggota);
        $this->assertNull($this->memberWithoutLoan->member_sejak);
    }

    /**
     * User yang belum menjadi member TIDAK BISA meminjam buku.
     */
    public function test_non_member_cannot_borrow_book_from_library(): void
    {
        $service = app(PeminjamanService::class);
        $eligibility = $service->cekEligibilitas($this->nonMemberDosen->id_pengguna);

        $this->assertFalse($eligibility['eligible']);
        $this->assertStringContainsString('member', strtolower($eligibility['message']));

        // Coba catat transaksi peminjaman untuk non-member
        $response = $this->actingAs($this->admin)->post(route('admin.peminjaman.store'), [
            'id_pengguna' => $this->nonMemberDosen->id_pengguna,
            'id_eksemplar' => $this->availableExemplar->id_eksemplar,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(14)->toDateString(),
        ]);

        $response->assertSessionHasErrors('id_pengguna');
    }

    /**
     * User yang sudah di-promote menjadi member dapat meminjam buku jika tidak memiliki tanggungan pinjaman.
     */
    public function test_promoted_member_can_borrow_book(): void
    {
        // Promote non-member dosen
        $this->nonMemberDosen->promoteToMember();
        $this->assertTrue($this->nonMemberDosen->isMember());

        $service = app(PeminjamanService::class);
        $eligibility = $service->cekEligibilitas($this->nonMemberDosen->id_pengguna);
        $this->assertTrue($eligibility['eligible']);

        // Catat peminjaman buku
        $response = $this->actingAs($this->admin)->post(route('admin.peminjaman.store'), [
            'id_pengguna' => $this->nonMemberDosen->id_pengguna,
            'id_eksemplar' => $this->availableExemplar->id_eksemplar,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(14)->toDateString(),
        ]);

        $response->assertRedirect(route('admin.peminjaman.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('peminjaman_buku', [
            'id_pengguna' => $this->nonMemberDosen->id_pengguna,
            'id_eksemplar' => $this->availableExemplar->id_eksemplar,
            'status' => 'Dipinjam',
        ]);
    }

    /**
     * Mahasiswa dan dosen tidak bisa request menjadi member dari website,
     * melainkan ada informasi jelas untuk menemui pustakawan secara langsung.
     */
    public function test_profile_displays_in_person_membership_requirement_without_online_request_form(): void
    {
        $response = $this->actingAs($this->nonMemberDosen)->get(route('profile.show'));

        $response->assertStatus(200);
        $response->assertSee('Belum Menjadi Member');
        $response->assertSee('Prosedur Aktivasi Member');
        $response->assertSee('Permohonan menjadi member tidak dapat dilakukan melalui website');
        $response->assertSee('Meja Sirkulasi Perpustakaan MNC University Lantai 2');

        // Pastikan tidak ada tombol submit atau formulir pengajuan member online
        $response->assertDontSee('Ajukan Menjadi Member');
        $response->assertDontSee('Daftar Member Online');
    }
}
