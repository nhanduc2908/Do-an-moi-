<?php

namespace App\Services\Module08_DatabaseSecurity;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class DatabaseEncryptionService
{
    protected $encryptedColumns = [];

    public function encryptColumn($table, $column, $value)
    {
        return Crypt::encryptString($value);
    }

    public function decryptColumn($table, $column, $value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    public function enableColumnEncryption($table, $column)
    {
        $this->encryptedColumns[$table][$column] = true;
        
        // Migrate existing data
        DB::table($table)->chunk(100, function($rows) use ($table, $column) {
            foreach ($rows as $row) {
                if ($row->$column && !$this->isEncrypted($row->$column)) {
                    $encrypted = $this->encryptColumn($table, $column, $row->$column);
                    DB::table($table)
                        ->where('id', $row->id)
                        ->update([$column => $encrypted]);
                }
            }
        });
    }

    protected function isEncrypted($value)
    {
        return str_starts_with($value, 'eyJ');
    }

    public function searchEncryptedColumn($table, $column, $searchTerm)
    {
        // Can't search encrypted columns directly
        // Need to decrypt and search in application layer
        $results = [];
        DB::table($table)->chunk(100, function($rows) use ($column, $searchTerm, &$results) {
            foreach ($rows as $row) {
                $decrypted = $this->decryptColumn($table, $column, $row->$column);
                if (stripos($decrypted, $searchTerm) !== false) {
                    $results[] = $row;
                }
            }
        });
        
        return $results;
    }
}