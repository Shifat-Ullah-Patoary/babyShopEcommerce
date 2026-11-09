<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_log_in_from_the_main_login_form(): void
    {
        $response = $this->post('/login', [
            'email' => 'test@gmail.com',
            'password' => '12345678',
        ]);

        $response->assertRedirect('/admin');
        $this->assertTrue(session('is_admin'));
        $this->get('/')->assertSee('Dashboard')->assertSee('Logout')->assertDontSee('>Admin</a>');
    }

    public function test_regular_user_can_log_in_but_cannot_access_the_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/')->assertSee('Logout')->assertDontSee('Dashboard')->assertDontSee('>Admin</a>');
    }

    public function test_logout_clears_an_admin_session(): void
    {
        $this->withSession(['is_admin' => true])->post('/logout')->assertRedirect('/');

        $this->assertFalse(session()->has('is_admin'));
        $this->get('/')->assertSee('Login')->assertDontSee('Logout');
    }

    public function test_admin_can_open_each_order_status_page(): void
    {
        foreach (['new', 'shipped', 'confirmed', 'cancelled'] as $status) {
            Order::create([
                'customer_name' => 'Test Customer',
                'address' => 'Test address',
                'mobile' => '0123456789',
                'total' => 10,
                'status' => $status,
            ]);
        }

        foreach (['new', 'shipping', 'confirmed', 'cancelled'] as $page) {
            $this->withSession(['is_admin' => true])
                ->get('/admin/orders/'.$page)
                ->assertOk();
        }
    }
}
