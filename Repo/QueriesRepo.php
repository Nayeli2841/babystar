<?php
class QueriesRepo
{

	// Add Vendor Images
	public function sendAdminQuery($data)
	{
		$date_created = date("Y-m-d H:i:s");
		$values = array('name' => $data['name'], 'phone' => $data['phone'], 'email' => $data['email'], 'subject' => $data['subject'], 'message' => $data['message'], 'date_created' => $date_created);
		$query = $GLOBALS['con']->insertInto('queries', $values)->execute();

		$loginRepo = new LoginRepo();
		$admindata = $loginRepo->getAdminData(1);

		//"coursemadt@gmail.com"
		$to = $admindata['username'];
		$subject = "Contact query";

		$message = "
		<html>
		<head>
		<title>New query</title>
		</head>
		<body>
		Hello,  New business is added. Please review the details. 
		<table cellspacing='20'>
		<tr>
		<th>Subject: ".$data['subject']."</th>
		<th>Name: ".$data['name']."</th>
		<th>Email: ".$data['email']."</th>
		<th>Phone: ".$data['phone']."</th>
		</tr>
		<tr>
		<td colspan='4'>".$data['message']."</td>
		</tr>
		</table>
		</body>
		</html>
		";

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: <'.$admindata['username'].'>' . "\r\n";

		mail($admindata['username'],$subject,$message,$headers);
		return 200;
	}

	public function addSubscriber($data)
	{

		$count = $GLOBALS['con']->from('subscribers')->where('email',$data['email'])->count();
		if($count > 0)
		{
			if($data['status'] == 'active')
				$consent = 1;
			else
				$consent = 0;

			$values = array("consent" => $consent);
			$query = $GLOBALS['con']->update('subscribers')->set($values)->where('email', $data['email'])->execute();
		}
		else
		{
			if($data['status'] == 'active')
			{
				$consent = '1';
			}
			else if($data['status'] == 'deactive')
			{
				$consent = '0';
			}

			$date_created = date("Y-m-d H:i:s");
			$values = array('first_name' => $data['first_name'], 'last_name' => $data['last_name'], 'email' => $data['email'],'date_created' => $date_created, "status" => $data['status'],'consent' => $consent);
			$query = $GLOBALS['con']->insertInto('subscribers', $values)->execute();

			$msg = "<html>
					<head>
					  <title>Confirmation Email</title>
					</head>
					<body>
						<p>Please click on the link below to confirm your subscription.</p>

						<a href='http://www.tamildirectoryapp.com/beta/confirm.php?email=".$data['email']."'>Click to confirm</a> 
					    
					</body>
					</html>";
			$to = $data['email'];
			$subject = "Confirmation";

			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

			// More headers
			//$headers .= 'From: <'. .'>' . "\r\n";

			$email = mail($to,$subject,$msg,$headers);	

			return 200;
		}
	}

	public function deleteQuery($request)
	{
		$query = $GLOBALS['con']->deleteFrom('queries')->where('id', $request['id'])->execute();
		$sql   = $GLOBALS['con']->deleteFrom('services')->where('query_id', $request['id'])->execute();
		return 200;
	}

	public function deleteSubscriber($request)
	{
		$query = $GLOBALS['con']->deleteFrom('subscribers')->where('id', $request['id'])->execute();
		return 200;
	}

	public function getQueries($request)
	{
		$limit = 15;
		$total_pages = 0;
		if(!isset($request['page']))
			$page = 0;
		else
			$page = $request['page'];

		$offset = $page * $limit;

		$resp = array('code' => 200, 'data' => array());
		$count = $GLOBALS['con']->from('queries')->count();
	
		$total_pages = ceil($count / $limit) ;	
	
		$queries = $GLOBALS['con']->from('queries')->limit($limit)->offset($offset);
		
		if(!empty($queries))
		{
			foreach ($queries as $key => $query) {
				$query = array_map('utf8_encode', $query);
				$resp['data'][] = $query;
			}
		}

		$resp['total_pages'] = $total_pages;

		return $resp;
		//return array('code' => '200','data' => $resp['data'], 'total_pages' => $resp['total_pages']);
	}

	public function getQueryDetail($request)
	{
		$data = $this->QueryDetail($request);

		$queryDataStr = '<table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Parent Name</th>
                    <td id="parent_name">Ingreed Castro</td>
                  </tr>
                  <tr>
                    <th>Child Name</th>
                    <td id="child_name">Madeline Sanchez</td>
                  </tr>
                  <tr>
                    <th>Date Of Birth</th>
                    <td id="dob"></td>
                  </tr>
                  <tr>
                    <th>Age</th>
                    <td id="age"></td>
                  </tr>
                  <tr id="filename">
                    <th>File Name</th>
                    <td id="file_name">12444.emlx</td>
                  </tr>
                  <tr>
                    <th>Branch Office</th>
                    <td id="branch_office">Condesa</td>
                  </tr>
                  <tr>
                    <th>Strat Time</th>
                    <td id="start_time">10:00:00</td>
                  </tr>
                  <tr>
                    <th>End Time</th>
                    <td id="end_time">16:00:00</td>
                  </tr>
                  <tr>
                    <th>Email</th>
                    <td id="email">pamcastro.77@gmail.com</td>
                  </tr>
                  <tr>
                    <th>Phone</th>
                    <td id="phone">5527443787</td>
                  </tr>
                  <tr>
                    <th>Refer By</th>
                    <td id="refer_by">Recomendación</td>
                  </tr>
                  <tr>
                    <th>Date Created</th>
                    <td id="date_created">2014-09-30 00:00:00</td>
                  </tr>
                  <tr>
                    <th style="vertical-align: top;">Services</th>
                    <td id="services">Guarderia<br>Lactantes<br>Web Cams<br>Estimulacion<br></td>
                  </tr>

                </thead>
                <tbody id="detail">

                </tbody>
              </table>';

              echo $queryDataStr;		
	}

