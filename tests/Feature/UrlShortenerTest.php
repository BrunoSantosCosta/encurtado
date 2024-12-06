<?php

// tests/Feature/UrlShortenerTest.php

namespace Tests\Feature;

use App\Models\Url;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlShortenerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste para encurtar URL
     *
     * @return void
     */
    public function test_shorten_url()
    {
        // URL válida para teste
        $url = 'https://www.google2.com';

        // Realiza a requisição POST para encurtar a URL
        $response = $this->postJson('/', [
            'url' => $url,
        ]);

        // Verifica se o código de status é 200 (OK)
        $response->assertStatus(200);

        // Verifica se a resposta JSON contém os campos esperados
        $response->assertJsonStructure([
            'original',
            'short',
            'expires_at',
        ]);

        // Verifica se a URL original e encurtada estão corretas
        $response->assertJson([
            'original' => $url,
            'short' => url($response->json('short')), // Verifica se a URL encurtada está correta
        ]);

        // Verifica se a URL foi salva no banco de dados
        $this->assertDatabaseHas('urls', [
            'original_url' => $url,
        ]);

        // Verifica se o campo short_url existe e tem o formato correto (10 caracteres aleatórios)
        $shortUrl = $response->json('short');
        $this->assertDatabaseHas('urls', [
            'short_url' => basename($shortUrl), // remove o prefixo 'http://'
        ]);

        // Verifica se o short_url gerado tem 10 caracteres (além do protocolo 'http://')
        $this->assertEquals(10, strlen(basename($shortUrl)));
    }


    /**
     * Teste para tentar encurtar uma URL inválida
     *
     * @return void
     */
    public function test_invalid_url()
    {
        // URL inválida para teste
        $invalidUrl = 'invalid-url';

        // Realiza a requisição POST para encurtar a URL
        $response = $this->postJson('/', [
            'url' => $invalidUrl,
        ]);

        // Verifica se o código de status é 422 (Unprocessable Entity)
        $response->assertStatus(422);

        // Verifica se o JSON contém um erro de validação para o campo URL
        $response->assertJsonValidationErrors(['url']);
    }
}
