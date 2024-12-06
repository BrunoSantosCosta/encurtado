<?php

namespace Tests\Feature;

use App\Factories\UrlFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Url;
use Illuminate\Support\Facades\Artisan;

class UrlControllerTest extends TestCase
{
    use RefreshDatabase;


    /**
     * Testa a geração de URL encurtada.
     *
     * @return void
     */
    public function test_shorten_url()
    {

        // URL válida para teste
        $url = 'https://www.google.com';

        // Realiza a requisição POST para encurtar a URL
        $response = $this->postJson('/', [
            'url' => $url,
        ]);


        // Verifica se o código de status é 200 (OK)
        $response->assertStatus(200);

        // Verifica se o JSON de resposta contém os campos esperados
        $response->assertJsonStructure([
            'original',
            'short',
            'expires_at',
        ]);

        // Verifica se a URL original e encurtada estão corretas
        $response->assertJson([
            'original' => $url,
            'short' => url($response->json('short')), // Verifica se a URL encurtada é gerada corretamente
        ]);

        // Verifica se a URL foi salva no banco de dados
        $this->assertDatabaseHas('urls', [
            'original_url' => $url,
        ]);
    }

    /**
     * Testa a criação de URL com um formato inválido.
     *
     * @return void
     */
    public function test_shorten_url_invalid()
    {
        // URL inválida para teste
        $invalidUrl = 'invalid-url';

        // Realiza a requisição POST para encurtar a URL
        $response = $this->postJson('/', [
            'url' => $invalidUrl,
        ]);

        // Verifica se o código de status é 422 (Unprocessable Entity)
        $response->assertStatus(422);

        // Verifica se o erro de validação foi retornado
        $response->assertJsonValidationErrors('url');
    }

    /**
     * Testa o redirecionamento para a URL original.
     *
     * @return void
     */
    public function test_redirect_url()
    {
        // Cria uma URL encurtada com uma data de expiração no futuro
        $url = UrlFactory::create('https://www.example.com');

        // Realiza a requisição GET para o redirecionamento
        $response = $this->get($url->short_url);

        // Verifica se o redirecionamento foi feito para a URL original
        $response->assertRedirect('https://www.example.com');
    }

    /**
     * Testa o redirecionamento para uma URL expirada.
     *
     * @return void
     */
    public function test_redirect_url_expired()
    {
        // Cria uma URL encurtada com uma data de expiração no passado
        $url = UrlFactory::create('https://www.example.com');

        // Realiza a requisição GET para o redirecionamento
        $response = $this->get('/expired123');

        // Verifica se o erro 404 é retornado quando a URL está expirada
        $response->assertStatus(404);
        $response->assertJson(['error' => 'Not found or expired']);
    }

    /**
     * Testa o redirecionamento para uma URL inexistente.
     *
     * @return void
     */
    public function test_redirect_url_not_found()
    {
        // Realiza a requisição GET para uma URL inexistente
        $response = $this->get('/nonexistent123');

        // Verifica se o erro 404 é retornado
        $response->assertStatus(404);
        $response->assertJson(['error' => 'Not found or expired']);
    }
}
