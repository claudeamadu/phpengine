<?php

class ContactImporter
{
    private $filePath;
    private $batchSize = 1000; // Adjust batch size as needed

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function importContacts()
    {
        $fileExtension = pathinfo($this->filePath, PATHINFO_EXTENSION);

        switch ($fileExtension) {
            case 'xlsx':
                return $this->importFromExcel();
            case 'csv':
                return $this->importFromCSVInBatches();
            default:
                return false;
        }
    }

    private function importFromExcel()
    {
        return false;
    }

    private function importFromCSVInBatches()
    {
        $contacts = [];
        if (($handle = fopen($this->filePath, "r")) !== false) {
            while (($rowData = fgetcsv($handle, 1000, ",")) !== false) {
                $contacts[] = [
                    'first_name' => $rowData[0],
                    'last_name' => $rowData[1],
                    'contact' => formatPhoneNumber($rowData[2]),
                    'email' => $rowData[3]
                ];

                // If batch size reached, process the batch
                if (count($contacts) >= $this->batchSize) {
                    $this->processBatch($contacts);
                    $contacts = []; // Reset batch
                }
            }

            // Process remaining contacts if any
            if (!empty($contacts)) {
                $this->processBatch($contacts);
            } 

            fclose($handle);
        }

        return true; // Successfully imported
    }

    private function processBatch($contacts)
    {
        // Placeholder for batch processing
        foreach ($contacts as $contact) {
            if (count($contact) >= 3) {
                Database::insert('contacts', [
                    'first_name' => $contact['first_name'],
                    'last_name' => $contact['last_name'],
                    'contact' => formatPhoneNumber(str_replace(['(', ')', '-','_','+', ' '], '', $contact[2])),
                    'email' => $contact['email'],
                    'group_id' => requestData('group_id'),
                    'user_id' => User::get()->id
                ]);
            }
        }
    }
    
    public function importFromCSV() {
        $data = [];
        if (($handle = fopen($this->filePath, "r")) !== false) {
            while (($rowData = fgetcsv($handle, 1000, ",")) !== false) {
                $data[] = $rowData;
            }
            fclose($handle);
        }

        return $this->processData($data);
    }

    private function processData($data) {
        $contacts = [];

        foreach ($data as $row) {
            if (count($row) >= 3) {
                $contacts[] = [
                    'first_name' => $row[0],
                    'last_name' => $row[1],
                    'contact' => formatPhoneNumber(str_replace(['(', ')', '-','_','+', ' '], '', $row[2])),
                    'email' => $row[3],
                    'birthday' => $row[4]
                ];
            }
        }

        return $contacts;
    }
}
