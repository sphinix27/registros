<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DelitoTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function ($browser) {
            $browser->visit('/')
                    ->clickLink('Delitos')
                    ->assertSee('Delitos')
                    ->click('#add')
                    ->with('.table', function ($table) {
                        $table->assertSee('Artículo')
                              ->assertSee('Inciso')
                              ->assertSee('Detalle')
                              ->assertSee('Enlaces')
                              ->type('articulo', '12')
                              ->type('inciso', 'bis')
                              ->type('nombre', 'Asesinato')
                              ->press('#store')
                              ->assertSee('12')
                              ->assertSee('bis')
                              ->assertSee('Asesinato');
                    })
                    ->assertSee('Registro agregado correctamente');
        });
    }
}
