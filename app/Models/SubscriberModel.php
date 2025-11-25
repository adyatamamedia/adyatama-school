<?php

namespace App\Models;

use CodeIgniter\Model;

class SubscriberModel extends Model
{
    protected $table            = 'subscribers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = ['email', 'name', 'is_active', 'unsubscribed_at'];

    protected $useTimestamps = false; // Schema uses subscribed_at default current_timestamp
    protected $createdField  = 'subscribed_at';
    protected $updatedField  = '';
}
