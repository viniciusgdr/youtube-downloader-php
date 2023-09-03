<div class="bg-slate-800 flex flex-col items-center gap-3 p-4">
    <div class="container rounded bg-white p-6 flex flex-col justify-center items-center max-w-4xl">
        <h1 class="text-4xl pb-12 text-center text-black">
            Download Video and Audio from YouTube
        </h1>
        <div class="flex justify-center flex-col w-full">
            <form class="flex flex-row items-center w-full" wire:submit.prevent="searchSubmit">
                <div class="join w-full">
                    <input wire:model="search" type="text" class="input input-bordered join-item w-full"
                           placeholder="Enter YouTube URL"/>
                    <button class="btn btn-primary join-item rounded-r-full">
                        @if ($loading)
                            <div class="loading loading-spinner loading-sm"></div>
                        @else
                            Procurar
                        @endif
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
</div>
