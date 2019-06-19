<?php

namespace Xtrategie\Clients\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
      'name', 'email', 'document', 'phone', 'ind_status', 'metadata'
    ];

    protected $casts = [
        'metadata' => 'json'
    ];

    const statuses = [
        'ACTIVED' => 'success',
        'BLOCKED' => 'danger'
    ];

    const statusesLabel = [
        'ACTIVED' => 'Ativo',
        'BLOCKED' => 'Bloqueado'
    ];
}
