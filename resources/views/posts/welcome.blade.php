<x-app-layout>
    <img class="md:h-96 h-48 w-full object-cover" src="{{ asset('images/main_img.jpg') }}" alt="">
    <section>
        <div class="pt-10">
            <h1 class="text-gray-900 lg:text-3xl text-2xl text-center font-thin">Would you like to make portfolio
                together?</h1>
            <p class="pt-4 pb-10 w-1/2 mx-auto text-center">
                このアプリはご自身で作成したWEBアプリを保存するためのポートフォリオアプリです。他の人が作成したアプリも見られるようにしていますので、是非ご自身のアプリ作成の参考にしてください。</p>
        </div>
    </section>

    <div class="container max-w-screen-xl mx-auto px-4 md:px-12 pb-3 mt-3">

        <h1 class="text-gray-900 lg:text-3xl text-2xl text-center font-thin pb-10">Works</h1>

        <x-flash-message :message="session('notice')" />

        <div class="flex flex-wrap mx-1 lg:mx-4 mb-4">
            @foreach ($posts as $post)
                <article class="w-full p-4 md:w-1/3 text-l text-gray-800 leading-normal">
                    <a href="{{ route('posts.show', $post) }}">
                        <img class="lg:h-48 md:h-36 w-full object-cover" src="{{ $post->image_url }}" alt="">
                        <div class="p-3">
                            <h2>制作者&nbsp;:&nbsp;{{ $post->user->name }}</h2>
                            <h2
                                class="font-bold font-sans break-normal text-gray-900 text-base md:text-4xl break-words pb-3">
                                {{ $post->title }}</h2>
                        </div>
                    </a>
                </article>
            @endforeach
            <div class="mx-auto text-center py-10">
                <a href="{{ route('posts.index') }}"
                    class="text-gray-900 lg:text-3xl text-2xl font-thin px-16 py-1 border-4 border-black">more...</a>
            </div>
        </div>
    </div>
</x-app-layout>
