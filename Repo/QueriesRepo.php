<?php
/** Include PHPExcel */
ini_set('max_execution_time', 900);
ini_set('memory_limit', '-1');

require_once dirname(__FILE__) . '/../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
require_once dirname(__FILE__) . '/../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';

class QueriesRepo
{

	public function createExport($request)
    {
		$date = DateTime::createFromFormat('d/m/Y', $request['start_date']);
		$startDate = $date->format('Y-m-d');

		$date = DateTime::createFromFormat('d/m/Y', $request['end_date']);
		$endDate = $date->format('Y-m-d');


		$objPHPExcel = new PHPExcel();

		$sql = "Select id from queries where DATE(date_created) between '".$startDate."'  AND  '".$endDate."'";
		$sth = $GLOBALS['pdo']->query($sql);
		$sth->setFetchMode(PDO::FETCH_ASSOC);

		$row = 1;
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'DB Id')
									  ->setCellValue('B'.$row, 'File Name')
									  ->setCellValue('C'.$row, 'Parent Name')
									  ->setCellValue('D'.$row, 'Child Name')
									  ->setCellValue('E'.$row, 'Date of birth')
									  ->setCellValue('F'.$row, 'Start Time')
									  ->setCellValue('G'.$row, 'End Time')
									  ->setCellValue('H'.$row, 'Email')
									  ->setCellValue('I'.$row, 'Phone')
									  ->setCellValue('J'.$row, 'Refer By')
									  ->setCellValue('K'.$row, 'Services')
									  ->setCellValue('L'.$row, 'Date');

