<div {{ $attributes->merge([
  'class' => 'bg-white shadow rounded-2xl'   {{-- noņemam dark: klases --}}
]) }}>
  <div class="p-4 sm:p-6">
    {{ $slot }}
  </div>
</div>
