<?php

namespace Tests\Feature;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseConnectionResilienceTest extends TestCase
{
    use RefreshDatabase;

    public function test_beranda_shows_error_message_when_database_error_session_exists(): void
    {
        $response = $this->withSession(['database_error' => 'Database belum terhubung. Pastikan layanan database (MySQL / XAMPP) sudah dijalankan.'])
            ->get(route('beranda'));

        $response->assertStatus(200);
        $response->assertSee('Koneksi Database Belum Terhubung');
        $response->assertSee('Database belum terhubung. Pastikan layanan database (MySQL / XAMPP) sudah dijalankan.');
    }

    public function test_beranda_shows_error_message_when_db_error_query_is_present(): void
    {
        $response = $this->get('/?db_error=1');

        $response->assertStatus(200);
        $response->assertSee('Koneksi Database Belum Terhubung');
    }

    public function test_database_connection_exception_redirects_to_beranda_with_error(): void
    {
        // Simulate a web route that throws QueryException with code 2002 (connection refused)
        $this->app['router']->get('/test-db-error', function () {
            throw new QueryException(
                'mysql',
                'select * from dummy',
                [],
                new \PDOException('SQLSTATE[HY000] [2002] No connection could be made because the target machine actively refused it', 2002)
            );
        })->middleware('web');

        $response = $this->get('/test-db-error');

        $response->assertRedirect(route('beranda'));
        $response->assertSessionHas('database_error');

        $followResponse = $this->followRedirects($response);
        $followResponse->assertStatus(200);
        $followResponse->assertSee('Koneksi Database Belum Terhubung');
    }

    public function test_database_connection_exception_on_beranda_renders_beranda_directly(): void
    {
        // When already on beranda route and QueryException with code 2002 occurs
        $this->app['router']->get('/beranda-test-fail', function () {
            throw new QueryException(
                'mysql',
                'select * from dummy',
                [],
                new \PDOException('SQLSTATE[HY000] [2002] Connection refused', 2002)
            );
        })->name('beranda');

        $response = $this->get('/beranda-test-fail');

        $response->assertStatus(200);
        $response->assertSee('Koneksi Database Belum Terhubung');
    }
}