	public function queryDetail($request)
	{
		$resp = array();
		$data = array();
		$query = $GLOBALS['con']->from('queries')->where('id', $request['id']);
		$sql = $GLOBALS['con']->from('services')->where('query_id', $request['id']);
		if(!empty($sql))
		{
			foreach ($sql as $key => $services) 
			{
				$services = array_map('utf8_encode', $services);
				$data[] = $services;
			}
		}
		if(!empty($query))
		{
			foreach ($query as $key => $queries) 
			{
				$queries = array_map('utf8_encode', $queries);
				$resp = $queries;
			}
		}

		return array('code' => 200, 'data' => $resp, 'services' => $data);
	}

	// public function getSubscribers($request)
	// {
	// 	$sortBy = 'id';
	// 	$orderBy = 'asc';

	// 	if(!isset($request['status']))
	// 		$status = 'active';
	// 	else
	// 		$status = $request['status'];

	// 	if(isset($request['sort_by']) && !empty($request['sort_by']) && isset($request['sort_order']) && !empty($request['sort_order'] )) 
	// 	{
	// 		$sortBy = $request['sort_by'];
	// 		$orderBy = $request['sort_order'];
	// 	}

	// 	if(isset($request['search']) && !empty($request['search']))
	// 		$key = '%'.$request['search'].'%';

	// 	$limit = 15;
	// 	$total_pages = 0;
	// 	if(!isset($request['page']))
	// 		$page = 0;
	// 	else
	// 		$page = $request['page'];

	// 	$offset = $page * $limit;

	// 	$resp = array('code' => 200, 'data' => array());

	// 	if(!isset($key))
	// 	{
	// 		$count = $GLOBALS['con']->from('subscribers')->where('status', $status)->count();

	// 	}
	// 	else
	// 	{
	// 		$rawSql = "SELECT COUNT(*) as cid FROM subscribers where status = '".$status."' AND  (email like '".$key."')";
	// 		$stmt = $GLOBALS['pdo']->query($rawSql);
	// 		$count = $stmt->fetchColumn();
	// 	}


	// 	$total_pages = ceil($count / $limit) ;			
	// 	if(isset($key))
	// 	{
	// 		$rawSql = "SELECT * FROM subscribers where  status = '".$status."' AND  (email like '".$key."')";
	// 		$stmt = $GLOBALS['pdo']->query($rawSql);
	// 		$queries = $stmt->fetchAll(PDO::FETCH_ASSOC);
	// 	}
	// 	else
	// 	{
	// 		$queries = $GLOBALS['con']->from('subscribers')->orderBy($sortBy." ".$orderBy)->where('status', $status)->limit($limit)->offset($offset);
	// 	}


	// 	$allVendors = array();
	// 	if(!empty($queries))
	// 	{
	// 		foreach ($queries as $key => $query) {
	// 			$resp['data'][] = $query;
	// 		}
	// 	}

	// 	$resp['total_pages'] = $total_pages;
	// 	return $resp;
	// }	

	// public function editSubscriber($request)
	// {
	// 	$response = 400;

	// 	if(!empty($request['email']))
	// 	{
	// 		$count = $GLOBALS['con']->from('subscribers')->where('email',$request['email'])->where('id != ?',$request['id'])->count();
	// 		if($count > 0)
	// 		{
	// 			$response = 400;
	// 		}
	// 		else
	// 		{
	// 			$date_created = date("Y-m-d H:i:s");
	// 			$values = array('email' => $request['email'],'date_created' => $date_created, "first_name" => $request['first_name'], "last_name" =>  $request['last_name'] );
	// 			$query = $GLOBALS['con']->update('subscribers', $values, $request['id'])->execute();
	// 			$response = 200;
	// 		}
	// 	}
	// 	return $response;
	// }

	// public function getSingleSubscriber($request)
	// {
	// 	$query = $GLOBALS['con']->from('subscribers')->where('id',$reuqeust['id']);
	// 	$subscriber = array();

	// 		foreach($query as $items)
	//     	{
	// 			$subscriber[] = $items;

	// 		}

	// 		return array('code' => '200','data' => $subscriber);
	// }

	// public function deactiveSubscriber($request)
	// {
	// 	$response = 400;
	// 	$values = array('status' => $request['status']);
	// 	$query = $GLOBALS['con']->update('subscribers', $values, $request['id'])->execute();
	// 	$response = 200;
	// 	return $response;
	// }

	// public function subscriberExists($data)
	// {
	// 	$count = $GLOBALS['con']->from('subscribers')->where('email',$data['email'])->count();
	// 	if($count == 0)
	// 		return 200;
	// 	else
	// 		return 401;
	// }

	// public function verifyEmail($request)
	// {
	// 	$count = $GLOBALS['con']->from('subscribers')->where('email',$request['email'])->count();
	// 	if($count > 0)
	// 	{
	// 		$values = array('verified' => '1');
	// 		$sql = $GLOBALS['con']->update('subscribers')->set($values)->where('email', $request['email'])->execute();			
	// 		$response = 200;
	// 	}
	// 	else
	// 	{
	// 		$response = 400;
	// 	}
	// 	return $response;
	// }
}