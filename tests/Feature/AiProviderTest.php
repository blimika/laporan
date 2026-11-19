<?php

namespace Tests\Feature;

use App\Models\Satker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiProviderTest extends TestCase
{
    use RefreshDatabase;

    public function test_ai_provider_edit_screen_can_be_rendered_by_admin(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS NTB',
            'kode' => '5200',
            'ai_provider' => 'gemini',
            'gemini_api_key' => 'AIzaSyTestGeminiKey123',
            'deepseek_api_key' => 'sk-test-deepseek-key',
            'is_centralized_api' => true,
        ]);
        $admin = User::create([
            'name' => 'Admin Satker',
            'username' => 'adminsatker',
            'email' => 'adminsatker@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.ai_provider.edit'));

        $response->assertStatus(200);
        $response->assertSee('Setting Provider AI &amp; API Key', false);
        $response->assertSee('Google Gemini');
        $response->assertSee('DeepSeek');
        $response->assertSee('Test Koneksi Gemini');
        $response->assertSee('Test Koneksi DeepSeek');
        $response->assertSee('Gunakan API Key Terpusat (Global Satker)');
        $response->assertSee('5200');
        $response->assertSee('BPS NTB');
    }

    public function test_ai_provider_update_successfully_saves_settings(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS Lombok Barat',
            'kode' => '5201',
            'ai_provider' => 'gemini',
            'is_centralized_api' => false,
        ]);
        $admin = User::create([
            'name' => 'Admin Lobar',
            'username' => 'adminlobar',
            'email' => 'adminlobar@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.ai_provider.update'), [
            'ai_provider' => 'deepseek',
            'gemini_api_key' => 'AIzaSyNewGeminiUpdated',
            'deepseek_api_key' => 'sk-new-deepseek-updated',
            'is_centralized_api' => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $satker->refresh();
        $this->assertEquals('deepseek', $satker->ai_provider);
        $this->assertEquals('AIzaSyNewGeminiUpdated', $satker->gemini_api_key);
        $this->assertEquals('sk-new-deepseek-updated', $satker->deepseek_api_key);
        $this->assertTrue((bool) $satker->is_centralized_api);
    }

    public function test_ai_provider_test_connection_returns_error_if_key_is_empty(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS Empty Key',
            'kode' => '5299',
            'ai_provider' => 'gemini',
        ]);
        $admin = User::create([
            'name' => 'Admin Empty',
            'username' => 'adminempty',
            'email' => 'adminempty@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.ai_provider.test'), [
            'provider' => 'gemini',
            'api_key' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
        $response->assertJsonFragment([
            'message' => 'API Key belum diisi. Masukkan API Key terlebih dahulu pada kolom yang disediakan untuk menguji koneksi.',
        ]);
    }

    public function test_ai_provider_test_connection_gemini_success(): void
    {
        Http::fake([
            'generativelanguage.googleapis.com/v1beta/models?*' => Http::response([
                'models' => [
                    [
                        'name' => 'models/gemini-3.1-flash-lite',
                        'supportedGenerationMethods' => ['generateContent'],
                    ],
                ],
            ], 200),
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'OK'],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $satker = Satker::create([
            'nama' => 'BPS Test AI',
            'kode' => '5205',
        ]);
        $admin = User::create([
            'name' => 'Admin AI Test',
            'username' => 'adminaitest',
            'email' => 'adminaitest@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.ai_provider.test'), [
            'provider' => 'gemini',
            'api_key' => 'AIzaSyValidDummyKey123',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'provider' => 'Google Gemini',
            'reply' => 'OK',
        ]);
    }

    public function test_ai_provider_test_connection_deepseek_success(): void
    {
        Http::fake([
            'api.deepseek.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'OK',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $satker = Satker::create([
            'nama' => 'BPS Test DeepSeek',
            'kode' => '5206',
        ]);
        $admin = User::create([
            'name' => 'Admin Deepseek Test',
            'username' => 'admindeepseek',
            'email' => 'admindeepseek@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.ai_provider.test'), [
            'provider' => 'deepseek',
            'api_key' => 'sk-dummy-valid-key-456',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'provider' => 'DeepSeek',
            'model' => 'deepseek-chat',
            'reply' => 'OK',
        ]);
    }

    public function test_ai_provider_test_connection_gemini_handles_invalid_key_error(): void
    {
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'error' => [
                    'code' => 400,
                    'message' => 'API_KEY_INVALID: The provided API key is invalid.',
                    'status' => 'INVALID_ARGUMENT',
                ],
            ], 400),
        ]);

        $satker = Satker::create([
            'nama' => 'BPS Test Invalid',
            'kode' => '5207',
        ]);
        $admin = User::create([
            'name' => 'Admin Invalid Key',
            'username' => 'admininvalid',
            'email' => 'admininvalid@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.ai_provider.test'), [
            'provider' => 'gemini',
            'api_key' => 'AIzaSyWrongKey999',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'provider' => 'Google Gemini',
            'status_code' => 400,
        ]);
        $response->assertSee('API Key Google Gemini TIDAK VALID');
    }

    public function test_ai_provider_denied_for_regular_user(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS NTB',
            'kode' => '5200',
        ]);
        $user = User::create([
            'name' => 'Regular User 2',
            'username' => 'reguser2',
            'email' => 'reguser2@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'satker_id' => $satker->id,
        ]);

        $response = $this->actingAs($user)->get(route('admin.ai_provider.edit'));
        $response->assertStatus(403);

        $responseTest = $this->actingAs($user)->postJson(route('admin.ai_provider.test'), [
            'provider' => 'gemini',
            'api_key' => 'any-key',
        ]);
        $responseTest->assertStatus(403);
    }
}
