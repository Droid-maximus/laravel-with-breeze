@props(['title' => null])

<x-app-layout>
  @isset($header)
    <x-slot name="header">
      {{ $header }}
    </x-slot>
  @endisset

  {{-- (Neobligāti) title var ielikt head <title> – šeit var atstāt, Breeze ņem no config('app.name') --}}
  {{ $slot }}
</x-app-layout>
