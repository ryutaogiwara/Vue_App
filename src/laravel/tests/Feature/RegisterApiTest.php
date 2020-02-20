<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class RegisterApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */

    public function shouls_新しいユーザーを追加して返却する()
    {
        $data = [
            'name' => 'hoge user',
            'email' => 'dummy@email.jp',
            'password' => 'password',
            'passwaord_confirmation' => 'password'
        ];

        // ユーザー登録
        $response = $this->json('POST', route('register'), $data);

        // ユーザー検索
        $user = User::first();
        $this->assertEquals($data['name'], $user->name);

        $response
            ->assertStatus(201)
            ->assertJson(['name' => $user->name]);

    }
}
