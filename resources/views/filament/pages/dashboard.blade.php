<x-filament::page>
    <x-filament::grid>
        {{ $this->header }}

        @if (filled($this->headerWidgets))
            <x-filament::grid @class(['gap-4 lg:gap-8'])>
                @foreach ($this->headerWidgets as $widget)
                    {{ $widget }}
                @endforeach
            </x-filament::grid>
        @endif

        <div class="p-2 space-y-4">
            <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
                <h2 class="text-2xl font-bold">Добро пожаловать в админ-панель HolodLab</h2>
                <p class="mt-2 text-gray-600 dark:text-gray-300">
                    Здесь вы можете управлять всеми аспектами системы. Используйте навигацию слева для доступа к различным разделам.
                </p>
            </div>
        </div>
    </x-filament::grid>
</x-filament::page>
