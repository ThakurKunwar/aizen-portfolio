<x-layouts.admin title="Messages">
    <h1 class="mb-6 font-serif text-3xl font-semibold">Messages</h1>

    @forelse ($messages as $message)
        <article class="mb-4 rounded-xl border border-line bg-paper p-5">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <p class="font-semibold">{{ $message->name }}</p>
                    <p class="text-sm"><a href="mailto:{{ $message->email }}" class="link">{{ $message->email }}</a></p>
                </div>
                <p class="text-sm text-slate">{{ $message->created_at->diffForHumans() }}</p>
            </div>

            <p class="mt-3 whitespace-pre-line leading-relaxed">{{ $message->message }}</p>

            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" class="mt-4" onsubmit="return confirm('Delete this message?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-small btn-danger">Delete</button>
            </form>
        </article>
    @empty
        <p class="text-slate">No messages yet.</p>
    @endforelse

    @if ($messages->hasPages())
        <nav class="mt-6 flex items-center justify-between text-sm" aria-label="Pages">
            @if ($messages->onFirstPage())
                <span class="text-slate">Previous</span>
            @else
                <a href="{{ $messages->previousPageUrl() }}" class="link">Previous</a>
            @endif

            <span class="text-slate">Page {{ $messages->currentPage() }} of {{ $messages->lastPage() }}</span>

            @if ($messages->hasMorePages())
                <a href="{{ $messages->nextPageUrl() }}" class="link">Next</a>
            @else
                <span class="text-slate">Next</span>
            @endif
        </nav>
    @endif
</x-layouts.admin>
