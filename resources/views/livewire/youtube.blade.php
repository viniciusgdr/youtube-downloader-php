<div class="bg-slate-800 flex flex-col items-center gap-3 p-4">
    <div class="container rounded bg-white p-6 flex flex-col justify-center items-center max-w-4xl">
        <h1 class="text-4xl pb-12 text-center text-black">
            Download Video and Audio from YouTube
        </h1>
        <div class="flex justify-center flex-col w-full">
            <form class="flex flex-row items-center w-full" wire:submit.prevent="searchSubmit">
                <div class="join w-full">
                    <input wire:model="search" type="text" class="input input-bordered join-item w-full"
                           placeholder="Enter YouTube URL" value="{{ $id }}"/>
                    <button class="btn btn-primary join-item rounded-r-full" id="submit">
                        Procurar
                    </button>
                </div>
            </form>
            <p class="text-red-500 text-sm mt-2">
                @error('url') {{ $message }} @enderror
            </p>
            <p class="text-sm mt-2">
                Ao clicar em Procurar, você concorda com nossos <a href="#" class="text-blue-500">Termos de
                    Serviço</a> e <a href="#" class="text-blue-500">Política de Privacidade</a>.
            </p>
        </div>
    </div>
    @if ($infoVideo['title'])
        <div class="container rounded bg-white p-6 flex flex-col justify-center items-center max-w-4xl">
            <div class="grid md:flex grid-cols-1 gap-2 w-full">
                <div class="flex flex-col gap-2  md:w-2/5 items-center justify-center">
                    <img src="{{ $infoVideo['thumbnail'] }}" alt="{{ $infoVideo['title'] }}"
                         class="rounded-lg w-full" width="418" height="320"/>
                    <p class="text-black text-sm font-bold mt-2 w-full">{{ $infoVideo['title'] }}</p>
                </div>
                <div class="flex flex-col gap-2 md:w-3/5 items-center justify-center">
                    @if (count($infoVideo['videos']))
                        <h3 class="text-xl text-center text-black">
                            Videos
                        </h3>
                        @foreach($infoVideo['videos'] as $video)
                            <div class="w-full p-1 flex justify-between">
                                <p class="text-black text-sm font-bold mt-1 w-full">{{ $video['quality'] }}</p>
                                <a
                                    class="flex flex-col items-center"
                                    style="cursor: pointer"
                                    target="_blank"
                                    href="{{ $video['url'] }}"
                                >
                                    <button class="btn btn-sm btn-primary join-item rounded-xl">
                                        Download
                                    </button>
                                </a>
                            </div>
                        @endforeach
                    @endif
                    @if (count($infoVideo['audios']))
                        <h3 class="text-xl pb-4 text-black">
                            Audios
                        </h3>
                        @foreach($infoVideo['audios'] as $audio)
                            <div class="w-full p-1 flex justify-between">
                                <p class="text-black text-sm font-bold mt-1 w-full">{{ $audio['quality'] }}</p>
                                <a
                                    class="flex flex-col items-center"
                                    style="cursor: pointer"
                                    target="_blank"
                                    href="{{ $audio['url'] }}"
                                >
                                    <button class="btn btn-sm btn-primary join-item rounded-xl">
                                        Download
                                    </button>
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    @endif
    @if (count($resultsRecommended))
        <div class="container rounded bg-white p-6 flex flex-col justify-center items-center max-w-4xl">
            <h2 class="text-2xl pb-12 text-center text-black">
                Recomendados
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($resultsRecommended as $result)
                    <div class="w-full p-1">
                        <a
                            class="flex flex-col items-center"
                            style="cursor: pointer"
                            target="_blank"
                            href="{{ route('youtube', ['id' => $result['id']]) }}"
                        >
                            <img src="{{ $result['thumbnail'] }}" alt="{{ $result['title'] }}"
                                 class="rounded-lg w-full" width="214" height="160"/>
                            <p class="text-black text-sm font-bold mt-2 w-full">{{ trim(substr($result['title'], 0, 75)) }}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
    window.onload = function () {
        document.getElementById('submit').click();
    }
</script>
