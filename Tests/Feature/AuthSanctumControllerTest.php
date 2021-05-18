<?php
namespace Modules\Authsanctum\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Authsanctum\Tests\TestCase;
use Modules\Users\Entities\User;

class AuthSanctumControllerTest extends TestCase
{
	use RefreshDatabase;
	public function test_register()
	{
		$this->withExceptionHandling();
		$response = $this->json('POST', route('api.sanctum.register'), [
			'email' => 'test@test.dev',
			'password' => '123456',
			'password_confirmation' => '123456',
			'name' => 'John Doe'
		]);

		$response->assertJsonStructure(['token', 'user']);
	}

	public function test_get_auth_user()
	{
		User::factory()->make();
		$user = User::factory()->create();
		$this->actingAs($user);
		$response = $this->json('get', 'api/me')->assertStatus(200);
		$this->assertEquals($response->decodeResponseJson()['email'], $user->email);
	}

	public function test_login()
	{
		$this->withExceptionHandling();
		$user = User::factory()->create([
			'password' => '1234'
		]);
		$this->response = $this->json('post', route('api.sanctum.login', [
			'email' => $user->email,
			'password' => '1234'
		]))->assertStatus(200);
	}

	public function test_logout()
	{
		$this->withExceptionHandling();
		$user = User::factory()->create([
			'password' => '1234'
		]);
		$this->actingAs($user);
		$response = $this->json('post', route('api.sanctum.logout'));
		$this->assertTrue($response->decodeResponseJson()->json == '"Tokens deleted"');
	}
}
