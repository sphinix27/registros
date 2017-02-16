<?php

namespace Tests\Unit;

use App\Estado;
use App\Registro;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RegistroTest extends TestCase
{
	use DatabaseMigrations;

    /** @test */
    function a_registro_can_attach_estados_from_array_or_object_array()
    {
        $registro = factory(Registro::class)->create();
        $estados = factory(Estado::class, 5)->create();
        $estadosIntegerArray = [1, 2, 3];
        $estadosObjectArray = [
        	[
        		'id' => 4,
        	],
        	[
        		'id' => 5,
        	]
        ];

        $estados = $registro->attachEstados($estadosIntegerArray);
        $estados = $registro->attachEstados($estadosObjectArray);

        $response = $this->json('GET', '/registro');

        $this->assertEquals(5, $registro->estados()->count());
        $response->assertJson(['estados' => [['id' => 1], ['id' => 2], ['id' => 3], ['id' => 4], ['id' => 5]]]);
    }
}
