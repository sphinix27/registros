<?php

namespace Tests\Feature;

use App\Delito;
use App\Estado;
use App\Persona;
use App\Registro;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ApiRegistroTest extends TestCase
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
    function can_view_all_registros()
    {
        $registros = factory(Registro::class)->create();
        $delitos = factory(Delito::class, 2)->create();
        $estados = factory(Estado::class, 4)->create();
        $personas = factory(Persona::class, 4)->create();
        $registros->delitos()->attach($delitos);
        $registros->estados()->attach($estados);
        $registros->denunciantes()->attach(1);
        $registros->denunciados()->attach(3);

        $response = $this->json('GET', 'api/registro');

        $response
        	->assertStatus(200)
        	->assertJson([
                'registros' => [
                    'data' => Registro::with(['delitos', 'estados', 'denunciados', 'denunciantes'])->get()->toArray()],
                'delitos' => Delito::select('id', 'nombre')->get()->toArray(),
                'estados' => Estado::select('id', 'nombre')->get()->toArray(),
                'personas' => Persona::select('id', 'nombre')->get()->toArray(),
            ]);
    }

    /** @test */
    function can_create_a_registro()
    {
    	$delitos = factory(Delito::class, 2)->create();
        $estados = factory(Estado::class, 2)->create();
        $denunciantes = factory(Persona::class, 2)->create();
        $denunciados = factory(Persona::class, 2)->create();

        $response = $this->json('POST', 'api/registro', [
        	'caso' => 'FIS1702020',
	        'fecha' => Carbon::now()->toDateString(),
	        'situacion_procesal' => 'LIB',
	        'observaciones' => 'Alguna',
	        'delitos' => $delitos->toArray(),
            'estados' => $estados->pluck('id'),
            'denunciantes' => $denunciantes,
            'denunciados' => $denunciados
        ]);

    	$response
    		// ->assertStatus(200)
    		->assertJson([
    			'created' => true,
    			'registro' => [
	    			'caso' => 'FIS1702020',
			        'fecha' => Carbon::now()->toDateString(),
			        'situacion_procesal' => 'LIB',
			        'observaciones' => 'Alguna',
			        'delitos' => [
                        ['id' => 1],
                        ['id' => 2]
                    ],
                    'estados' => [
                        ['id' => 1],
                        ['id' => 2]
                    ],
                    'denunciantes' => [
                        ['id' => 1],
                        ['id' => 2]
                    ],
                    'denunciados' => [
                        ['id' => 3],
                        ['id' => 4]
                    ]
    			]
    		]);
    }

    /** @test */
    function validation_when_creating_a_registro()
    {
        $delitos = factory(Delito::class, 2)->create();
        $estados = factory(Estado::class, 2)->create();

        $response = $this->json('POST', 'api/registro', [
        	'caso' => '',
	        'fecha' => '',
            'situacion_procesal' => '',
            'observaciones' => '',
            'delitos' => [],
            'estados' => [],
            'denunciantes' => [],
            'denunciados' => [],
        ]);

        $response
    		->assertStatus(422)
    		->assertJson([
    			'created' => false,
    			'errors' => [
    				'caso' => ['The caso field is required.'],
					'fecha' => ['The fecha field is required.'],
                    'situacion_procesal' => ['The situacion procesal field is required.'],
                    'delitos' => ['The delitos field is required.'],
                    'estados' => ['The estados field is required.'],
                    'denunciantes' => ['The denunciantes field is required.'],
                    'denunciados' => ['The denunciados field is required.'],
    			],
    			'registro' => []
    		]);
    }

    /** @test */
    function can_update_a_registro()
    {
        $delitos = factory(Delito::class, 4)->create();
        $estados = factory(Estado::class, 3)->create();
        $denunciantes = factory(Persona::class, 2)->create();
        $denunciados = factory(Persona::class, 2)->create();
        $registro = factory(Registro::class)->create();
        $registro->delitos()->attach(['1', '4']);
        $registro->estados()->attach(['1', '3']);
        $registro->denunciantes()->attach(['1']);
        $registro->denunciados()->attach(['1']);

        $response = $this->json('PUT', 'api/registro/'.$registro->id, [
        	'caso' => 'FIS1702020',
	        'fecha' => Carbon::now()->toDateString(),
            'situacion_procesal' => 'LIB',
            'observaciones' => 'Ninguna',
            'delitos' => [
                ['id' => 2],
                ['id' => '3'],
            ],
            'estados' => [2],
	        'denunciantes' => [['id' => 2]],
	        'denunciados' => [['id' => 2]],
        ]);

        $response
        	->assertStatus(200)
        	->assertJson([
        		'updated' => true,
    			'registro' => [
	    			'caso' => 'FIS1702020',
			        'fecha' => Carbon::now()->toDateString(),
                    'situacion_procesal' => 'LIB',
                    'observaciones' => 'Ninguna',
                    'delitos' => [
                        ['id' => 2],
                        ['id' => 3]
                    ],
                    'estados' => [
                        ['id' => 2]
                    ],
			        'denunciantes' => [
                        ['id' => 2]
                    ],
			        'denunciados' => [
                        ['id' => 2]
                    ],
    			]
        	]);
    }

    /** @test */
    function validation_when_updating_a_registro()
    {
        $delitos = factory(Delito::class, 4)->create();
        $estados = factory(Estado::class, 3)->create();
        $registro = factory(Registro::class)->create();
        $registro->delitos()->attach(['1', '4']);
        $registro->estados()->attach(['1', '2']);

        $response = $this->json('PUT', 'api/registro/'.$registro->id, [
        	'caso' => '',
	        'fecha' => '',
            'situacion_procesal' => '',
            'observaciones' => 'Ninguna',
            'delitos' => [],
            'estados' => [],
	        'denunciantes' => [],
	        'denunciados' => [],
        ]);

        $response
        	// ->assertStatus(422)
        	->assertJson([
        		'updated' => false,
        		'errors' => [
    				'caso' => ['The caso field is required.'],
					'fecha' => ['The fecha field is required.'],
                    'situacion_procesal' => ['The situacion procesal field is required.'],
                    'delitos' => ['The delitos field is required.'],              
                    'estados' => ['The estados field is required.'],
					'denunciantes' => ['The denunciantes field is required.'],
					'denunciados' => ['The denunciados field is required.'],
                ],
    			'registro' => []
        	]);
    }

    /** @test */
    function can_delete_a_registro()
    {
        $registro = factory(Registro::class)->create();

        $response = $this->json('DELETE', 'api/registro/'.$registro->id);

        $response
    		->assertJson([
    			'deleted' => true
    		]);
    }
}
