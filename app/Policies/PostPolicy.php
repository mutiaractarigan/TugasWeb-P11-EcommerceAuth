<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Semua user yang login boleh melihat daftar post.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Post $post): bool
    {
        return true;
    }

    /**
     * Hanya admin dan editor yang boleh menulis post.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'editor');
    }

    /**
     * Admin boleh mengedit semua post,
     * editor hanya boleh mengedit post miliknya sendiri.
     */
    public function update(User $user, Post $post): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->hasRole('editor') && $post->user_id === $user->id;
    }

    /**
     * Aturan hapus sama dengan aturan edit.
     */
    public function delete(User $user, Post $post): bool
    {
        return $this->update($user, $post);
    }
}
