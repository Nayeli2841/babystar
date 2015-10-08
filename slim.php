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


    $app->get('/news/', function () use ($app, $viewParameters) {
        $newsRepo = new NewsRepo();
        $news = $newsRepo->getNews(array());
        $viewParameters['title'] = 'News';
        $viewParameters['news']  = $news['data'];

        $app->render('news.html.twig', $viewParameters);
    })->name('index');

    $app->get('/production/' , function () use ($app, $viewParameters){
        $viewParameters['title'] = 'Production';
        $app->render('production.html.twig', $viewParameters);
    });

    $app->get('/about/about-al-rajhi/' , function () use ($app, $viewParameters){
        $viewParameters['title'] = 'Al-Rajhi';
        $teamRepo = new TeamRepo();
        $teams = $teamRepo->getTeams(array());
        $viewParameters['teams'] = $teams['data'];
        $app->render('about-al-rajhi.html.twig', $viewParameters);
    });

    $app->get('/about/our-team/' , function () use ($app, $viewParameters){
        $viewParameters['title'] = 'Our Team';
        $teamRepo = new TeamRepo();
        $teams = $teamRepo->getTeams(array());
        $viewParameters['teams'] = $teams['data'];
        $app->render('about-team.html.twig', $viewParameters);
    });

    $app->get('/about/about-romeo-interiors/' , function () use ($app, $viewParameters){
        $viewParameters['title'] = 'About Romeo';
        $teamRepo = new TeamRepo();
        $teams = $teamRepo->getTeams(array());
        $viewParameters['teams'] = $teams['data'];
        $app->render('about-romeo-interiors.html.twig', $viewParameters);
    });

    $app->get('/about/mission-values/' , function () use ($app, $viewParameters){
        $viewParameters['title'] = 'Mission Values';
        $teamRepo = new TeamRepo();
        $teams = $teamRepo->getTeams(array());
        $viewParameters['teams'] = $teams['data'];
        $app->render('mission-values.html.twig', $viewParameters);
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
        $code = $new->getQueries($app->requestdata);
        response(200, array('data' => $code['data'], 'total_pages' => $code['total_pages']));
        
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
        $code = $new->QueryDetail($app->requestdata);
        response(200, array('data' => $code));
    });

});






$app->run();