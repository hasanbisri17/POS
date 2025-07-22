@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center">
    <h1 class="filament-header-heading text-2xl font-bold tracking-tight">
        {{ $title }}
    </h1>
    <p class="filament-header-subheading mt-2 text-gray-500">
        {{ $description }}
    </p>
</div>
