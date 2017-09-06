<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
	echo "hello";
    // return view('welcome');
});


$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function($api){
	$api->get('materials','App\Http\Controllers\DataController@materials');
});

$api->version('v1', function($api){
	$api->post('description','App\Http\Controllers\DataController@description');
});



$api->version('v1', function($api){
	$api->post('premium','App\Http\Controllers\DataController@premium');
});

$api->version('v1', function($api){
	$api->post('duty','App\Http\Controllers\DataController@duty');
});

$api->version('v1', function($api){
	$api->post('net','App\Http\Controllers\DataController@net');
});

$api->version('v1', function($api){
	$api->post('levy','App\Http\Controllers\DataController@levy');
});

$api->version('v1', function($api){
	$api->post('total','App\Http\Controllers\DataController@total');
});


//Registration and login routes
$api->version('v1', function($api){
	$api->post('login','App\Http\Controllers\RegistrationController@login');
});


//Saving data into database
$api->version('v1', function($api){
	$api->post('saveData','App\Http\Controllers\DataController@saveData');
});

//Getting data from DB based on logged in user
$api->version('v1', function($api){
	$api->post('transactions','App\Http\Controllers\DataController@transactions');
});



//Getting quotation data
$api->version('v1', function($api){
	$api->get('getQuotations','App\Http\Controllers\DataController@getQuotations');
});

//Adding a new customer
$api->version('v1', function($api){
	$api->post('addCustomers','App\Http\Controllers\DataController@addCustomers');
});

//getting data of registered customers
$api->version('v1', function($api){
	$api->get('getCustomers','App\Http\Controllers\DataController@getCustomers');
});

// Searching for a customer
$api->version('v1', function($api){
	$api->post('searchUser','App\Http\Controllers\DataController@searchUser');
});

//Get countries for ports
$api->version('v1', function($api){
	$api->get('countries','App\Http\Controllers\ShippingController@countries');
});
// Get ports for the selected countries
$api->version('v1', function($api){
	$api->post('ports','App\Http\Controllers\ShippingController@ports');
});

// Saving shipping data
$api->version('v1', function($api){
	$api->post('saveData','App\Http\Controllers\ShippingController@saveData');
});
//get custmer data
$api->version('v1', function($api){
	$api->post('viewCustomer','App\Http\Controllers\ShippingController@viewCustomer');
});

// update customers
$api->version('v1', function($api){
	$api->post('updateCustomers','App\Http\Controllers\ShippingController@updateCustomers');
});

//adding a quote
$api->version('v1', function($api){
	$api->post('addQuote','App\Http\Controllers\QuoteController@addQuote');
});
// getting quotes
$api->version('v1', function($api){
	$api->get('getQuotes','App\Http\Controllers\QuoteController@getQuotes');
});
// get Quote
$api->version('v1', function($api){
	$api->post('getQuote','App\Http\Controllers\QuoteController@getQuote');
});

// Adding payment
$api->version('v1', function($api){
	$api->post('addPayment','App\Http\Controllers\QuoteController@addPayment');
});

// view payments
$api->version('v1', function($api){
	$api->get('viewPayments','App\Http\Controllers\QuoteController@viewPayments');
});
