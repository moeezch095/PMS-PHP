<?php
require_once 'BaseModel.php';

class Owner extends BaseModel {
    protected $table = 'owners'; // Table name

    public function __construct($db) {
        parent::__construct($db);
    }
}
