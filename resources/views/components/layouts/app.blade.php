@props([
  'title' => 'Invoice Manager',
])

<!doctype html>
<html lang="lv">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title }}</title>

  {{-- Vite (ja izmantošu) --}}
  {{-- @vite(['resources/css/app.css','resources/js/app.js']) --}}

  <style>
    body{font-family:system-ui,Segoe UI,Arial,sans-serif;max-width:980px;margin:24px auto;padding:0 16px}
    nav a{margin-right:12px}
    table{width:100%;border-collapse:collapse}
    th,td{border-bottom:1px solid #ddd;padding:8px;text-align:left}
    .row{display:flex;gap:12px;flex-wrap:wrap}
    .col{flex:1 1 260px}
    .btn{display:inline-block;padding:8px 12px;border:1px solid #888;border-radius:6px;text-decoration:none}
    .btn-primary{background:#111;color:#fff;border-color:#111}
    .btn-danger{background:#b00020;color:#fff;border-color:#b00020}
    form .group{margin-bottom:12px}
    input,select,textarea{width:100%;padding:8px;border:1px solid #bbb;border-radius:6px}
    .muted{color:#666}
  </style>
</head>
<body>
  <nav>
    <a href="{{ route('clients.index') }}">Klienti</a>
    <a href="{{ route('invoices.index') }}">Rēķini</a>
  </nav>
  <hr>

  {{-- Flash ziņa --}}
  @if(session('status'))
    <p style="background:#e7ffe7;border:1px solid #9ad49a;padding:8px;border-radius:6px">
      {{ session('status') }}
    </p>
  @endif

  @if ($errors->any())
  <div style="background:#ffecec;border:1px solid #f5a3a3;padding:8px;border-radius:6px;margin:8px 0">
    <strong>Formā ir kļūdas:</strong>
    <ul style="margin:6px 0 0 16px">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif


  {{-- Galvenes slots (izvēles) --}}
  @isset($header)
    <div style="margin:8px 0">@php /** @var \Illuminate\View\ComponentSlot $header */ @endphp
      {{ $header }}
    </div>
  @endisset

  {{-- Galvenais saturs --}}
  {{ $slot }}
</body>
</html>
