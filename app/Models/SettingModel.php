<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'key_name', 'value', 'type', 'group_name', 'description'
    ];

    // Helper to get value by key
    public function getValue($key)
    {
        $row = $this->where('key_name', $key)->first();
        return $row ? $row->value : null;
    }

    // Helper to get settings by group
    public function getGroup($groupName)
    {
        return $this->where('group_name', $groupName)->orderBy('id', 'ASC')->findAll();
    }

    // Helper to update or create setting
    public function updateOrCreate($key, $value, $type = 'text', $group = 'general', $description = '')
    {
        $existing = $this->where('key_name', $key)->first();

        $data = [
            'key_name' => $key,
            'value' => $value,
            'type' => $type,
            'group_name' => $group,
            'description' => $description
        ];

        if ($existing) {
            $data['id'] = $existing->id;
            return $this->update($existing->id, $data);
        } else {
            return $this->insert($data);
        }
    }

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}