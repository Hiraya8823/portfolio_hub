<x-app-layout>
    <div class="container lg:w-3/4 md:w-4/5 w-11/12 mx-auto my-8 px-8 py-4 bg-white shadow-md">

        <x-flash-message :message="session('notice')" />

        <x-validation-errors :errors="$errors" />

        <article class="mb-2">
            <div class="lg:flex md:flex-none items-center pt-5 gap-10">
                <div class="basis-1/2">
                    <img src="{{ $post->image_url }}" alt="">
                </div>
                <div class="basis-1/2">
                    <h2
                        class="font-bold font-sans break-normal text-gray-900 pt-6 pb-1 text-3xl md:text-4xl break-words">
                        {{ $post->title }}</h2>
                    <h3>制作者&nbsp;:&nbsp;{{ $post->user->name }}</h3>
                    <p class="text-sm mb-2 md:text-base fonr-normal text-gray-600">
                        制作日&nbsp;:&nbsp;<span
                            class="text-red-400 font-bold">{{ date('Y-m-d H:i:s', strtotime('-10 day')) < $post->created_at ? 'NEW' : '' }}</span>
                        {{ $post->created_at }}
                    </p>
                    <a href="{{ $post->url }}" class="text-blue-600 hover:text-blue-800 font-bold"
                        target="_blank">ホームページを見る</a>
                </div>
            </div>
            <h2 class="font-bold text-lg text-gray-900 pt-10 pb-1">ホームページ概要</h2>
            <p class="text-gray-600 text-vase pb-4">{!! nl2br(e($post->description)) !!}</p>
        </article>
        <div class="flex place-content-between items-center">
            <div class="my-4">
                @if ($nice)
                    <a href="{{ route('unnice', $post) }}"
                        class="bg-rose-400 hover:bg-rose-300 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-20 mr-2">
                        いいね
                        <span class="badge">
                            {{ $nices }}
                        </span>
                    </a>
                @else
                    <a href="{{ route('nice', $post) }}"
                        class="bg-rose-300 hover:bg-rose-400 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-20 mr-2">
                        いいね
                        <span class="badge">
                            {{ $nices }}
                        </span>
                    </a>
                @endif
            </div>
            <div class="flex flex-row text-center my-4">
                @can('update', $post)
                    <a href="{{ route('posts.edit', $post) }}"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-20 mr-2">編集</a>
                @endcan
                @can('delete', $post)
                    <form action="{{ route('posts.destroy', $post) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="削除" onclick="if(!confirm('本当に削除しますか？')){return false};"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-20">
                    </form>
                @endcan
            </div>
        </div>
    </div>

</x-app-layout>
