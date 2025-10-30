@props(['color' => 'gray'])
@php
$map = [
  'gray'  => 'bg-gray-100 text-gray-800',
  'green' => 'bg-green-100 text-green-800',
  'yellow'=> 'bg-yellow-100 text-yellow-800',
  'red'   => 'bg-red-100 text-red-800',
  'blue'  => 'bg-blue-100 text-blue-800',
];
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium '.$map[$color]]) }}>
  {{ $slot }}
</span>
