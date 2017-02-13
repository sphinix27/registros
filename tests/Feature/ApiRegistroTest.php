<?php

namespace Tests\Feature;

use App\Delito;
use App\Registro;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ApiRegistroTest extends TestCase
{
	use DatabaseMigrations;

    /** @test */
    function can_view_all_registros()
    {
        $registros = factory(Registro::class)->create();
        $delitos = factory(Delito::class, 2)->create();
        $registros->delitos()->attach($delitos);

        $response = $this->json('GET', '/registro');

        $response
        	->assertStatus(200)
        	->assertJson([
                'registros' => Registro::with('delitos')->get()->toArray()
            ]);
    }

    /** @test */
    function can_create_a_registro()
    {
    	$delitos = factory(Delito::class, 2)->create();

        $response = $this->json('POST', '/registro', [
        	'caso' => 'FIS1702020',
	        'fecha' => Carbon::now()->toDateString(),
	        'denunciante' => 'John Doe',
	        'denunciado' => 'Jane Doe',
	        'estado' => 'CAUTELAR',
	        'situacion_procesal' => 'APR',
	        'observaciones' => '',
	        'delitos' => $delitos->pluck('id')
        ]);

    	$response
    		->assertStatus(200)
    		->assertJson([
    			'created' => true,
    			'registro' => [
	    			'caso' => 'FIS1702020',
			        'fecha' => Carbon::now()->toDateString(),
			        'denunciante' => 'John Doe',
			        'denunciado' => 'Jane Doe',
			        'estado' => 'CAUTELAR',
			        'situacion_procesal' => 'APR',
			        'observaciones' => '',
			        'delitos' => []
    			]
    		]);
    }

    /** @test */
    function validation_when_creating_a_registro()
    {
        $delitos = factory(Delito::class, 2)->create();

        $response = $this->json('POST', '/registro', [
        	'caso' => '',
	        'fecha' => '',
	        'denunciante' => '',
	        'denunciado' => '',
	        'estado' => '',
	        'situacion_procesal' => '',
	        'observaciones' => '',
	        'delitos' => []
        ]);

        $response
    		->assertStatus(422)
    		->assertJson([
    			'created' => false,
    			'errors' => [
    				'caso' => ['The caso field is required.'],
					'fecha' => ['The fecha field is required.'],
					'denunciante' => ['The denunciante field is required.'],
					'denunciado' => ['The denunciado field is required.'],
					'estado' => ['The estado field is required.'],
					'situacion_procesal' => ['The situacion procesal field is required.'],
					'delitos' => [] 				
    			],
    			'registro' => []
    		]);
    }

    /** @test */
    function can_update_a_registro()
    {
        $delitos = factory(Delito::class, 4)->create();
        $registro = factory(Registro::class)->create();
        $registro->delitos()->attach(['1', '4']);

        $response = $this->json('PUT', 'registro/'.$registro->id, [
        	'caso' => 'FIS1702020',
	        'fecha' => Carbon::now()->toDateString(),
	        'denunciante' => 'John Doe',
	        'denunciado' => 'Jane Doe',
	        'estado' => 'CAUTELAR',
	        'situacion_procesal' => 'APR',
	        'observaciones' => 'Ninguna',
	        'delitos' => [
                ['id' => 2],
                ['id' => '3']
            ]
        ]);

        $response
        	->assertStatus(200)
        	->assertJson([
        		'updated' => true,
    			'registro' => [
	    			'caso' => 'FIS1702020',
			        'fecha' => Carbon::now()->toDateString(),
			        'denunciante' => 'John Doe',
			        'denunciado' => 'Jane Doe',
			        'estado' => 'CAUTELAR',
			        'situacion_procesal' => 'APR',
			        'observaciones' => 'Ninguna',
			        'delitos' => []
    			]
        	]);
    }

    /** @test */
    function validation_when_updating_a_registro()
    {
        $delitos = factory(Delito::class, 4)->create();
        $registro = factory(Registro::class)->create();
        $registro->delitos()->attach(['1', '4']);

        $response = $this->json('PUT', 'registro/'.$registro->id, [
        	'caso' => '',
	        'fecha' => '',
	        'denunciante' => '',
	        'denunciado' => '',
	        'estado' => '',
	        'situacion_procesal' => '',
	        'observaciones' => 'Ninguna',
	        'delitos' => []
        ]);

        $response
        	// ->assertStatus(422)
        	->assertJson([
        		'updated' => false,
        		'errors' => [
    				'caso' => ['The caso field is required.'],
					'fecha' => ['The fecha field is required.'],
					'denunciante' => ['The denunciante field is required.'],
					'denunciado' => ['The denunciado field is required.'],
					'estado' => ['The estado field is required.'],
					'situacion_procesal' => ['The situacion procesal field is required.'],
					'delitos' => [] 				
    			],
    			'registro' => []
        	]);
    }

    /** @test */
    function can_delete_a_registro()
    {
        $registro = factory(Registro::class)->create();

        $response = $this->json('DELETE', 'registro/'.$registro->id);

        $response
    		->assertJson([
    			'deleted' => true
    		]);
    }
}
