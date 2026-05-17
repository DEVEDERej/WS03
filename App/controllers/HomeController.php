<?php

namespace App\Controllers;

use Framework\Database;

class HomeController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Display home page with recent job listings
     * 
     * @param array $params
     * @return void
     */
    public function index($params = [])
    {
        $listings = $this->db->Query("SELECT * FROM listings ORDER BY created_at DESC LIMIT 6")->fetchAll();
        
        loadView('home', ['listings' => $listings]);
    }
}
