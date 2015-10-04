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

    $globalWebUrl = Image::getRootPath(false);
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

    $webUrl = Image::getRootPath(false);
    $formParams['web_url'] = $webUrl;
	$app->requestdata = array_merge($jsonParams, $formParams);

    $jsonmiddleware = new JsonMiddleware();
    $jsonmiddleware->dbConnect();
    $serviceRepo = new ServicesRepo();
    $services = $serviceRepo->getServices(array());
    $viewParameters['services'] = $services['data'];  
    $viewParameters['main_service'] = $services['data'][0];  

    $app->get('/', function () use ($app, $viewParameters) {
        $testimonialRepo = new TestimonialRepo();
        $projectRepo = new ProjectRepo();
        $projectCatRepo = new ProjectCategoryRepo();
        $serviceRepo = new ServicesRepo();

        $testimonials = $testimonialRepo->getTestimonials(array());
        $viewParameters['title'] = 'Home';
        $viewParameters['testimonials'] = $testimonials['data'];

        $viewParameters['page_call'] = 'home';
        $projectCat = $projectCatRepo->getProjectCategories(array());
        $projects = $projectRepo->getProjects(array('limit' => 8));
        $viewParameters['projects'] = $projects['data'];        
        $viewParameters['project_cats'] = $projectCat['data'];        

        $services = $serviceRepo->getServices(array());
        $viewParameters['services'] = array_splice($services['data'], 0, 3);

        $app->render('index.html.twig', $viewParameters);
    })->name('index');

    $app->get('/about/', function () use ($app, $viewParameters) {
        $testimonialRepo = new TestimonialRepo();
        $testimonials = $testimonialRepo->getTestimonials(array());
        $viewParameters['title'] = 'About';

        $viewParameters['testimonials'] = $testimonials['data'];

         $teamRepo = new TeamRepo();
        $teams = $teamRepo->getTeams(array());        
        $viewParameters['teams'] = $teams['data'];

        $app->render('about.html.twig', $viewParameters);
    })->name('index');

    $app->get('/testimonials/', function () use ($app, $viewParameters) {
        $testimonialRepo = new TestimonialRepo();
        $testimonials = $testimonialRepo->getTestimonials(array());
        $viewParameters['title'] = 'Testimonial';
        $viewParameters['testimonials'] = $testimonials['data'];

        $app->render('testimonial.html.twig', $viewParameters);
    })->name('index');

        $app->get('/our-clients/', function () use ($app, $viewParameters) {
        $viewParameters['title'] = 'Our Clients';

        $clientRepo = new ClientRepo();
        $clients = $clientRepo->getClients(array());
        $viewParameters['clients'] = $clients['data'];

        $app->render('clients.html.twig', $viewParameters);
    })->name('index');


    $app->get('/index', function () use ($app, $viewParameters) {
        $testimonialRepo    = new TestimonialRepo();
        $projectRepo        = new ProjectRepo();
        $projectCatRepo     = new ProjectCategoryRepo();

        $testimonials = $testimonialRepo->getTestimonials(array());
        $viewParameters['title'] = 'Home';
        $viewParameters['testimonials'] = $testimonials['data'];


        $projectCat = $projectCatRepo->getProjectCategories(array());
        $projects = $projectRepo->getProjects(array('limit' => '8'));
        $viewParameters['page_call'] = 'home';
        $viewParameters['projects'] = $projects['data'];        
        $viewParameters['project_cats'] = $projectCat['data']; 
        $app->render('index.html.twig', $viewParameters);       
    })->name('index');

    $app->get('/contact-us/', function () use ($app, $viewParameters) {
        $viewParameters['title'] = 'Contact';
        $app->render('contact.html.twig', $viewParameters);
    });

    
    $app->get('/our-projects/' , function () use ($app, $viewParameters){
        $projectRepo = new ProjectRepo();
        $projectCatRepo = new ProjectCategoryRepo();
        $projectCat = $projectCatRepo->getProjectCategories(array());
        $projects = $projectRepo->getProjects(array());
        $viewParameters['page_call'] = 'projects';
        $viewParameters['title'] = 'Our Projects';
        $viewParameters['projects'] = $projects['data'];        
        $viewParameters['project_cats'] = $projectCat['data'];        
        $app->render('projects.html.twig', $viewParameters);
    });

    $app->get('/about/' , function () use ($app, $viewParameters){
        $teamRepo = new TeamRepo();
        $teams = $teamRepo->getTeams(array());
        $viewParameters['title'] = 'About Us';
        $viewParameters['teams'] = $teams['data'];        
        $app->render('about.html.twig', $viewParameters);
    });

    $app->get('/products-services/' , function () use ($app, $viewParameters){
        
        $viewParameters['title'] = 'Services';
        $serviceRepo = new ServicesRepo();
        $services = $serviceRepo->getServices(array());
        $viewParameters['services'] = $services['data'];  
       
        $app->render('services.html.twig', $viewParameters);
    });


    $app->get('/products-services/:id/', function ($id)  use ($app, $viewParameters){
        $viewParameters['title'] = 'Services';
        $serviceRepo = new ServicesRepo();
        $servicesData = $serviceRepo->getServiceDetail($id);

       $viewParameters['service_data'] = $servicesData['service_data'];  
       $viewParameters['services'] = $servicesData['services'];  
//       $viewParameters['service_name'] = $servicesData['service_data']['name'];  
       // echo "<pre>";
       // print_r($viewParameters);
       // die();
       $app->render('single_services.html.twig', $viewParameters);
    });




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

    // Get Client from id    
     $app->get('/client', function() use ($app){

        $new = new ClientRepo();
        $code = $new->getClient($app->requestdata);
        response($code['code'], array('data' => $code['data']));
    });     

