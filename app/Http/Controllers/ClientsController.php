<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;



class ClientsController extends Controller
{

        public function __construct()
    {
        // Šī rinda sasaista Policy ar visām resource metodēm (index, show, edit, delete utt.)
        $this->authorizeResource(Client::class, 'client');
    }
    
    public function index(Request $request)
    {
        if (auth()->user()->role === 'admin') {
            $clients = Client::query()
                ->when($request->type, fn($q, $type) => $q->where('type', $type))
                ->orderBy('id','desc')
                ->paginate(15);
        } else {
            // parastais lietotājs redz tikai sevi (savu Client profilu)
            $clients = Client::where('user_id', auth()->id())
                ->orderBy('id','desc')
                ->paginate(15);
        }

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        $users = auth()->user()->role === 'admin' ? \App\Models\User::orderBy('name')->get() : collect();

        return view('clients.create', compact('users'));
    }

public function store(StoreClientRequest $request)
{
    $data = $request->validated();
    // parasts lietotājs – ignorē jebkuru “user_id” un piesaista sevi
    if (auth()->user()->role !== 'admin') {
        $data['user_id'] = auth()->id();
    }
    Client::create($data);

    return redirect()->route('clients.index')->with('status','Klients izveidots.');
}

    public function edit(Client $client)
    {
    $users = auth()->user()->role === 'admin' ? \App\Models\User::orderBy('name')->get() : collect();

    return view('clients.edit', compact('client','users'));
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        $data = $request->validated();
        // parasts lietotājs – nedrīkst mainīt īpašnieku
        if (auth()->user()->role !== 'admin') {
            unset($data['user_id']);
        }
        $client->update($data);
        return redirect()->route('clients.index')->with('status','Klients atjaunots.');
    }


    public function destroy(Client $client)
    {
        $client->delete();
        return back()->with('status','Klients dzēsts.');
    }
}
