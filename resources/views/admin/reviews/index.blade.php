<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reviews — Vendora Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-rose-50/30 text-ink font-sans antialiased">

    @include('admin.partials.header')

    <main class="max-w-5xl mx-auto px-6 lg:px-8 py-10">

        <div class="flex items-center justify-between mb-8">
            <h1 class="font-display text-3xl">Reviews</h1>
            <form action="{{ route('admin.reviews.index') }}" method="GET">
                <select name="status" onchange="this.form.submit()"
                        class="rounded-lg border border-rose-200 text-sm px-3 py-2 focus:outline-none focus:border-rose-400">
                    <option value="">All reviews</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending approval</option>
                    <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                </select>
            </form>
        </div>

        @if (session('admin_success'))
            <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3">
                {{ session('admin_success') }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse ($reviews as $review)
                <div class="bg-white rounded-2xl border border-rose-100 p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <div class="flex text-rose-500 text-sm">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                    @endfor
                                </div>
                                @if (! $review->is_approved)
                                    <span class="inline-flex rounded-full bg-amber-50 text-amber-700 text-[11px] font-medium px-2 py-0.5">Pending</span>
                                @endif
                            </div>
                            <p class="text-sm font-medium text-ink mt-1">
                                {{ $review->product->name }}
                                @if ($review->title) — {{ $review->title }} @endif
                            </p>
                            <p class="text-sm text-mauve mt-1">{{ $review->comment }}</p>
                            <p class="text-xs text-mauve mt-2">{{ $review->reviewer_name }} ({{ $review->reviewer_email }}) · {{ $review->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex flex-col items-end gap-2 shrink-0">
                            @unless ($review->is_approved)
                                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">Approve</button>
                                </form>
                            @endunless
                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                  onsubmit="return confirm('Remove this review?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-sm text-mauve hover:text-rose-600 font-medium">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-rose-100 py-16 text-center text-mauve">
                    No reviews found.
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $reviews->links() }}</div>

    </main>

</body>
</html>