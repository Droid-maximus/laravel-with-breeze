<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Invoice Manager') }}</title>

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">   {{-- noņem dark:bg-gray-900 --}}
  <div class="min-h-screen">
    @include('layouts.navigation')

    @isset($header)
      <header class="bg-white border-b">   {{-- gaiša galvene --}}
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
          {{ $header }}
        </div>
      </header>
    @endisset

    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
      {{-- flash/errors --}}
      {{ $slot }}
    </main>

    {{-- JS --}}
    @stack('scripts')
  </div>
</body>

</html>
