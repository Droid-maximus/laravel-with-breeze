<x-layouts.app title="Jauns klients">
  <h2>Jauns klients</h2>

  <form method="post" action="{{ route('clients.store') }}" id="clientForm">
    @csrf
    <div class="row">
      <div class="col">
        <div class="group">
          <label>Tips</label>
          <select name="type" id="typeSelect">
            <option value="person" @selected(old('type')==='person')>Fiziska persona</option>
            <option value="company" @selected(old('type','company')==='company')>Juridiska persona</option>
          </select>
          @error('type')<div class="muted">{{ $message }}</div>@enderror
        </div>
      </div>
      <div class="col">
        <div class="group">
          <label id="nameLabel">Nosaukums / Vārds Uzvārds</label>
          <input name="name" id="nameInput" value="{{ old('name') }}">
          @error('name')<div class="muted">{{ $message }}</div>@enderror
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <div class="group">
          <label id="regLabel">Reģ. nr./personas kods</label>
          <input name="reg_no" id="regInput" value="{{ old('reg_no') }}">
          @error('reg_no')<div class="muted">{{ $message }}</div>@enderror
        </div>
      </div>
      <div class="col">
        <div class="group" id="vatGroup">
          <label>PVN numurs</label>
          <input name="vat_no" id="vatInput" value="{{ old('vat_no') }}">
          @error('vat_no')<div class="muted">{{ $message }}</div>@enderror
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col"><div class="group"><label>E-pasts</label><input name="email" value="{{ old('email') }}"></div></div>
      <div class="col"><div class="group"><label>Tālrunis</label><input name="phone" value="{{ old('phone') }}"></div></div>
    </div>

    <div class="group">
      <label>Adrese</label>
      <textarea name="address">{{ old('address') }}</textarea>
    </div>

    <button class="btn btn-primary">Saglabāt</button>
    <a class="btn" href="{{ route('clients.index') }}">Atpakaļ</a>
  </form>

  <script>
    const typeSelect = document.getElementById('typeSelect');
    const nameLabel  = document.getElementById('nameLabel');
    const nameInput  = document.getElementById('nameInput');
    const regLabel   = document.getElementById('regLabel');
    const regInput   = document.getElementById('regInput');
    const vatGroup   = document.getElementById('vatGroup');
    const vatInput   = document.getElementById('vatInput');

    function applyTypeUI() {
      const isCompany = typeSelect.value === 'company';

      // Etiķetes un vietturis
      nameLabel.textContent = isCompany ? 'Uzņēmuma nosaukums' : 'Vārds, Uzvārds';
      nameInput.placeholder = isCompany ? 'SIA Demo' : 'Jānis Bērziņš';
      regLabel.textContent  = isCompany ? 'Reģistrācijas numurs' : 'Personas kods';

      // PVN grupa redzamība un required
      vatGroup.style.display = isCompany ? '' : 'none';
      if (isCompany) {
        vatInput.setAttribute('required', 'required');
      } else {
        vatInput.removeAttribute('required');
        // opc. notīrīt vērtību, ja nevēlies saglabāt to personai:
        // vatInput.value = '';
      }

      // Reģ. nr./personas kods obligāts abos (pēc taviem noteikumiem var mainīt)
      regInput.setAttribute('required', 'required');
      nameInput.setAttribute('required', 'required');
    }

    // Palaist uzreiz (ņemta vērā old('type'))
    applyTypeUI();
    // Reaģēt uz izmaiņām
    typeSelect.addEventListener('change', applyTypeUI);
  </script>
</x-layouts.app>
