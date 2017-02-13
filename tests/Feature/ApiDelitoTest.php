<?php

namespace Tests\Feature;

use App\Delito;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ApiDelitoTest extends TestCase
{
	use DatabaseMigrations;

    /** @test */
    function can_view_all_delitos()
    {
        $delitos = factory(Delito::class, 4)->create();

        $response = $this->json('GET', '/delito');

        $response
        	->assertStatus(200)
        	->assertJson($delitos->toArray());
    }

    /** @test */
    function can_create_a_delito()
    {
    	$response = $this->json('POST', '/delito', [
        	'articulo' => 192,
        	'inciso' => 'bis',
        	'nombre' => 'Homicidio'
        ]);

    	$response
    		->assertStatus(200)
    		->assertJson([
    			'created' => true,
    			'delito' => [
    				'articulo' => 192,
		        	'inciso' => 'bis',
		        	'nombre' => 'Homicidio'
    			]
    		]);
    }

    /** @test */
    function validation_when_creating_a_delito()
    {
        $response = $this->json('POST', '/delito', [
        	'articulo' => '',
        	'inciso' => 'bis',
        	'nombre' => ''
        ]);

        $response
    		->assertStatus(422)
    		->assertJson([
    			'created' => false,
    			'errors' => [
    				'articulo' => ['The articulo field is required.'],
    				'nombre' => ['The nombre field is required.']    				
    			],
    			'delito' => [
	    			'articulo' => '',
		        	'inciso' => 'bis',
		        	'nombre' => ''
        		]
    		]);
    }

    /** @test */
    function can_update_a_delito()
    {
        $delito = factory(Delito::class)->create();
        $updatedDelito = [
        	'articulo' => 192,
        	'inciso' => 'bis',
        	'nombre' => 'Homicidio'
        ];

        $response = $this->json('PUT', 'delito/'.$delito->id, $updatedDelito);

        $response
    		->assertStatus(200)
    		->assertJson([
    			'updated' => true,
    			'delito' => $updatedDelito
    		]);
    }

    /** @test */
    function validation_when_updating_a_delito()
    {
        $delito = factory(Delito::class)->create();

        $response = $this->json('PUT', 'delito/'.$delito->id, [
        	'articulo' => '',
        	'inciso' => 'bis',
        	'nombre' => ''
        ]);

        $response
    		->assertStatus(422)
    		->assertJson([
    			'updated' => false,
    			'errors' => [
    				'articulo' => ['The articulo field is required.'],
    				'nombre' => ['The nombre field is required.']    				
    			],
    			'delito' => $delito->toArray()
    		]);
    }

    /** @test */
    function can_delete_a_delito()
    {
        $delito = factory(Delito::class)->create();

        $response = $this->json('DELETE', 'delito/'.$delito->id);

        $response
    		->assertJson([
    			'deleted' => true
    		]);
    }
}
