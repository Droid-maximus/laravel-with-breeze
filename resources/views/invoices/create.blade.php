<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="text-xl font-semibold text-gray-900">Jauns rēķins</h2>
    </div>
  </x-slot>

  <x-ui.card class="max-w-5xl mx-auto">
    <form method="POST" action="{{ route('invoices.store') }}" class="space-y-6">
      @csrf

      {{-- Pamatinformācija --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
          <label class="block text-sm font-medium text-gray-700">Klients</label>
          <select name="client_id"
                  class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">-- izvēlies klientu --</option>
            @foreach($clients as $client)
              <option value="{{ $client->id }}" @selected(old('client_id')==$client->id)>{{ $client->name }}</option>
            @endforeach
          </select>
          @error('client_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Valūta</label>
          <input name="currency" value="{{ old('currency','EUR') }}" maxlength="3"
                 class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
          @error('currency')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Datums</label>
          <input type="date" name="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}"
                 class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Apmaksas termiņš</label>
          <input type="date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+14 days'))) }}"
                 class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Statuss</label>
          <select name="status"
                  class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="draft" @selected(old('status')==='draft')>draft</option>
            <option value="sent" @selected(old('status')==='sent')>sent</option>
            <option value="paid" @selected(old('status')==='paid')>paid</option>
            <option value="cancelled" @selected(old('status')==='cancelled')>cancelled</option>
          </select>
          @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
      </div>

      {{-- Rindas --}}
      <div>
        <div class="flex items-center justify-between mb-2">
          <h3 class="text-lg font-semibold text-gray-900">Rindas</h3>
          <x-ui.button type="button" as="button" id="addRowBtn">+ Pievienot rindu</x-ui.button>
        </div>

        <div class="overflow-hidden rounded-xl ring-1 ring-gray-200">
          <table class="min-w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Apraksts</th>
                <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600">Daudzums</th>
                <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600">Cena</th>
                <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600">Atlaide</th>
                <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600">PVN %</th>
                <th class="px-3 py-2"></th>
              </tr>
            </thead>
            <tbody id="items-body" class="divide-y divide-gray-200">
              @php $oldItems = old('items', [['description'=>'','qty'=>1,'unit_price'=>0,'discount'=>0,'vat_rate'=>21]]); @endphp
              @foreach($oldItems as $i => $item)
                <tr class="odd:bg-white even:bg-gray-50">
                  <td class="px-3 py-2">
                    <input name="items[{{ $i }}][description]" value="{{ $item['description'] }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                  </td>
                  <td class="px-3 py-2 text-right">
                    <input name="items[{{ $i }}][qty]" value="{{ $item['qty'] }}" type="number" step="0.001"
                           class="w-28 text-right rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                  </td>
                  <td class="px-3 py-2 text-right">
                    <input name="items[{{ $i }}][unit_price]" value="{{ $item['unit_price'] }}" type="number" step="0.01"
                           class="w-32 text-right rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                  </td>
                  <td class="px-3 py-2 text-right">
                    <input name="items[{{ $i }}][discount]" value="{{ $item['discount'] }}" type="number" step="0.01"
                           class="w-28 text-right rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                  </td>
                  <td class="px-3 py-2 text-right">
                    <input name="items[{{ $i }}][vat_rate]" value="{{ $item['vat_rate'] }}" type="number" step="0.01"
                           class="w-24 text-right rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                  </td>
                  <td class="px-3 py-2 text-right">
                    <button type="button" class="text-red-600 hover:text-red-800 font-semibold" onclick="removeRow(this)">✕</button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <x-ui.button as="button" type="submit" variant="primary">Saglabāt</x-ui.button>
        <x-ui.button href="{{ route('invoices.index') }}">Atpakaļ</x-ui.button>
      </div>
    </form>
  </x-ui.card>

  @push('scripts')
  <script>
    // Rindu pievienošana/izņemšana
    const tbody = document.getElementById('items-body');
    document.getElementById('addRowBtn').addEventListener('click', () => {
      const i = tbody.rows.length;
      const tr = document.createElement('tr');
      tr.className = 'odd:bg-white even:bg-gray-50';
      tr.innerHTML = `
        <td class="px-3 py-2">
          <input name="items[${i}][description]" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
        </td>
        <td class="px-3 py-2 text-right">
          <input name="items[${i}][qty]" type="number" value="1" step="0.001" class="w-28 text-right rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
        </td>
        <td class="px-3 py-2 text-right">
          <input name="items[${i}][unit_price]" type="number" value="0" step="0.01" class="w-32 text-right rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
        </td>
        <td class="px-3 py-2 text-right">
          <input name="items[${i}][discount]" type="number" value="0" step="0.01" class="w-28 text-right rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
        </td>
        <td class="px-3 py-2 text-right">
          <input name="items[${i}][vat_rate]" type="number" value="21" step="0.01" class="w-24 text-right rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
        </td>
        <td class="px-3 py-2 text-right">
          <button type="button" class="text-red-600 hover:text-red-800 font-semibold" onclick="removeRow(this)">✕</button>
        </td>
      `;
      tbody.appendChild(tr);
    });

    function removeRow(btn){ btn.closest('tr').remove(); }
  </script>
  @endpush
</x-app-layout>
