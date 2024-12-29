<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\EquipmentType;  // Import EquipmentType model
use App\Models\DataType;      // Import DataType model

class EquipmentTableManager
{
    // Logic for table creation and data insertion
    public function handleEquipmentTable($equipmentName, $equipmentTypeName)
    {
        // Create table
        $this->createEquipmentTable($equipmentName);

        // Insert initial data from `data_types` table
        $this->insertInitialData($equipmentName, $equipmentTypeName);
    }

    private function createEquipmentTable($equipmentName)
    {
        // Create table if it doesn't exist
        $createQuery = "CREATE TABLE IF NOT EXISTS `{$equipmentName}` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `data` VARCHAR(255) DEFAULT NULL,
            `data_type` VARCHAR(50) DEFAULT NULL,
            `byte_number` VARCHAR(255) DEFAULT NULL,
            `bit_number` VARCHAR(255) DEFAULT NULL,
            `final_value` VARCHAR(255) DEFAULT NULL,
            `data_time` DATETIME NOT NULL
        )";

        DB::statement($createQuery);
    }

    private function insertInitialData($equipmentName, $equipmentTypeName)
    {
        // Fetch data from the `data_types` table
        $data = $this->getDataForType($equipmentTypeName);

        foreach ($data as $entry) {
            // Insert data into the created table
            DB::statement(
                "INSERT INTO {$equipmentName} (data, data_type, byte_number, bit_number, data_time) 
                VALUES (?, ?, ?, ?, NOW() )", 
                [$entry['data'], $entry['data_type'], $entry['byte_number'], $entry['bit_number']]
            );
        }
    }

    private function getDataForType($equipmentTypeName)
    {
        // Fetch the equipment type based on the name
        $equipmentType = EquipmentType::where('type', strtoupper($equipmentTypeName))->first();

        if (!$equipmentType) {
            // If the equipment type doesn't exist, return default data
            return $this->getDefaultData();
        }

        // Fetch data types for the equipment type from the `data_types` table
        $dataTypes = DataType::where('equipment_type_id', $equipmentType->id)->get();

        // Return the data in the required format (array)
        return $dataTypes->map(function ($dataType) {
            return [
                'data' => $dataType->data,
                'data_type' => $dataType->data_type,
                'byte_number' => $dataType->byte_number,
                'bit_number' => $dataType->bit_number,
            ];
        })->toArray();
    }

    private function getDefaultData()
    {
        // Default fallback data when equipment type is not found
        return [
            ['data' => 'STS_LR', 'data_value' => '0', 'data_type' => 'integer'],
            ['data' => 'STS_RN', 'data_value' => '0', 'data_type' => 'bytes'],
        ];
    }
}
