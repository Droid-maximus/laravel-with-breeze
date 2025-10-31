<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold text-gray-900">Labot klientu</h2>
  </x-slot>

    @auth
    @if(auth()->user()->client)
      <x-nav-link :href="route('clients.edit', auth()->user()->client)" :active="request()->routeIs('clients.edit')">
        Mans profils
      </x-nav-link>
    @endif
  @endauth




  <x-ui.card class="max-w-3xl mx-auto !bg-white">
    <form method="POST" action="{{ route('clients.update', $client) }}" id="clientForm" class="space-y-6">
      @csrf @method('PUT')

        @can('is-admin')
      <div class="group">
        <label>Īpašnieks (lietotājs)</label>
        <select name="user_id">
          @foreach($users as $u)
            <option value="{{ $u->id }}"
              @selected(old('user_id', isset($client)? $client->user_id : auth()->id()) == $u->id)>
              {{ $u->name }} ({{ $u->email }})
            </option>
          @endforeach
        </select>
        @error('user_id')<div class="muted">{{ $message }}</div>@enderror
      </div>
    @endcan

      {{-- Tips + Nosaukums/Vārds Uzvārds --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700">Tips</label>
          <select name="type" id="typeSelect"
                  class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="person"  @selected(old('type', $client->type) === 'person')>Fiziska persona</option>
            <option value="company" @selected(old('type', $client->type) === 'company')>Juridiska persona</option>
          </select>
          @error('type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
          <label id="nameLabel" class="block text-sm font-medium text-gray-700">
            Nosaukums / Vārds Uzvārds
          </label>
          <input name="name" id="nameInput" value="{{ old('name', $client->name) }}"
                 placeholder="SIA Demo vai Jānis Bērziņš"
                 class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
          @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
      </div>

      {{-- Reģ. nr./PK + PVN --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
          <label id="regLabel" class="block text-sm font-medium text-gray-700">
            Reģ. nr. / Personas kods
          </label>
          <input name="reg_no" id="regInput" value="{{ old('reg_no', $client->reg_no) }}"
                 placeholder="4010xxxxxxx vai 010101-12345"
                 class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
          @error('reg_no')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div id="vatGroup">
          <label class="block text-sm font-medium text-gray-700">PVN numurs</label>
          <input name="vat_no" id="vatInput" value="{{ old('vat_no', $client->vat_no) }}"
                 placeholder="LV12345678901"
                 class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
          @error('vat_no')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
      </div>

      {{-- Epasts + Tālrunis --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700">E-pasts</label>
          <input name="email" value="{{ old('email', $client->email) }}"
                 class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
          @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Tālrunis</label>
          <input name="phone" value="{{ old('phone', $client->phone) }}"
                 class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
          @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
      </div>

      {{-- Adrese --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">Adrese</label>
        <textarea name="address" rows="3"
                  class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >{{ old('address', $client->address) }}</textarea>
        @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
      </div>

      <div class="flex items-center gap-3">
        <x-ui.button as="button" type="submit" variant="primary">Saglabāt</x-ui.button>
        <x-ui.button href="{{ route('clients.index') }}">Atpakaļ</x-ui.button>
      </div>
    </form>
  </x-ui.card>

  @push('scripts')
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
      nameLabel.textContent = isCompany ? 'Uzņēmuma nosaukums' : 'Vārds, Uzvārds';
      nameInput.placeholder = isCompany ? 'SIA Demo' : 'Jānis Bērziņš';
      regLabel.textContent  = isCompany ? 'Reģistrācijas numurs' : 'Personas kods';
      vatGroup.style.display = isCompany ? '' : 'none';
      if (isCompany) { vatInput.setAttribute('required','required'); }
      else { vatInput.removeAttribute('required'); }
      regInput.setAttribute('required','required');
      nameInput.setAttribute('required','required');
    }
    applyTypeUI();
    typeSelect.addEventListener('change', applyTypeUI);
  </script>
  @endpush
</x-app-layout>
