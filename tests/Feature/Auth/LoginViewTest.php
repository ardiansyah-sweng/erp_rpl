<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class LoginViewTest extends TestCase
{
    public function test_login_page_shows_form()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('login');
        $response->assertSee('Sign in to start your session');
        // periksa label/teks yang terlihat pengguna (lebih stabil)
        $response->assertSee('Email');
        $response->assertSee('Password');
        $response->assertSee('Remember Me');
        $response->assertSee('Sign In');
        // link teks pada UI
        $response->assertSee('I forgot my password');
        $response->assertSee('Register a new membership');
        // gambar/asset kanan
        $response->assertSee('assets/dist/assets/img/rpl.jpg');
    }
}
