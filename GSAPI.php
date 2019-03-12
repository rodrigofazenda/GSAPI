<?php

/**
 * Feed API Based on a Google Sheet
 * 
 * https://gsapi.kelvins.cc/sheet/{GOOGLE_SHEET_ID}/{TABLE_NAME}/{ROW_INDEX}
 * 
 * GOOGLE_SHEET_ID: The last path of Google Sheet Link, something like this -> 1aVGHEZk0C4lv_hJOTYI_OOFWKuqgTH2pcScXEio6XuQ/
 * TABLE_NAME: name of the table like User, Profile, Rates
 * ROW_INDEX: Index of the row you want
 * 
 * @author Kelvin Biffi (https://github.com/kelvinbiffi) 
 * Creation Date: March 12, 2019 05:45 AM
 * 
 * Requirements: To Consume this API, The Google Sheet Must be Entirely published as a Web Page, otherwise the API Will Not Wor Correctly
 */
class GSAPI {

    // PROPERTIES

    /**
     * @var stdClass $sheet as Sheet Object
     */
    private $sheet;

    /** 
     * @var String $sheetID
     */
    private $sheetID = "";

    /** 
     * @var String $table
     */
    private $table = "";

    /** 
     * @var Integer $row
     */
    private $row = 0;


    // GETTERS AND SETTERS

    public function addTable($table, $entry) {
        var_dump($table);
        var_dump($entry);
        $this->sheet->{$table} = $entry;
    }

    public function getSheet() {
        return $this->sheet;
    }

    public function getSheetID() {
        return $this->sheetID;
    }

    public function setSheetID($value) {
        $this->sheetID = $value;
    }

    public function getTable() {
        return $this->table;
    }

    public function setTable($value) {
        $this->table = $value;
    }

    public function getRow() {
        return $this->row;
    }

    public function setRow($value) {
        $this->row = $value;
    }

    // CONSTRUCTOR

    /**
     * Construct function
     */
    public function __construct($sheetID = "", $table = "", $row = "") {
        $this->sheet = new stdClass;
        $this->setSheetID($sheetID);
        $this->setTable($table);
        $this->setRow($row);

        $this->getSheetInfo(1);
    }

    /**
     * Handle infos given by path link
     */
    private function getSheetInfo($tableId) {

        $data = $this->getTableInfo($tableId);
        
        // Chck if the return is a valid JSON Object
        if ($this->isValidJSON($data)) {
            $data = json_decode($data);
            $this->handleFeed($data->feed);


            if ($this->getTable() != "") {

            } else {
                $this->sendError("NO");
            }

            // $this->getSheetInfo($tableId++);
        } else {
            $this->sendError("Google Sheet Returned a non JSON Object, please check if the sheet id is correct and if it is published on the web indeed.");
        }
    }

    /**
     * Verify Google Sheet was given and if it exists return the first table
     * 
     * @return String
     */
    private function getTableInfo($tableId) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "https://spreadsheets.google.com/feeds/list/" . $this->getSheetID() . "/" . $tableId . "/public/values?alt=json"
        ]);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }

    /**
     * Handle the $feed data
     * 
     * @param stdClass $feed
     */
    private function handleFeed($feed) {
        var_dump($feed);
        $this->addTable($feed->title->{"\$t"}, $this->formatEntry($feed->entry));
    }

    private function formatEntry($entry) {
        return $entry;
    }

    /**
     * Valid JSON Object Format
     * 
     * @param String $JSONString
     */
    private function isValidJSON($JSONString) {
        @json_decode($JSONString);
        return (json_last_error() === JSON_ERROR_NONE);
    }

    /**
     * Return a Bad Request
     * 
     * @param String $message
     */
    private function sendError($message) {
        http_response_code(400);
        echo "{\"message\": \"" . $message . "\", \"error\": true}";exit;
    }
}