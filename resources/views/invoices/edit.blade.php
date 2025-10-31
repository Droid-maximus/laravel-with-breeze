<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold text-gray-900">Labot rēķinu</h2>
  </x-slot>

  <x-ui.card class="max-w-5xl mx-auto !bg-white">
    <form method="POST" action="{{ route('invoices.update', $invoice) }}" class="space-y-6">
      @csrf @method('PUT')

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700">Rēķina numurs</label>
          <input name="number" value="{{ old('number', $invoice->number) }}"
                 class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
          @error('number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="lg:col-span-2">
          <label class="block text-sm font-medium text-gray-700">Klients</label>
          <select name="client_id"
                  class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @foreach($clients as $client)
              <option value="{{ $client->id }}"
                @selected(old('client_id', $invoice->client_id) == $client->id)>{{ $client->name }}</option>
            @endforeach
          </select>
          @error('client_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Datums</label>
          <input type="date" name="issue_date" value="{{ old('issue_date', $invoice->issue_date) }}"
                 class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Apmaksas termiņš</label>
          <input type="date" name="due_date" value="{{ old('due_date', $invoice->due_date) }}"
                 class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Valūta</label>
          <input name="currency" value="{{ old('currency', $invoice->currency) }}" maxlength="3"
                 class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
          @error('currency')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Statuss</label>
          <select name="status"
                  class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="draft"     @selected(old('status', $invoice->status)==='draft')>draft</option>
            <option value="sent"      @selected(old('status', $invoice->status)==='sent')>sent</option>
            <option value="paid"      @selected(old('status', $invoice->status)==='paid')>paid</option>
            <option value="cancelled" @selected(old('status', $invoice->status)==='cancelled')>cancelled</option>
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
                <th class="px-3 py-2 text-left  text-xs font-semibold text-gray-600">Apraksts</th>
                <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600">Daudzums</th>
                <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600">Cena</th>
                <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600">Atlaide(uz 1 vienības)EUR</th>
                <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600">PVN %</th>
                <th class="px-3 py-2"></th>
              </tr>
            </thead>
            <tbody id="items-body" class="divide-y divide-gray-200">
              @foreach(old('items', $invoice->items->map(fn($it) => [
                  'description'=>$it->description,
                  'qty'=>$it->qty,
                  'unit_price'=>$it->unit_price,
                  'discount'=>$it->discount,
                  'vat_rate'=>$it->vat_rate,
                ])->toArray()) as $i => $item)
                <tr class="odd:bg-white even:bg-gray-50">
                  <td class="px-3 py-2">
                    <input name="items[{{ $i }}][description]" value="{{ $item['description'] }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                  </td>
                  <td class="px-3 py-2 text-right">
                    <input name="items[{{ $i }}][qty]" value="{{ $item['qty'] }}" type="number" step="1"
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
          <input name="items[${i}][qty]" type="number" value="1" step="1" class="w-28 text-right rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
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
