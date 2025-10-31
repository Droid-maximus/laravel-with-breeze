<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="text-xl font-semibold text-gray-900">Rēķini</h2>
        @can('is-admin')
      <x-ui.button variant="primary" href="{{ route('invoices.create') }}">+ Jauns rēķins</x-ui.button>
      @endcan
    </div>
  </x-slot>

  @php $statusColor = ['draft'=>'gray','sent'=>'yellow','paid'=>'green','cancelled'=>'red']; @endphp

  <x-ui.table class="mt-4">
    <thead class="bg-gray-50 sticky top-0 z-10">
      <tr>
        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Numurs</th>
        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Klients</th>
        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Datums</th>
        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Termiņš</th>
        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Statuss</th>
        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">Summa (ar PVN)</th>
        <th class="px-4 py-3"></th>
      </tr>
    </thead>

    <tbody class="divide-y divide-gray-200">
      @forelse($invoices as $inv)
        <tr class="odd:bg-white even:bg-gray-50 hover:bg-indigo-50/40">
          <td class="px-4 py-3 font-medium text-gray-900">{{ $inv->number }}</td>
          <td class="px-4 py-3">{{ $inv->client->name }}</td>
          <td class="px-4 py-3">{{ $inv->issue_date }}</td>
          <td class="px-4 py-3">{{ $inv->due_date }}</td>
          <td class="px-4 py-3">
            <x-ui.badge :color="$statusColor[$inv->status] ?? 'gray'">{{ ucfirst($inv->status) }}</x-ui.badge>
          </td>
          <td class="px-4 py-3 text-right">{{ number_format($inv->total_gross, 2, ',', ' ') }} €</td>
          <td class="px-4 py-3">
            <div class="flex gap-2 justify-end sm:justify-start">
              @can('update', $inv)
                <x-ui.button href="{{ route('invoices.edit',$inv) }}">Labot</x-ui.button>
              @endcan
                  @can('delete', $inv)
              <form action="{{ route('invoices.destroy',$inv) }}" method="post"
                    onsubmit="return confirm('Dzēst rēķinu?')">
                @csrf @method('DELETE')
                <x-ui.button as="button" type="submit" variant="danger">Dzēst</x-ui.button>
              </form>
              @endcan
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" class="px-4 py-6 text-gray-500">Nav rēķinu.</td>
        </tr>
      @endforelse
    </tbody>
  </x-ui.table>

  <div class="mt-4">{{ $invoices->withQueryString()->links() }}</div>
</x-app-layout>