// Add Client
     $app->post('/client', function() use ($app){

        $new = new ClientRepo();
        $code = $new->addClient($app->requestdata);
        response($code, array());
    }); 

     // Edit Client
     $app->post('/editclient', function() use ($app){

        $new = new ClientRepo();
        $code = $new->editClient($app->requestdata);
        response($code, array());
    }); 

// Delete Client
     $app->post('/deleteclient', function() use ($app){

        $new = new ClientRepo();
        $code = $new->deleteClient($app->requestdata);
        response($code, array());
    }); 


     $app->post('/client_upload', function() use ($app){
        $image = new Image();
        $file = $_FILES['image'];
        $resp = $image->uploadTmp($file, 'client');
        response($resp['code'], $resp);
    });

     // Get Project Categories    
     $app->get('/projectcategories', function() use ($app){

        $new = new ProjectCategoryRepo();
        $code = $new->getProjectCategories($app->requestdata);
        response($code['code'], array('data' => $code['data']));
    });

    // Get Project Category from id    
     $app->get('/projectcategory', function() use ($app){

        $new = new ProjectCategoryRepo();
        $code = $new->getProjectCategory($app->requestdata);
        response($code['code'], array('data' => $code['data']));
    });     

// Add Project Category
     $app->post('/projectcategory', function() use ($app){

        $new = new ProjectCategoryRepo();
        $code = $new->addProjectCategory($app->requestdata);
        response($code, array());
    }); 

     // Edit Project Category
     $app->post('/editprojectcategory', function() use ($app){

        $new = new ProjectCategoryRepo();
        $code = $new->editProjectCategory($app->requestdata);
        response($code, array());
    }); 

// Delete Project Category
     $app->post('/deleteprojectcategory', function() use ($app){

        $new = new ProjectCategoryRepo();
        $code = $new->deleteProjectCategory($app->requestdata);
        response($code, array());
    }); 

    // Add Testimonial
      $app->post('/testimonial', function() use ($app){

        $new = new TestimonialRepo();
        $code = $new->addTestimonial($app->requestdata);
        response($code, array());
    }); 

      // Edit Testimonial
      $app->post('/edittestimonial', function() use ($app){

        $new = new TestimonialRepo();
        $code = $new->editTestimonial($app->requestdata);
        response($code, array());
    }); 

// Get Testimonials
     $app->get('/testimonials', function() use ($app){

        $new = new TestimonialRepo();
        $code = $new->getTestimonials($app->requestdata);
        response($code['code'], array('data' => $code['data']));
    });  

     // Get Testimonial from id
     $app->get('/singletestimonial', function() use ($app){

        $new = new TestimonialRepo();
        $code = $new->getTestimonial($app->requestdata);
        response($code['code'], array('data' => $code['data']));
    });

// Delete Testimonial
     $app->post('/deletetestimonial', function() use ($app){

        $new = new TestimonialRepo();
        $code = $new->deleteTestimonial($app->requestdata);
        response($code, array());
    }); 

    $app->post('/testimonial_upload', function() use ($app){
        $image = new Image();
        $file = $_FILES['image'];
        $resp = $image->uploadTmp($file, 'testimonial');
        response($resp['code'], $resp);
    }); 

// Get Queries    
     $app->get('/queries', function() use ($app){

        $new = new QueryRepo();
        $code = $new->getQueries($app->requestdata);
        response($code['code'], array('data' => $code['data'], 'total_pages' => $code['total_pages']));
    });   

    // Get Query with id    
     $app->get('/singlequery', function() use ($app){

        $new = new QueryRepo();
        $code = $new->getQuery($app->requestdata);
        response($code['code'], array('data' => $code['data']));
    });    

// Add Query
     $app->post('/query', function() use ($app){

        $new = new QueryRepo();
        $code = $new->addQuery($app->requestdata);
        response($code, array());
    }); 

// Delete Query
     $app->post('/deletequery', function() use ($app){

        $new = new QueryRepo();
        $code = $new->deleteQuery($app->requestdata);
        response($code, array());
    }); 

     // Edit Query
      $app->post('/editquery', function() use ($app){

        $new = new QueryRepo();
        $code = $new->editQuery($app->requestdata);
        response($code, array());
    }); 

// Get Teams    
     $app->get('/teams', function() use ($app){

        $new = new TeamRepo();
        $code = $new->getTeams($app->requestdata);
        response($code['code'], array('data' => $code['data']));
    });   

    // Get Team with id    
     $app->get('/singleteam', function() use ($app){

        $new = new TeamRepo();
        $code = $new->getTeam($app->requestdata);
        response($code['code'], array('data' => $code['data']));
    });    

