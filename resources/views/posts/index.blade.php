<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Posts</h2>
            @can('create', App\Models\Post::class)
                <a href="{{ route('posts.create') }}">
                    <x-primary-button>+ Tulis Post</x-primary-button>
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @forelse ($posts as $post)
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="font-semibold text-lg">
                        <a href="{{ route('posts.show', $post) }}" class="hover:underline">{{ $post->title }}</a>
                    </h3>
                    <p class="text-sm text-gray-500">
                        Oleh {{ $post->user->name }} <x-role-badge :role="$post->user->role" />
                        · {{ $post->created_at->diffForHumans() }}
                    </p>
                    <p class="mt-2 text-gray-700">{{ Str::limit($post->body, 160) }}</p>

                    @include('posts._actions')
                </div>
            @empty
                <p class="text-gray-500">Belum ada post.</p>
            @endforelse

            {{ $posts->links() }}
        </div>
    </div>
</x-app-layout>
