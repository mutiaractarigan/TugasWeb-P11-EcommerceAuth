<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        foreach (['/dashboard', '/products', '/posts', '/posts/create', '/admin'] as $url) {
            $this->get($url)->assertRedirect('/login');
        }
    }

    public function test_new_registered_user_gets_user_role(): void
    {
        $this->post('/register', [
            'name' => 'Pengguna Baru',
            'email' => 'baru@example.com',
            'role' => 'admin', // dicoba disusupkan, harus diabaikan
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertSame('user', User::where('email', 'baru@example.com')->first()->role);
    }

    public function test_only_admin_can_open_admin_panel(): void
    {
        $this->actingAs(User::factory()->admin()->create())->get('/admin')->assertOk();
        $this->actingAs(User::factory()->editor()->create())->get('/admin')->assertForbidden();
        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
    }

    public function test_all_roles_can_view_products_and_posts(): void
    {
        foreach ([User::factory()->admin()->create(), User::factory()->editor()->create(), User::factory()->create()] as $user) {
            $this->actingAs($user)->get('/products')->assertOk();
            $this->actingAs($user)->get('/posts')->assertOk();
        }
    }

    public function test_user_role_cannot_create_post(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/posts/create')->assertForbidden();
        $this->actingAs($user)->post('/posts', ['title' => 'Judul Post', 'body' => str_repeat('isi ', 10)])->assertForbidden();
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_editor_can_create_and_edit_own_post(): void
    {
        $editor = User::factory()->editor()->create();

        $this->actingAs($editor)->post('/posts', ['title' => 'Post Editor', 'body' => str_repeat('isi ', 10)])
            ->assertRedirect();

        $post = Post::first();
        $this->assertSame($editor->id, $post->user_id);

        $this->actingAs($editor)->get("/posts/{$post->id}/edit")->assertOk();
        $this->actingAs($editor)->put("/posts/{$post->id}", ['title' => 'Post Editor Revisi', 'body' => str_repeat('isi ', 10)])
            ->assertRedirect();
        $this->assertSame('Post Editor Revisi', $post->fresh()->title);
    }

    public function test_editor_cannot_edit_or_delete_other_users_post(): void
    {
        $post = Post::factory()->for(User::factory()->admin())->create();
        $editor = User::factory()->editor()->create();

        $this->actingAs($editor)->get("/posts/{$post->id}/edit")->assertForbidden();
        $this->actingAs($editor)->delete("/posts/{$post->id}")->assertForbidden();
        $this->assertModelExists($post);
    }

    public function test_admin_can_delete_any_post(): void
    {
        $post = Post::factory()->for(User::factory()->editor())->create();

        $this->actingAs(User::factory()->admin()->create())->delete("/posts/{$post->id}")
            ->assertRedirect('/posts');
        $this->assertModelMissing($post);
    }

    public function test_post_validation(): void
    {
        $this->actingAs(User::factory()->editor()->create())
            ->post('/posts', ['title' => 'abc', 'body' => 'pendek'])
            ->assertSessionHasErrors(['title', 'body']);
    }
}
