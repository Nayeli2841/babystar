<?php

require 'vendor/autoload.php';

$app = new \Slim\Slim(array(
    "debug" => true,
    'view' => new \Slim\Views\Twig()
));

$view = $app->view();
$view->parserOptions = array(
    'debug' => true,
    'cache' => false //dirname(__FILE__) . '/cache'
);

$view->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
);

session_cache_limiter(false);
session_start();

/*
* HTTP STATUS CODES
* 200 ok
* 400 Bad Request
* 401 Unauthorized
* 409 Conflict
*/


function response($code, $dataAry)
{
    if($code != 200)
    {
        $dataAry['status'] = 'error';        
    }
    else
    {
        $dataAry['status'] = 'success'; 
    }
    $response = $GLOBALS['app']->response();
    $response['Content-Type'] = 'application/json';
    $response->status($code);
    $response->body(json_encode($dataAry));
}

    $globalWebUrl = '';
    $viewParameters = array('web_url' => $globalWebUrl) ;



	$jsonParams = array();
	$formParams = $app->request->params();
    $data = $app->request->getBody();

	if(!empty($data))
	{
	    $decodeJsonParams = json_decode($data, TRUE);
        if(is_array($decodeJsonParams))
            $jsonParams = $decodeJsonParams;
	}

    $webUrl = '';
    $formParams['web_url'] = $webUrl;
	$app->requestdata = array_merge($jsonParams, $formParams);

    $jsonmiddleware = new JsonMiddleware();
    $jsonmiddleware->dbConnect();

   
    $app->get('/admin/' , function () use ($app, $viewParameters){
        echo "<script>window.location='login.php'</script>";
    });



    $app->notFound(function () use ($app, $viewParameters) {
        $viewParameters['title'] = 'Not Found';        
        $app->render('404.html.twig', $viewParameters);
    });


/*
* JSON middleware
* It Always make sure, response is in the form of JSON
* We also initiate database connection here
*/

$app->add(new JsonMiddleware('/api'));


/*
* Grouped routes
*/

$app->group('/api', function () use ($app) {

    // Login
    $app->post('/login' , function () use ($app){

        $new = new LoginRepo();
        $code = $new->login($app->requestdata);
        response($code, $code['data']);
    }); 
    

    $app->get('/admindata', function() use ($app){
        $new = new LoginRepo();
        $code = $new->getAdminData();
        response(200, array('data' => $code));
    });

    $app->post('/editadmindata', function() use ($app){
        $new = new LoginRepo();
        $code = $new->editAdminData($app->requestdata);
        response($code, array());
        
    });

    $app->post('/editadminpassword', function() use ($app){
        $new = new LoginRepo();
        $code = $new->editadminpassword($app->requestdata);
        response($code, array());
        
    });     

    $app->get('/reporting', function() use ($app){
        $new = new ReportingRepo();
        $data = $new->getReporting($app->requestdata);
        response(200, array('data' => $data));
    });

    $app->post('/export', function() use ($app){
        $new = new QueriesRepo();
        $data = $new->createExport($app->requestdata);
        response(200, array('data' => $data));
    });

    $app->get('/logout' , function () use ($app){
        session_destroy();
        response(200, array());
    }); 

     // Get Clients    
     $app->get('/clients', function() use ($app){

        $new = new ClientRepo();
        $code = $new->getClients($app->requestdata);
        response($code['code'], array('data' => $code['data']));
    });

    $app->get('/queries', function() use ($app){
        $new = new QueriesRepo();
        $data = $new->getQueries($app->requestdata);
        response(200, array('data' => $data['data'],  'total_pages' => $data['total_pages']));    
    });   

    $app->post('/mail', function() use ($app){
        $new = new MessageRepo();
        $data = $new->sendMessage($app->requestdata);
        response(200, array('status' => 'success'));    
    });


    $app->get('/get_query_data', function() use ($app){
        $new = new QueriesRepo();
        $data = $new->QueryDetail($app->requestdata);
        response(200, array( 'data' => $data['data'], 'services' => $data['services']));
    });

    $app->post('/query', function() use ($app){
        $new = new QueriesRepo();
        $status = $new->saveQuery($app->requestdata);
        response(200, array('status' => $status));
    });

    // Delete Query
    $app->post('/deletequery', function() use ($app){

        $new = new QueriesRepo();
        $code = $new->deleteQuery($app->requestdata);
        response($code, array());
    });

    // Delete Query
    $app->get('/querydetail', function() use ($app){

        $new = new QueriesRepo();
        $code = $new->getQueryDetail($app->requestdata);
//        response(200, array('data' => $code));
    });

    $app->post('/csv_upload', function() use ($app){
        $fileupload = new FileUpload();
        $file = $_FILES['csv'];
        $resp = $fileupload->uploadTmp($file, 'temp');
        response($resp['code'], $resp);
    });     

    $app->post('/import_csv', function() use ($app){
        $importRepo = new ImportExportRepo();
        $resp = $importRepo->importCsv($app->requestdata);
        response($resp['code'], $resp);
    });     

});






$app->run();