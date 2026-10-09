<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $post->title }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <p class="text-sm text-gray-500 mb-4">
                    Oleh {{ $post->user->name }} <x-role-badge :role="$post->user->role" />
                    · {{ $post->created_at->format('d M Y H:i') }}
                </p>

                <div class="text-gray-700 leading-relaxed">{!! nl2br(e($post->body)) !!}</div>

                @include('posts._actions')

                <a href="{{ route('posts.index') }}" class="inline-block mt-6 text-sm text-indigo-600 underline">&larr; Kembali</a>
            </div>
        </div>
    </div>
</x-app-layout>
