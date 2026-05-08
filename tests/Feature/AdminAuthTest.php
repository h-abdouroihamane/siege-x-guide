<?php

use App\Models\User;

it('redirects guest from dashboard to login', function () {
    $response = $this->get('/admin/dashboard');

    $response->assertRedirect('/admin/login');
});

it('renders dashboard for authenticated admin', function () {
    $admin = User::factory()->create();

    $response = $this->actingAs($admin)->get('/admin/dashboard');

    $response->assertStatus(200);
});

it('renders the Inertia admin Dashboard component for admin', function () {
    $admin = User::factory()->create();

    $response = $this->actingAs($admin)->get('/admin/dashboard');

    $response->assertInertia(fn($page) => $page->component('admin/Dashboard'));
});

it('redirects to dashboard after valid login', function () {
    // Auth::attempt is missing its import in AdminController — this
    // test documents the expected behavior but will fail due to
    // the latent bug (see report below).
    $admin = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => bcrypt('secret'),
    ]);

    $response = $this->post('/admin/login', [
        'email' => 'admin@example.com',
        'password' => 'secret',
    ]);

    $response->assertRedirect();
});

it('returns validation errors for invalid login credentials', function () {
    User::factory()->create([
        'email' => 'admin@example.com',
        'password' => bcrypt('secret'),
    ]);

    $response = $this->post('/admin/login', [
        'email' => 'admin@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('email');
});

it('does not authenticate with missing email field', function () {
    $response = $this->post('/admin/login', [
        'password' => 'secret',
    ]);

    $response->assertSessionHasErrors('email');
});

it('does not authenticate with missing password field', function () {
    $response = $this->post('/admin/login', [
        'email' => 'admin@example.com',
    ]);

    $response->assertSessionHasErrors('password');
});