// Add Team
     $app->post('/team', function() use ($app){

        $new = new TeamRepo();
        $code = $new->addTeam($app->requestdata);
        response($code, array());
    }); 

// Delete Team
     $app->post('/deleteteam', function() use ($app){

        $new = new TeamRepo();
        $code = $new->deleteTeam($app->requestdata);
        response($code, array());
    }); 

     // Edit Team
      $app->post('/editteam', function() use ($app){

        $new = new TeamRepo();
        $code = $new->editTeam($app->requestdata);
        response($code, array());
    }); 

    $app->post('/team_upload', function() use ($app){
        $image = new Image();
        $file = $_FILES['image'];
        $resp = $image->uploadTmp($file, 'team');
        response($resp['code'], $resp);
    }); 

    // Get Projects    
     $app->get('/projects', function() use ($app){

        $new = new ProjectRepo();
        $code = $new->getProjects($app->requestdata);
        response($code['code'], array('data' => $code['data']));
    });   

    // Get Project with id    
     $app->get('/singleproject', function() use ($app){

        $new = new ProjectRepo();
        $code = $new->getProject($app->requestdata);
        response($code['code'], array('data' => $code['data']));
    }); 

    
// Add Project
     $app->post('/project', function() use ($app){

        $new = new ProjectRepo();
        $code = $new->addProject($app->requestdata);
        response($code['code'], array('data' => $code['data']));
    }); 

// Delete Project
     $app->post('/deleteproject', function() use ($app){

        $new = new ProjectRepo();
        $code = $new->deleteProject($app->requestdata);
        response($code, array());
    }); 

     // Edit Project
      $app->post('/editproject', function() use ($app){

        $new = new ProjectRepo();
        $code = $new->editProject($app->requestdata);
        response($code, array());
    });

    // Add Project Video
     $app->post('/projectimage', function() use ($app){

        $new = new ProjectRepo();
        $code = $new->addProjectImages($app->requestdata);
        response($code, array());
    });

    $app->post('/project_upload', function() use ($app){
        $image = new Image();
        $file = $_FILES['image'];
        $resp = $image->uploadTmp($file, 'project');
        response($resp['code'], $resp);
    }); 

    // Get News    
     $app->get('/news', function() use ($app){

        $new = new NewsRepo();
        
        $code = $new->getNews($app->requestdata);
    
        if(isset($_REQUEST['limit']))
            $code['data'] = array_splice($code['data'], 0, $_REQUEST['limit']);

        response($code['code'], array('data' => $code['data']));
    });   

    // Get News with id    
     $app->get('/singlenews', function() use ($app){

        $new = new NewsRepo();
        $code = $new->getNew($app->requestdata);
        response($code['code'], array('data' => $code['data']));
    });    

// Add News
     $app->post('/news', function() use ($app){

        $new = new NewsRepo();
        $code = $new->addNews($app->requestdata);
        response($code, array());
    }); 

// Delete News
     $app->post('/deletenews', function() use ($app){

        $new = new NewsRepo();
        $code = $new->deleteNews($app->requestdata);
        response($code, array());
    }); 

     // Edit News
      $app->post('/editnews', function() use ($app){

        $new = new NewsRepo();
        $code = $new->editNews($app->requestdata);
        response($code, array());
    }); 

    $app->post('/news_upload', function() use ($app){
        $image = new Image();
        $file = $_FILES['image'];
        $resp = $image->uploadTmp($file, 'news');
        response($resp['code'], $resp);
    });

        // Get Services    
     $app->get('/services', function() use ($app){

        $service = new ServicesRepo();
        $code = $service->getServices($app->requestdata);
        response($code['code'], array('data' => $code['data']));
    });   

    // Get Service with id    
     $app->get('/singleservice', function() use ($app){

        $service = new ServicesRepo();
        $code = $service->getService($app->requestdata);
        response($code['code'], array('data' => $code['data']));
    });    

// Add Services
     $app->post('/services', function() use ($app){

        $service = new ServicesRepo();
        $code = $service->addServices($app->requestdata);
        response($code, array());
    }); 

// Delete Services
     $app->post('/deleteservices', function() use ($app){

        $service = new ServicesRepo();
        $code = $service->deleteServices($app->requestdata);
        response($code, array());
    }); 

     // Edit Services
      $app->post('/editservices', function() use ($app){

        $service = new ServicesRepo();
        $code = $service->editServices($app->requestdata);
        response($code, array());
    }); 

    $app->post('/service_upload', function() use ($app){
        $image = new Image();
        $file = $_FILES['image'];
        $resp = $image->uploadTmp($file, 'services');
        response($resp['code'], $resp);
    }); 

    // Get Service with slug    
     $app->get('/serviceslug', function() use ($app){

        $service = new ServicesRepo();
        $code = $service->getServiceId($app->requestdata);
        response($code['code'], array('data' => $code['data']));
    });

});






$app->run();