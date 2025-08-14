<?php

namespace App\Policies;

use App\Models\Incident;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IncidentPolicy
{
  
public function view(User $user, Incident $incident)
{
    return $user->id === $incident->user_id;
}

public function update(User $user, Incident $incident)
{
    return $user->id === $incident->user_id;
}

public function delete(User $user, Incident $incident)
{
    return $user->id === $incident->user_id;
}

}
