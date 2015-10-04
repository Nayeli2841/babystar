<?php

class JsonMiddleware extends \Slim\Middleware
{

    public function call()
    {
        $currentRoute = $this->app->request()->getPathInfo();

        if(strpos($currentRoute, '/api') )
        {
            // Force response headers to JSON
            $this->app->response->headers->set(
                'Content-Type',
                'application/json'
            );

        }
         
        $method = strtolower($this->app->request->getMethod());
        $mediaType = $this->app->request->getMediaType();
         
        if (in_array(
            $method,
            array('post', 'put', 'patch', 'delete', 'get')
        )) {
             
            if (empty($mediaType) || $mediaType !== 'application/json') {
                // Return generic error
                // Connect with database
                $this->dbConnect();
                
            }
            else
            {
                // Connect with database
                $this->dbConnect();
            }
        }


        $this->next->call();
    }

    public function dbConnect()
    {
        if($_SERVER['HTTP_HOST'] == 'localhost')
        {
            $database = 'babystar';
            $user = 'root';
            $password = '';
        }
        else
        {
            $database = 'iqbal89_babystar';
            $user = 'iqbal89_babystar';
            $password = '=Veu9%deoh5$';
        }

        $GLOBALS['pdo'] = new PDO("mysql:dbname=". $database, $user, $password);
        $GLOBALS['con'] = new FluentPDO($GLOBALS['pdo']);
        $GLOBALS['abslolute_path'] = '';
        $GLOBALS['con']->debug = true;
    }


}

