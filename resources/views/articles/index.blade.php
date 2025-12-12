@extends('layouts.app')
@section('page_title', 'Articles')
@section('breadcrumb', 'Home » Articles')
@section('content')

<div class="max-w-7xl mx-auto px-4 py-10 grid grid-cols-1 lg:grid-cols-4 gap-10">
    <div class="lg:col-span-3">
        @if ($articles->isEmpty())
            <p class="text-gray-500 italic">No articles available.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-7">
                @foreach ($articles as $article)
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition border overflow-hidden">

                        @if ($article->image)
                            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-56 object-cover">
                        @endif

                        <div class="p-6 text-left">
                            <p class="text-gray-500 text-xs mb-1">
                                {{ $article->created_at->format('F d, Y') }} — Fashion
                            </p>

                            <h2 class="text-lg font-semibold text-gray-900 leading-tight mb-3 min-h-[55px]">
                                {{ $article->title }}
                            </h2>

                            <a href="{{ route('articles.show', $article->slug) }}" class="inline-flex items-center text-blue-600 font-semibold hover:text-blue-800 transition text-sm">
                                Read More →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $articles->links() }}
            </div>
        @endif
    </div>

    <div class="lg:col-span-1">
      
       <div class="bg-white border border-gray-200 rounded-2xl shadow-md p-6">
    <h3 class="text-lg font-semibold mb-5">Recent Articles</h3>

    @foreach ($articles->take(4) as $recent)
        <div class="flex items-center gap-4 mb-6 pb-5 border-b last:border-none">
            @if ($recent->image)
                <img src="{{ asset('storage/' . $recent->image) }}" 
                     class="w-16 h-16 object-cover rounded-lg">
            @endif

            <div>
                <a href="{{ route('articles.show', $recent->slug) }}" 
                   class="font-semibold text-gray-800 hover:text-blue-600">
                    {{ Str::limit($recent->title, 50) }}
                </a>

                <p class="text-xs text-gray-500">
                    {{ $recent->created_at->format('F d, Y') }}
                </p>

                <p class="text-xs text-blue-600 mt-1">
                    Fashion
                </p>
            </div>
        </div>
    @endforeach
</div>


    </div>

</div>
@endsection
