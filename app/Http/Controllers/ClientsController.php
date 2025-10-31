<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;



class ClientsController extends Controller
{
    
    public function index()
    {
        $type = request('type'); // 'company' or 'person' or all
        $clients = Client::when(in_array($type, ['company','person']), fn($q) => $q->where('type', $type))
            ->orderBy('name')
            ->paginate(10)
            ->appends(['type' => $type]); // save for paginate()

        return view('clients.index', compact('clients','type'));
    }

    public function create()
    {
        return view('clients.create', ['client' => new Client()]);
    }

    public function store(StoreClientRequest $request)
    {
        $data = $request->validated();
        Client::create($data);
        return redirect()->route('clients.index')->with('status','Klients izveidots!');
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        $data = $request->validated();
        $data['vat_no'] = $data['vat_no'] ?? '-';
        $client->update($data);
        return redirect()->route('clients.index')->with('status','Klients atjaunināts!');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return back()->with('status','Klients dzēsts.');
    }
}
