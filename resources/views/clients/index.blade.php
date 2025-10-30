<x-layouts.app title="Klienti">
  <x-slot:header>
    <div class="row" style="align-items:center;justify-content:space-between">
      <h2>Klienti</h2>
      <div>
        <a class="btn" href="{{ route('clients.index') }}">Visi</a>
        <a class="btn" href="{{ route('clients.index',['type'=>'company']) }}">Juridiskie</a>
        <a class="btn" href="{{ route('clients.index',['type'=>'person']) }}">Fiziskie</a>
        <a class="btn btn-primary" href="{{ route('clients.create') }}">+ Jauns</a>
      </div>
    </div>
  </x-slot:header>

  <table>
    <thead>
      <tr><th>Nosaukums</th><th>Tips</th><th>Reģ. nr.</th><th>PVN</th><th>E-pasts</th><th></th></tr>
    </thead>
    <tbody>
      @forelse($clients as $c)
        <tr>
          <td>{{ $c->name }}</td>
          <td>{{ $c->type }}</td>
          <td>{{ $c->reg_no }}</td>
          <td>{{ $c->vat_no }}</td>
          <td>{{ $c->email }}</td>
          <td style="white-space:nowrap">
            <a class="btn" href="{{ route('clients.edit',$c) }}">Labot</a>
            <form action="{{ route('clients.destroy',$c) }}" method="post" style="display:inline" onsubmit="return confirm('Dzēst klientu?')">
              @csrf @method('DELETE')
              <button class="btn btn-danger" type="submit">Dzēst</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" class="muted">Nav klientu.</td></tr>
      @endforelse
    </tbody>
  </table>

  <div style="margin-top:12px">{{ $clients->links() }}</div>
</x-layouts.app>
