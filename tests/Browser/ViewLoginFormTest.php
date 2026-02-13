<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ViewLoginFormTest extends DuskTestCase
{
    /**
     * Test that the login form is displayed correctly
     */
    public function testLoginFormIsDisplayed(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->assertSee('Sign in to start your session')
                    ->assertVisible('#loginEmail')
                    ->assertVisible('#loginPassword');
        });
    }

    /**
     * Test that the login page has the correct title
     */
    public function testLoginPageTitle(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->assertTitleContains('Login Page v2');
        });
    }

    /**
     * Test that sign in button is visible
     */
    public function testSignInButtonIsVisible(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->assertSee('Sign In');
        });
    }
}
