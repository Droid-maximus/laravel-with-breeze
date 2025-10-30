<x-layouts.app title="Rēķini">
  <x-slot:header>
    <div class="row" style="align-items:center;justify-content:space-between">
      <h2>Rēķini</h2>
      <a class="btn btn-primary" href="{{ route('invoices.create') }}">+ Jauns rēķins</a>
    </div>
  </x-slot:header>

  <table>
    <thead>
      <tr>
        <th>Numurs</th>
        <th>Klients</th>
        <th>Datums</th>
        <th>Termiņš</th>
        <th>Statuss</th>
        <th>Summa (ar PVN)</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($invoices as $inv)
        <tr>
          <td>{{ $inv->number }}</td>
          <td>{{ $inv->client->name }}</td>
          <td>{{ $inv->issue_date }}</td>
          <td>{{ $inv->due_date }}</td>
          <td>{{ ucfirst($inv->status) }}</td>
          <td>{{ number_format($inv->total_gross, 2, ',', ' ') }} €</td>
          <td style="white-space:nowrap">
            <a class="btn" href="{{ route('invoices.edit',$inv) }}">Labot</a>
            <form action="{{ route('invoices.destroy',$inv) }}" method="post" style="display:inline"
                  onsubmit="return confirm('Dzēst rēķinu?')">
              @csrf @method('DELETE')
              <button class="btn btn-danger" type="submit">Dzēst</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="7" class="muted">Nav rēķinu.</td></tr>
      @endforelse
    </tbody>
  </table>

  <div style="margin-top:12px">{{ $invoices->links() }}</div>
</x-layouts.app>
