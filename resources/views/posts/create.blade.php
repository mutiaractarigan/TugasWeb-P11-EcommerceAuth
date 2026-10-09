<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tulis Post</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('posts.store') }}">
                    @csrf
                    @include('posts._form')

                    <div class="mt-6 flex gap-2">
                        <x-primary-button>Simpan</x-primary-button>
                        <a href="{{ route('posts.index') }}"><x-secondary-button>Batal</x-secondary-button></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
