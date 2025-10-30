@props(['variant' => 'default', 'as' => 'a', 'href' => null, 'type' => 'button'])

@php
  $base = 'inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium transition focus:outline-none focus:ring';
  $variants = [
    'default' => 'bg-white text-gray-900 border border-gray-300 hover:bg-gray-50 focus:ring-indigo-300',
    'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-indigo-300',
    'danger'  => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-300',
    'ghost'   => 'bg-transparent text-gray-700 hover:bg-gray-100 border border-gray-300',
  ];
  $classes = $base.' '.$variants[$variant];
@endphp

@if($as === 'button')
  <button type="{{ $type }}" {{ $attributes->merge(['class'=>$classes]) }}>
    {{ $slot }}
  </button>
@else
  <a @if($href) href="{{ $href }}" @endif {{ $attributes->merge(['class'=>$classes]) }}>
    {{ $slot }}
  </a>
@endif
