
<?php

$router->get('/', 'HomeController@index');
$router->get('/listings', 'ListingController@index');
$router->get('/listings/search', 'ListingController@search');
$router->get('/listings/create', 'ListingController@create', ['auth']);
$router->get('/listings/edit/{id}', 'ListingController@edit', ['auth']);
$router->get('/listings/{id}', 'ListingController@show');

$router->post('/listings', 'ListingController@store', ['auth']);
$router->put('/listings/{id}', 'ListingController@update', ['auth']);

$router->delete('/listings/{id}', 'ListingController@destroy', ['auth']);

// Authentication
$router->get('/register', 'UserController@create', ['guest']);
$router->post('/register', 'UserController@store', ['guest']);
$router->get('/login', 'UserController@login', ['guest']);
$router->post('/login', 'UserController@authenticate', ['guest']);
$router->post('/logout', 'UserController@logout', ['auth']);

