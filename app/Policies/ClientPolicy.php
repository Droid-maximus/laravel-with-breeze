<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    public function before(User $user, string $ability): ?bool {
        // Admins drīkst visu
        if ($user->role === 'admin') return true;
        return null;
    }

    public function viewAny(User $user): bool {
        return true; // user redzēs tikai savu (filtrēt nepieciešami kontrolierī)
    }

    public function view(User $user, Client $client): bool {
        return $client->user_id === $user->id;
    }

    public function create(User $user): bool {
        return $user->role === 'admin';
    }

    public function update(User $user, Client $client): bool {
        return $client->user_id === $user->id; // var labot savu profilu
    }

    public function delete(User $user, Client $client): bool {
        return false; // parastais lietotājs nedrīkst dzēst neko (=
    }
}
