<?php

namespace App\Service\Utils;

class ProductImportHelper {
    /**
     * @var int
     */
    private $LINE_LENGTH = 1000;
    /**
     * @var int
     */
    private $MINIMUM_IMPORT_STOCK_COUNT = 10;
    /**
     * @var int
     */
    private $MINIMUM_IMPORT_COST_AMOUNT = 5;
    /**
     * @var int
     */
    private $MAXIMUM_IMPORT_COST_AMOUNT = 1000;


    /**
     * @var int
     */
    private $valid_row_count;
    /**
     * @var int
     */
    private $invalid_row_count;
    /**
     * @var array
     */
    private $header_labels;
    /**
     * @var string
     */
    private $file_encoding;
    /**
     * @var array
     */
    private $ignored_rows;

    /**
     * @param void
     * 
     * @return int
     */
    public function getValidRowCount(): int
    {
        return $this->valid_row_count;
    }

    /**
     * @param void
     * 
     * @return int
     */
    public function getInvalidRowCount(): int
    {
        return $this->invalid_row_count;
    }

    /**
     * @param void
     * 
     * @return array
     */
    public function getHeaderLabels(): array
    {
        return $this->header_labels;
    }

    /**
     * @param void
     * 
     * @return string
     */
    public function getFileEncoding(): string
    {
        return $this->file_encoding;
    }

    /**
     * @param void
     * 
     * @return array
     */
    public function getIgnoredRows(): array
    {
        return $this->ignored_rows;
    }

    /**
     * @param array
     * 
     * @return boolean
     */
    private function isImportable($data)
    {
        return (            
            ( 
                intval($data['product_stock']) >= $this->MINIMUM_IMPORT_STOCK_COUNT 
            )
            &&
            (
                (floatval($data['product_price']) >= $this->MINIMUM_IMPORT_COST_AMOUNT) 
                && (floatval($data['product_price']) <= $this->MAXIMUM_IMPORT_COST_AMOUNT)
            )             
        );
    }

    /**
     * @param array
     * 
     * @return boolean
     */
    private function isDiscontinued($data)
    {
        return strtolower($data['product_discontinued']) === "yes";
    }

    /**
     * @param string
     * 
     * @return string
     */
    private function distillString($string): string
    {
        $quotes = [
            '"',
            "'"
        ];

        $replacements = [
            '\"',
            "\'"
        ];

        return str_replace(
            $quotes, 
            $replacements, 
            trim($string)
        );
    }

     /**
     * @param string $csvfile
     * 
     * @return array
     */
    public function processCSV(string $csvfile): array
    {
        if ($csvfile) {
            printf("You entered a CSV file: %s \n", $csvfile);

            // Iterators for displaying result count later
            $this->valid_row_count = 1;
            $this->invalid_row_count = 0;

            $iterator = 0;

            $payload = [];
            $this->ignored_rows = [];
            if (($handle = fopen($csvfile, "r")) !== false) {

                while (($data = fgetcsv($handle, $this->LINE_LENGTH, ",")) !== false) {
                    $num = count($data);
                   
                    // Ensure this is not a blank line
                    if(($num > 0) && ($iterator === 0)){
                        // Get the column labels
                        $this->header_labels = $data;
                        // Get encoding info
                        $this->file_encoding = mb_detect_encoding(
                            $data[0], 
                            "auto", 
                            true
                        );
                    } else {
                                        
                        if($data[0] !== null){
                            printf(
                                "Processing product code %s in row %d...\n\n", 
                                $data[0],
                                $iterator
                            );

                            $blank_placeholder = "--";
                            $zero_placeholder = 0;

                            // Dump into a temporary buffer for checking                  
                            // Use the product code as key
                            $buffer_payload[$data[0]] = [
                                'product_code'         => $data[0],
                                'product_name'         => $this->distillString($data[1]) ?? $blank_placeholder,
                                'product_desc'         => $this->distillString($data[2]) ?? $blank_placeholder,
                                'product_stock'        => isset($data[3]) && is_numeric($data[3]) ? intval($data[3]) : $zero_placeholder,
                                'product_price'        => isset($data[4]) && is_numeric($data[4]) ? floatval($data[4]) : $zero_placeholder,
                                'product_discontinued' => isset($data[5]) ? strtolower($data[5]) : $blank_placeholder
                            ];

                            $_tmp_payload = $buffer_payload[$data[0]];
                            
                            // Only import items that pass
                            // DISCONTINUED items will be imported, 
                            if($this->isImportable($_tmp_payload) || $this->isDiscontinued($_tmp_payload)){
                                $this->valid_row_count++;
                                $payload[] = $_tmp_payload;
                            } else {                                                                
                                $this->invalid_row_count++;
                                $this->ignored_rows[] = $_tmp_payload;
                            }
                        }                
                    } 
                    // Update counter
                    $iterator++;
                }
                fclose($handle);
            }
        }
        return $payload;
    }
}