<?php

namespace Tests\Feature;

use App\Delito;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ApiDelitoTest extends TestCase
{
	use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();
        Passport::actingAs(
            factory(User::class)->create()
        );
    }

    /** @test */
    function can_view_all_delitos()
    {
        $delitos = factory(Delito::class, 4)->create();

        $response = $this->json('GET', 'api/delito');

        $response
        	->assertStatus(200)
        	->assertJson([
                'data' => $delitos->toArray()
            ]);
    }

    /** @test */
    function can_create_a_delito()
    {
    	$response = $this->json('POST', 'api/delito', [
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
        $response = $this->json('POST', 'api/delito', [
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

        $response = $this->json('PUT', 'api/delito/'.$delito->id, $updatedDelito);

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

        $response = $this->json('PUT', 'api/delito/'.$delito->id, [
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

        $response = $this->json('DELETE', 'api/delito/'.$delito->id);

        $response
    		->assertJson([
    			'deleted' => true
    		]);
    }
}
