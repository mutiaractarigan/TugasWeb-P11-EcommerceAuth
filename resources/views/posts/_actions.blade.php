{{-- Tombol edit/hapus hanya muncul jika PostPolicy mengizinkan --}}
@canany(['update', 'delete'], $post)
    <div class="mt-4 flex gap-2">
        @can('update', $post)
            <a href="{{ route('posts.edit', $post) }}">
                <x-secondary-button>Edit</x-secondary-button>
            </a>
        @endcan

        @can('delete', $post)
            <form method="POST" action="{{ route('posts.destroy', $post) }}"
                  onsubmit="return confirm('Yakin ingin menghapus post ini?');">
                @csrf
                @method('DELETE')
                <x-danger-button>Hapus</x-danger-button>
            </form>
        @endcan
    </div>
@endcanany
