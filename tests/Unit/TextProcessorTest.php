<?php

namespace Tests\Unit;

use App\Http\Controllers\TextProcessorController;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TextProcessorTest extends TestCase
{
    public function test_analyze_text_con_datos_validos(): void
    {
        $controller = new TextProcessorController;

        $text = 'Hola mundo. Este es un texto de prueba!';

        $result = $controller->analyzeText($text);

        $this->assertEquals($text, $result['original_text']);
        $this->assertEquals(39, $result['character_count']);
        $this->assertEquals(8, $result['word_count']);
        $this->assertEquals(2, $result['sentence_count']);
        $this->assertEquals('HOLA MUNDO. ESTE ES UN TEXTO DE PRUEBA!', $result['uppercase_text']);
        $this->assertEquals('hola mundo. este es un texto de prueba!', $result['lowercase_text']);
    }

    public function test_analyze_text_falla_con_texto_vacio(): void
    {
        $controller = new TextProcessorController;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('El texto no puede estar vacío.');

        $controller->analyzeText('   ');
    }

    public function test_analyze_text_falla_con_longitud_minima_invalida(): void
    {
        $controller = new TextProcessorController;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('La longitud mínima debe ser mayor a 0.');

        $controller->analyzeText('Hola', ['min_length' => 0]);
    }

    public function test_analyze_text_falla_con_texto_fuera_de_rango(): void
    {
        $controller = new TextProcessorController;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('El texto debe tener entre 10 y 20 caracteres.');

        $controller->analyzeText('Hola', ['min_length' => 10, 'max_length' => 20]);
    }
}
