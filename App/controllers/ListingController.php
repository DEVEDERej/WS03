<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;
use Framework\Authorization;
use App\Controllers\ErrorController;

class ListingController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Display all job listings
     * 
     * @param array $params
     * @return void
     */
    public function index($params = [])
    {
        $listings = $this->db->Query("SELECT * FROM listings ORDER BY created_at DESC")->fetchAll();
        
        loadView('listings/index', ['listings' => $listings]);
    }

    /**
     * Search job listings
     * 
     * @param array $params
     * @return void
     */
    public function search($params = [])
    {
        $keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';
        $location = isset($_GET['location']) ? trim($_GET['location']) : '';

        $query = "SELECT * FROM listings WHERE (title LIKE :keywords OR description LIKE :keywords OR tags LIKE :keywords OR company LIKE :keywords)";

        $queryParams = [
            ':keywords' => "%{$keywords}%"
        ];

        // Add location filter if provided
        if (!empty($location)) {
            $query .= " AND (city LIKE :location OR state LIKE :location)";
            $queryParams[':location'] = "%{$location}%";
        }

        $query .= " ORDER BY created_at DESC";

        $listings = $this->db->query($query, $queryParams)->fetchAll();

        loadView('listings/index', [
            'listings' => $listings,
            'keywords' => $keywords,
            'location' => $location
        ]);
    }

    /**
     * Display job listing creation form
     * 
     * @param array $params
     * @return void
     */
    public function create($params = [])
    {
        loadView('listings/create');
    }

    /**
     * Display single job listing details
     * 
     * @param array $params
     * @return void
     */
    public function show($params = [])
    {
        // Get the listing ID from params
        $id = $params['id'] ?? '';

        // Validate ID
        if (empty($id)) {
            ErrorController::notFound('Listing not found');
            return;
        }

        // Use prepared statement to prevent SQL injection
        $listing = $this->db->Query("SELECT * FROM listings WHERE id = :id", [
            ':id' => $id
        ])->fetch();

        // Check if listing exists
        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        // Load the view with listing data
        loadView('listings/show', ['listing' => $listing]);
    }

    /**
     * Store data in database
     * 
     * @param array $params
     * @return void
     */
    public function store($params = [])
    {
        $allowedFields = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email', 'requirements', 'benefits'];

        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));

        // Get user ID from session
        $newListingData['user_id'] = Session::get('user')['id'];

        // Sanitize data
        $newListingData = array_map('sanitize', $newListingData);

        // Required fields
        $requiredFields = ['title', 'description', 'email', 'city', 'state'];

        $errors = [];

        // Loop through required fields and validate
        foreach ($requiredFields as $field) {
            if (empty($newListingData[$field]) || !Validation::string($newListingData[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        // Check if there are errors
        if (!empty($errors)) {
            // Reload view with errors
            loadView('listings/create', [
                'errors' => $errors,
                'listing' => $newListingData
            ]);
        } else {
            // Submit data to database
            
            // Get field names
            $fields = [];
            
            foreach ($newListingData as $field => $value) {
                $fields[] = $field;
            }
            
            $fields = implode(', ', $fields);
            
            // Get field values
            $values = [];
            
            foreach ($newListingData as $field => $value) {
                // Convert empty strings to null
                if ($value === '') {
                    $newListingData[$field] = null;
                }
                $values[] = ':' . $field;
            }
            
            $values = implode(', ', $values);
            
            $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";
            
            $this->db->query($query, $newListingData);
            
            // Set success flash message
            Session::setFlashMessage('success_message', 'Listing created successfully');
            
            redirect('/listings');
        }
    }

    /**
     * Delete a listing
     * 
     * @param array $params
     * @return void
     */
    public function destroy($params = [])
    {
        $id = $params['id'];
        
        // Check if listing exists
        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", [
            ':id' => $id
        ])->fetch();
        
        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }
        
        // Authorization - Check if user owns the listing
        if (!Authorization::isOwner($listing->user_id)) {
            Session::setFlashMessage('error_message', 'You are not authorized to delete this listing');
            return redirect('/listings/' . $listing->id);
        }
        
        // Delete the listing
        $this->db->query("DELETE FROM listings WHERE id = :id", [
            ':id' => $id
        ]);
        
        // Set success flash message
        Session::setFlashMessage('success_message', 'Listing deleted successfully');
        
        redirect('/listings');
    }

    /**
     * Show edit form for listing
     * 
     * @param array $params
     * @return void
     */
    public function edit($params = [])
    {
        // Get the listing ID from params
        $id = $params['id'] ?? '';

        // Validate ID
        if (empty($id)) {
            ErrorController::notFound('Listing not found');
            return;
        }

        // Use prepared statement to prevent SQL injection
        $listing = $this->db->Query("SELECT * FROM listings WHERE id = :id", [
            ':id' => $id
        ])->fetch();

        // Check if listing exists
        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        // Authorization - Check if user owns the listing
        if (!Authorization::isOwner($listing->user_id)) {
            Session::setFlashMessage('error_message', 'You are not authorized to edit this listing');
            return redirect('/listings/' . $listing->id);
        }

        // Load the view with listing data
        loadView('listings/edit', ['listing' => $listing]);
    }

    /**
     * Update listing
     * 
     * @param array $params
     * @return void
     */
    public function update($params = [])
    {
        // Get the listing ID from params
        $id = $params['id'] ?? '';

        // Validate ID
        if (empty($id)) {
            ErrorController::notFound('Listing not found');
            return;
        }

        // Use prepared statement to prevent SQL injection
        $listing = $this->db->Query("SELECT * FROM listings WHERE id = :id", [
            ':id' => $id
        ])->fetch();

        // Check if listing exists
        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        // Authorization - Check if user owns the listing
        if (!Authorization::isOwner($listing->user_id)) {
            Session::setFlashMessage('error_message', 'You are not authorized to update this listing');
            return redirect('/listings/' . $listing->id);
        }

        $allowedFields = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email', 'requirements', 'benefits'];

        $updatedValues = array_intersect_key($_POST, array_flip($allowedFields));

        // Sanitize data
        $updatedValues = array_map('sanitize', $updatedValues);

        // Required fields
        $requiredFields = ['title', 'description', 'email', 'city', 'state'];

        $errors = [];

        // Loop through required fields and validate
        foreach ($requiredFields as $field) {
            if (empty($updatedValues[$field]) || !Validation::string($updatedValues[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        // Check if there are errors
        if (!empty($errors)) {
            // Reload view with errors
            loadView('listings/edit', [
                'errors' => $errors,
                'listing' => $listing
            ]);
            exit;
        } else {
            // Submit to database
            
            // Build UPDATE query
            $updateFields = [];
            
            foreach (array_keys($updatedValues) as $field) {
                $updateFields[] = "{$field} = :{$field}";
            }
            
            $updateFields = implode(', ', $updateFields);
            
            $query = "UPDATE listings SET {$updateFields} WHERE id = :id";
            
            // Add ID to params
            $updatedValues['id'] = $id;
            
            $this->db->query($query, $updatedValues);
            
            // Set success flash message
            Session::setFlashMessage('success_message', 'Listing updated successfully');
            
            redirect('/listings/' . $id);
        }
    }
}