		$row++;
		while ($rec = $sth->fetch()) 
		{
			$queryDetail = $this->queryDetail(array('id' => $rec['id']));

		    $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $queryDetail['data']['id'])
										  ->setCellValue('B'.$row, $queryDetail['data']['filename'])
										  ->setCellValue('C'.$row, $queryDetail['data']['parent_name'])
										  ->setCellValue('D'.$row, $queryDetail['data']['child_name'])
										  ->setCellValue('E'.$row, $queryDetail['data']['dob_formatted'])
										  ->setCellValue('F'.$row, $queryDetail['data']['start_time'])
										  ->setCellValue('G'.$row, $queryDetail['data']['end_time'])
										  ->setCellValue('H'.$row, $queryDetail['data']['email'])
										  ->setCellValue('I'.$row, $queryDetail['data']['phone'])
										  ->setCellValue('J'.$row, $queryDetail['data']['refer_by'])
										  ->setCellValue('K'.$row, $queryDetail['data']['all_services'])
										  ->setCellValue('L'.$row, $queryDetail['data']['date_created_formatted']);

//		                                  ->setCellValue('D'.$row, PHPExcel_Shared_Date::stringToExcel($rec['date_of_birth']))
		 //   $objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);
		 //   $objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getNumberFormat()->setFormatCode('£#,##0.00');
		    $row++;
		}		


		$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
		//$writer->save('export.csv');

		header('Content-Type: application/csv');
		header("Content-Disposition: attachment; filename=\"export.csv\"");
		header("Cache-Control: max-age=0");
		$writer->save("php://output");		
    	die();
    }

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
	
		$queries = $GLOBALS['con']->from('queries')->orderBy("date_created DESC")->limit($limit)->offset($offset);
		
		if(!empty($queries))
		{
			foreach ($queries as $key => $query) {
				$query = $this->queryDetail(array('id' => $query['id']));				
				if(!empty($query))
				{
					$resp['data'][] = $query['data'];
				}
				// $query = array_map('utf8_encode', $query);
			}
		}

		$resp['total_pages'] = $total_pages;

		return $resp;
		//return array('code' => '200','data' => $resp['data'], 'total_pages' => $resp['total_pages']);
	}

	public function getQueryDetail($request)
	{
		$data = $this->QueryDetail($request);
		$data1 = $data['data'];

		$dob = strtotime($data1['dob']);
		$dob = date('j M Y',$dob);

		$date_created = strtotime($data1['date_created']);
		$date_created = date('j M Y',$date_created);

		$age = date_diff(date_create($data1['dob']), date_create('now'));
		$age = $age->format("%Y Year, %M Months, %d Days");
		$fileHtml = '';
		if($data1['import'] == 1)
        {
        	$fileHtml = '<tr id="filename">
        <th>File Name</th>
        <td id="file_name"><a href = "email/'.$data1['filename'].'" target="_blank" >'.$data1['filename'].'</td>
      	</tr>';
      }

		$queryDataStr = '<table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Parent Name</th>
                    <td id="parent_name">'.$data1['parent_name'].'</td>
                  </tr>
                  <tr>
                    <th>Child Name</th>
                    <td id="child_name">'.$data1['child_name'].'</td>
                  </tr>
                  <tr>
                    <th>Date Of Birth</th>
                    <td id="dob">'.$dob.'</td>
                  </tr>
                  <tr>
                    <th>Age</th>
                    <td id="age">'.$age.'</td>
                  </tr>

	                  '.$fileHtml.'
                  <tr>
                    <th>Branch Office</th>
                    <td id="branch_office">'.$data1['branch_office'].'</td>
                  </tr>
                  <tr>
                    <th>Strat Time</th>
                    <td id="start_time">'.$data1['start_time'].'</td>
                  </tr>
                  <tr>
                    <th>End Time</th>
                    <td id="end_time">'.$data1['end_time'].'</td>
                  </tr>
                  <tr>
                    <th>Email</th>
                    <td id="email">'.$data1['email'].'</td>
                  </tr>
                  <tr>
                    <th>Phone</th>
                    <td id="phone">'.$data1['phone'].'</td>
                  </tr>
                  <tr>
                    <th>Refer By</th>
                    <td id="refer_by">'.$data1['refer_by'].'</td>
                  </tr>
                  <tr>
                    <th>Date Created</th>
                    <td id="date_created">'.$date_created.'<tr>
                  </tr>
                  </tr>
                  <tr>
                    <th style="vertical-align: top;">Services</th>
                    <td id="services">Guarderia<br>Lactantes<br>Web Cams<br>Estimulacion<br></td>
                  </tr>

                </thead>
                <tbody id="detail">

                </tbody>
              </table>';

              if(empty($request['return']))
              {
	              echo $queryDataStr;              	
              }
              else
              {
              	return $queryDataStr;
              }

	}

	public function queryDetail($request)
	{
		$resp = array();
		$data = array();
		$query = $GLOBALS['con']->from('queries')->where('id', $request['id']);
		$sql = $GLOBALS['con']->from('services')->where('query_id', $request['id']);

		$serviceArr = array();
		if(!empty($sql))
		{
			foreach ($sql as $key => $services) 
			{
				$services = array_map('utf8_encode', $services);
				$serviceArr[] = $services['service'];
				$data[] = $services;
			}
		}

		$allServices = implode(',', $serviceArr);


		if(!empty($query))
		{
			foreach ($query as $key => $queries) 
			{
				if(!empty($queries['dob']))
					$queries['dob_formatted'] = date('d F Y', strtotime($queries['dob']));

				if(!empty($queries['start_time']))
					$queries['start_time'] = date('H:i', strtotime($queries['start_time']));

				if(!empty($queries['end_time']))
					$queries['end_time'] = date('H:i', strtotime($queries['end_time']));

				if(!empty($queries['date_created']))
					$queries['date_created_formatted'] = date('d F Y H:i', strtotime($queries['date_created']));

				$queries['all_services'] = $allServices;
				$queries = array_map('utf8_encode', $queries);
				$resp = $queries;
			}
		}

		return array('code' => 200, 'data' => $resp, 'services' => $data);
	}

	public function getDob($date, $years, $months, $days)
	{
		$dob = date('Y-m-d', mktime(0, 0, 0, $months, $days, $years));
		return $dob;
	}


	public function addServices($queryId, $services)
	{
		$deleteService = $GLOBALS['con']->deleteFrom('services')->where('query_id', $queryId)->execute();
		if(!empty($services))
		{
			foreach ($services as $key => $service) 
			{
				$values = array('service' => $service, 'query_id' => $queryId);
				$query = $GLOBALS['con']->insertInto('services', $values)->execute();
			}
		}	
	}

	public function saveQuery($request)
	{
		if($request['branch_office'] == 'other')
			$request['branch_office'] = $request['branch_office_other'];

		if($request['refer_by'] == 'other')
			$request['refer_by'] = $request['refery_by_other'];

		$dateCreated = date('Y-m-d H:i:s');

		$date = DateTime::createFromFormat('d/m/Y', $request['dob']);
		$dob = $date->format('Y-m-d');

		$values = array('filename' => '',
						'parent_name' => $request['parent_name'],
						'child_name' => $request['child_name'], 
						'dob' => $dob,
						'branch_office' => $request['branch_office'], 
						'start_time' => $request['start_time'],
						'end_time' => $request['end_time'],
						'email' => $request['email'],
						'phone' => $request['phone'],
						'refer_by' => $request['refer_by'],
						'import' => 0,
						'date_created' => $dateCreated,
						);

		if(!isset($request['services']))
			$request['services'] = array();


		$services = implode(',', $request['services']);
		$values['services'] = $services;

		if(!empty($request['id']))
		{
			unset($values['date_created']);

			$queryId = $request['id'];
			$query = $GLOBALS['con']->update('queries', $values, $request['id'])->execute();
		}
		else
		{
			$queryId = $GLOBALS['con']->insertInto('queries', $values)->execute();

			// send email to admin
			$this->sendMailAdmin($queryId);

			// send email to admin
			$this->sendMailUser($request['email']);

		}

		// add services
		$this->addServices($queryId, $request['services']);

		return 'success';
	}

	public function sendMailAdmin($queryId)
	{
		$request = array('id' => $queryId, 'return' => 1);
		$queryData = $this->getQueryDetail($request);
		$to = "jasonbourne501@gmail.com, manchag@hotmail.com";
		$subject = "New Download";

		$message = "
		<html>
		<head>
		<title>New Download</title>
		</head>
		<body>".$queryData."</body>
		</html>
		";

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: <jasonbourne501@gmail.com>';

		mail($to,$subject,$message,$headers);
	}

	public function sendMailUser($to)
	{
		$subject = "Saludos!";
		$txt = file_get_contents(str_replace('Repo', '', __DIR__.'/admin/mail.html'));
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
		$headers .= "From: jasonbourne501@gmail.com";
		mail($to,$subject,$txt,$headers);
	}
}