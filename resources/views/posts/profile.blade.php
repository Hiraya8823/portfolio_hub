<x-app-layout>
    <div class="container max-w-screen-xl mx-auto px-4 md:px-12 pb-3 mt-3">

        <h1 class="text-gray-900 lg:text-3xl text-2xl text-center font-thin pb-10 pt-10">{{ $user->user->name }}さんの記事一覧</h1>

        <x-flash-message :message="session('notice')" />

        <div class="flex flex-wrap mx-1 lg:mx-4 mb-4">
            @foreach ($posts as $post)
                <article class="w-full p-4 md:w-1/3 text-l text-gray-800 leading-normal">
                    <a href="{{ route('posts.show', $post) }}">
                        <img class="lg:h-48 md:h-36 w-full object-cover" src="{{ $post->image_url }}" alt="">
                        <div class="p-3">
                            <h2>制作者&nbsp;:&nbsp;{{ $post->user->name }}</h2>
                            <h2
                                class="font-bold font-sans break-normal text-gray-900 text-base md:text-4xl break-words pb-6">
                                {{ $post->title }}</h2>
                        </div>
                    </a>
                </article>
            @endforeach
        </div>
        {{ $posts->links() }}
    </div>
</x-app-layout>
