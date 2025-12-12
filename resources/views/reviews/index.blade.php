@extends('layouts.app')

@section('title', 'All Reviews')
@section('page_title', 'All Reviews')
@section('breadcrumb', 'Home » All Reviews')
@section('content')

   

    <section class="py-10 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">All Reviews</h1>
            </div>

            @if ($ratings->count() > 0)
                <div class="space-y-6">
                    @foreach ($ratings as $rating)
                        <div class="border border-gray-200 rounded-2xl p-6 shadow-sm bg-white">
                            <div class="flex items-start gap-4">
                                <img src="{{ $rating->customer && $rating->customer->profile_photo
                                    ? asset('storage/' . $rating->customer->profile_photo)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($rating->customer->name ?? 'User') }}"
                                    class="w-12 h-12 rounded-full object-cover border">

                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="font-semibold text-gray-900">{{ $rating->customer->name ?? 'Anonymous' }}
                                        </h3>
                                        <div class="flex text-yellow-400">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c-.3-.921 1.603-.921 1.902 0l1.286 3.974 4.181.032c.969.007 1.371 1.24.588 1.81l-3.378 2.455 1.266 3.998c.29.916-.755 1.688-1.54 1.118l-3.422-2.475-3.422 2.475c-.785.57-1.83-.202-1.54-1.118l1.266-3.998-3.378-2.455c-.783-.57-.381-1.803.588-1.81l4.181-.032 1.286-3.974z" />
                                                </svg>
                                            @endfor
                                        </div>
                                        <span
                                            class="text-sm text-gray-500">{{ $rating->created_at->format('M d, Y') }}</span>
                                    </div>

                                    <p class="text-gray-700 mb-3">{{ $rating->comment }}</p>

                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <span>On:</span>
                                        <a href="{{ route('product.show', $rating->product->id) }}"
                                            class="text-blue-600 hover:text-blue-800 font-medium">
                                            {{ $rating->product->product_name }}
                                        </a>
                                    </div>

                                    @if ($rating->image)
                                        <img src="{{ asset('storage/' . $rating->image) }}"
                                            class="w-20 h-20 mt-3 rounded-lg border object-cover">
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $ratings->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No reviews yet</h3>
                    <p class="text-gray-500">Be the first to leave a review!</p>
                </div>
            @endif
        </div>
    </section>

@endsection
