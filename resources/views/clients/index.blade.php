<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="text-xl font-semibold text-gray-900">Klienti</h2>
      <div class="flex gap-2">
          @can('is-admin')
        <x-ui.button href="{{ route('clients.index') }}">Visi</x-ui.button>
        <x-ui.button href="{{ route('clients.index', ['type' => 'company']) }}">Juridiskie</x-ui.button>
        <x-ui.button href="{{ route('clients.index', ['type' => 'person']) }}">Fiziskie</x-ui.button>
        <x-ui.button variant="primary" href="{{ route('clients.create') }}">+ Jauns</x-ui.button>
        @endcan
      </div>
    </div>
  </x-slot>

  <x-ui.table class="mt-4">
    {{--  Header daļa --}}
    <thead class="bg-gray-50 sticky top-0 z-10">
      <tr>
        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Nosaukums</th>
        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Tips</th>
        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Reģ. nr.</th>
        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">PVN</th>
        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">E-pasts</th>
        <th class="px-4 py-3"></th>
      </tr>
    </thead>

    {{--  Saturs --}}
    <tbody class="divide-y divide-gray-200">
      @forelse ($clients as $c)
        <tr class="odd:bg-white even:bg-gray-50 hover:bg-indigo-50/40">
          <td class="px-4 py-3 font-medium text-gray-900">{{ $c->name }}</td>
          <td class="px-4 py-3">
            <x-ui.badge :color="$c->type === 'company' ? 'blue' : 'gray'">
              {{ $c->type }}
            </x-ui.badge>
          </td>
          <td class="px-4 py-3">{{ $c->reg_no }}</td>
          <td class="px-4 py-3" >{{ $c->vat_no ?? '-'}}</td>
          <td class="px-4 py-3">{{ $c->email }}</td>
          <td class="px-4 py-3">
            <div class="flex gap-2 justify-end sm:justify-start">
                  @can('update', $c)
              <x-ui.button href="{{ route('clients.edit', $c) }}">Labot</x-ui.button>
                  @endcan

              @can('delete', $c)
              <form action="{{ route('clients.destroy', $c) }}" method="POST" onsubmit="return confirm('Dzēst klientu?')">
                @csrf
                @method('DELETE')
                <x-ui.button as="button" type="submit" variant="danger">Dzēst</x-ui.button>
              </form>
                  @endcan
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="px-4 py-6 text-gray-500 text-center">Nav klientu.</td>
        </tr>
      @endforelse
    </tbody>
  </x-ui.table>

  <div class="mt-4">{{ $clients->withQueryString()->links() }}</div>
</x-app-layout>
