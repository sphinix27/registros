<?php

namespace Tests\Feature;

use App\Estado;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ApiEstadoTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function can_view_all_estados()
    {
        $estados = factory(Estado::class, 4)->create();

        $response = $this->json('GET', '/estado');

        $response
        	->assertStatus(200)
        	->assertJson($estados->toArray());
    }

    /** @test */
    function can_create_a_estado()
    {
    	$response = $this->json('POST', '/estado', [
        	'nombre' => 'Cautelar'
        ]);

    	$response
    		// ->assertStatus(200)
    		->assertJson([
    			'created' => true,
    			'estado' => [
		        	'nombre' => 'Cautelar'
    			]
    		]);
    }

    /** @test */
    function validation_when_creating_a_estado()
    {
        $response = $this->json('POST', '/estado', [
        	'nombre' => ''
        ]);

        $response
    		->assertStatus(422)
    		->assertJson([
    			'created' => false,
    			'errors' => [
    				'nombre' => ['The nombre field is required.']    				
    			],
    			'estado' => [
		        	'nombre' => ''
        		]
    		]);
    }

    /** @test */
    function can_update_a_estado()
    {
        $estado = factory(Estado::class)->create();
        $updatedEstado = [
        	'nombre' => 'Cautelar'
        ];

        $response = $this->json('PUT', 'estado/'.$estado->id, $updatedEstado);

        $response
    		->assertStatus(200)
    		->assertJson([
    			'updated' => true,
    			'estado' => $updatedEstado
    		]);
    }

    /** @test */
    function validation_when_updating_a_estado()
    {
        $estado = factory(Estado::class)->create();

        $response = $this->json('PUT', 'estado/'.$estado->id, [
        	'nombre' => ''
        ]);

        $response
    		->assertStatus(422)
    		->assertJson([
    			'updated' => false,
    			'errors' => [
    				'nombre' => ['The nombre field is required.']    				
    			],
    			'estado' => $estado->toArray()
    		]);
    }

    /** @test */
    function can_delete_a_estado()
    {
        $estado = factory(Estado::class)->create();

        $response = $this->json('DELETE', 'estado/'.$estado->id);

        $response
    		->assertJson([
    			'deleted' => true
    		]);
    }
}
