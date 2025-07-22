<x-filament::page>
    <x-filament::widgets
        :widgets="$this->getHeaderWidgets()"
        :columns="1"
    />

    <x-filament::widgets
        :widgets="$this->getFooterWidgets()"
        :columns="1"
    />
</x-filament::page>
