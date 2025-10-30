@props(['maxh' => '70vh'])

<div {{ $attributes->merge([
  'class' => 'overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200'
]) }}>
  <div class="overflow-x-auto">
    <table class="min-w-full">
      {{ $slot }}
    </table>
  </div>
</div>
