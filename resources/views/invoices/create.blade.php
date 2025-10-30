<x-layouts.app title="Jauns rēķins">
  <h2>Jauns rēķins</h2>

  <form method="post" action="{{ route('invoices.store') }}">
    @csrf

    <div class="row">
      <div class="col">
        <div class="group">
          <label>Klients</label>
          <select name="client_id">
            <option value="">-- izvēlies klientu --</option>
            @foreach($clients as $client)
              <option value="{{ $client->id }}" @selected(old('client_id')==$client->id)>
                {{ $client->name }}
              </option>
            @endforeach
          </select>
          @error('client_id')<div class="muted">{{ $message }}</div>@enderror
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <div class="group">
          <label>Datums</label>
          <input type="date" name="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}">
        </div>
      </div>
      <div class="col">
        <div class="group">
          <label>Apmaksas termiņš</label>
          <input type="date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+14 days'))) }}">
        </div>
      </div>
    </div>

<div class="row">
  <div class="col">
    <div class="group">
      <label>Valūta</label>
      <input name="currency" value="{{ old('currency','EUR') }}" maxlength="3">
      @error('currency')<div class="muted">{{ $message }}</div>@enderror
    </div>
  </div>
  <div class="col">
    <div class="group">
      <label>Statuss</label>
      <select name="status">
        <option value="draft" @selected(old('status')==='draft')>draft</option>
        <option value="sent" @selected(old('status')==='sent')>sent</option>
        <option value="paid" @selected(old('status')==='paid')>paid</option>
        <option value="cancelled" @selected(old('status')==='cancelled')>cancelled</option>
      </select>
      @error('status')<div class="muted">{{ $message }}</div>@enderror
    </div>
  </div>
</div>


    <hr>

    <h3>Rindas</h3>
    <table id="items-table">
      <thead>
        <tr>
          <th>Apraksts</th><th>Daudzums</th><th>Cena</th><th>Atlaide</th><th>PVN %</th><th></th>
        </tr>
      </thead>
      <tbody id="items-body">
        @php $oldItems = old('items', [['description'=>'','qty'=>1,'unit_price'=>0,'discount'=>0,'vat_rate'=>21]]); @endphp
        @foreach($oldItems as $i => $item)
          <tr>
            <td><input name="items[{{ $i }}][description]" value="{{ $item['description'] }}"></td>
            <td><input name="items[{{ $i }}][qty]" value="{{ $item['qty'] }}" type="number" step="0.001"></td>
            <td><input name="items[{{ $i }}][unit_price]" value="{{ $item['unit_price'] }}" type="number" step="0.01"></td>
            <td><input name="items[{{ $i }}][discount]" value="{{ $item['discount'] }}" type="number" step="0.01"></td>
            <td><input name="items[{{ $i }}][vat_rate]" value="{{ $item['vat_rate'] }}" type="number" step="0.01"></td>
            <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">✕</button></td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <button type="button" class="btn" onclick="addRow()">+ Pievienot rindu</button>
    <hr>

    <button class="btn btn-primary">Saglabāt</button>
    <a class="btn" href="{{ route('invoices.index') }}">Atpakaļ</a>
  </form>

  <script>
    function addRow(){
      const tbody = document.getElementById('items-body');
      const i = tbody.rows.length;
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><input name="items[${i}][description]"></td>
        <td><input name="items[${i}][qty]" type="number" value="1" step="0.001"></td>
        <td><input name="items[${i}][unit_price]" type="number" value="0" step="0.01"></td>
        <td><input name="items[${i}][discount]" type="number" value="0" step="0.01"></td>
        <td><input name="items[${i}][vat_rate]" type="number" value="21" step="0.01"></td>
        <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">✕</button></td>
      `;
      tbody.appendChild(tr);
    }
    function removeRow(btn){
      btn.closest('tr').remove();
    }
  </script>
</x-layouts.app>
