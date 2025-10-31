<?php


namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    public function before(User $user, string $ability): ?bool {
        if ($user->role === 'admin') return true;
        return null;
    }

    public function viewAny(User $user): bool {
        return true; // lietotājs redzēs vēlāk filtrētus savus rēķinus
    }

    public function view(User $user, Invoice $invoice): bool {
        return optional($invoice->client)->user_id === $user->id;
    }

    public function create(User $user): bool {
        return false;
    }

    public function update(User $user, Invoice $invoice): bool {
        return false; // user nevar labot rēķinus
    }

    public function delete(User $user, Invoice $invoice): bool {
        return false;
    }
}
