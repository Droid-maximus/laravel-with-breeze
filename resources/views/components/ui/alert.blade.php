@props(['type' => 'info','message' => null])
@php
$map = [
 'success' => 'border-green-300 bg-green-50 text-green-800',
 'error'   => 'border-red-300 bg-red-50 text-red-800',
 'info'    => 'border-blue-300 bg-blue-50 text-blue-800',
 'warning' => 'border-yellow-300 bg-yellow-50 text-yellow-800',
];
@endphp
<div {{ $attributes->merge(['class' => 'rounded-md border px-4 py-2 '.$map[$type]]) }}>
  {{ $message ?? $slot }}
</div>
