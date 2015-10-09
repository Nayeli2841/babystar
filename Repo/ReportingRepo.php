<?php
class ReportingRepo{

	public function getReporting($request)
	{
//		echo strtotime(date('2012-02-30'));die();

		$finalData = array();
		$yearsArr = range(2011, date('Y'));
		if(!empty($yearsArr))
		{
			foreach ($yearsArr as $key => $year) 
			{
				$day = new DateTime('first day of this month');
				$monthStart =  date('m-d', strtotime($day->format('Y-m-d')));
				$curDay = date('m-d');
				$monthStart = $year.'-'.$monthStart;
				$curDay = $year.'-'.$curDay;

				$sql = "SELECT  DATE(date_created) date, COUNT(DISTINCT id) downloads FROM queries where YEAR(date_created)='".$year."' AND DATE(date_created) between '".$monthStart."' AND  '".$curDay."' GROUP   BY  DATE(date_created) ";
				$sth = $GLOBALS['pdo']->query($sql);
				$sth->setFetchMode(PDO::FETCH_ASSOC);
				$finalData[$key]['name'] = $year;
				while($row = $sth->fetch()) 
				{
					$datePart = date('m-d', strtotime($row['date']));
					$datePart = strtotime('2012-' . $datePart) * 1000;
					$finalData[$key]['data'][] = array($datePart, (int) $row['downloads'], $row['date']);
				}
			}
		}
	
		// current month
		$day = new DateTime('first day of this month');
		$toLowerDate =  date('Y-m-d', strtotime($day->format('Y-m-d')));
		$toUpperDate =  date('Y-m-d');
		
		$curStartMonth = $this->getFormattedDate($toLowerDate);
		$curTodayMonth = $this->getFormattedDate($toUpperDate);

		$day = new DateTime('first day of last month');
		$fromLowerDate =  date('Y-m-d', strtotime($day->format('Y-m-d')));
		$fromUpperDate =  date('Y-m-d', strtotime("-1 month"));

		$lastMonthStart = $this->getFormattedDate($fromLowerDate);
		$lastMonthToday = $this->getFormattedDate($fromUpperDate);

		// $fromLowerDate = '2012-01-01';
		// $fromUpperDate = '2012-12-01';
		// $toLowerDate = '2013-01-01';
		// $toUpperDate = '2014-12-01';

		$curMonthcomparison = $this->getDownloadComparison($fromLowerDate, $fromUpperDate, $toLowerDate, $toUpperDate);
		$monthlyBranchComparison = $this->getDownloadComparisonByType($fromLowerDate, $fromUpperDate, $toLowerDate, $toUpperDate, 'branch_office');
		$monthlyReferbyComparison = $this->getDownloadComparisonByType($fromLowerDate, $fromUpperDate, $toLowerDate, $toUpperDate, 'refer_by');

		$firstDayOfYear =  date('Y').'-01-01';
		$otherBranches = $this->getOtherByType($firstDayOfYear, date('Y-m-d'), 'branch_office');
		$otherReferBy = $this->getOtherByType($firstDayOfYear, date('Y-m-d'), 'refer_by');
		$otherServices = $this->getOtherByType($firstDayOfYear, date('Y-m-d'), 'service');//$toLowerDate
		$monthlyBrnachServicesComparison = $this->getComparisonByBranchServices($firstDayOfYear, date('Y-m-d'), '', '');


		// current month
		$day = new DateTime('first day of this month');
		$toLowerDate =  date('Y-m-d', strtotime($day->format('Y-m-d')));
		$toUpperDate =  date('Y-m-d');

		// current year
 		$fromUpperDate =  date('Y-m-d', strtotime("-1 year"));
		$day = new DateTime($fromUpperDate);
		$day =  $day->modify('first day of this month');
		$fromLowerDate = $day->format('Y-m-d');
		$lastYearStart = $this->getFormattedDate($fromLowerDate);
		$lastYearToday = $this->getFormattedDate($fromUpperDate);

		$yearcomparison = $this->getDownloadComparison($fromLowerDate, $fromUpperDate, $toLowerDate, $toUpperDate);
		$yearlyBranchComparison = $this->getDownloadComparisonByType($fromLowerDate, $fromUpperDate, $toLowerDate, $toUpperDate, 'branch_office');
		$yearlyReferbyComparison = $this->getDownloadComparisonByType($fromLowerDate, $fromUpperDate, $toLowerDate, $toUpperDate, 'refer_by');


		return array('chart' => $finalData,
					 'cur_month' => $curMonthcomparison, 
					 'last_year' => $yearcomparison, 
					 'cur_month_branch' => $monthlyBranchComparison,
					 'cur_year_branch' => $yearlyBranchComparison,					 
					 'cur_month_referby' => $monthlyReferbyComparison,
					 'cur_year_referby' => $yearlyReferbyComparison,
					 'cur_from' => $curStartMonth,
					 'cur_to' => $curTodayMonth,
					 'last_from' => $lastMonthStart,
					 'last_to' => $lastMonthToday,
					 'last_year_from' => $lastYearStart,
					 'last_year_to' => $lastYearToday,
					 'other_branches' => $otherBranches,
					 'other_refer_by' => $otherReferBy,
					 'other_services' => $otherServices,
					 'services_comparison' => $monthlyBrnachServicesComparison,
					 );
		// print_r($yearsArr);
	}

	public function getComparisonByBranchServices($fromLowerDate, $fromUpperDate, $toLowerDate, $toUpperDate)
	{
		$previousMonthBranches = array();
		$currentMonthBranches = array();		
		$sql = "SELECT distinct branch_office from queries as q where DATE(q.date_created) between '".$fromLowerDate."' AND  '".$fromUpperDate."'  AND (branch_office = 'Escandon' || branch_office = 'San Jeronimo' || branch_office = 'San Angel') group by q.branch_office";
		$sth = $GLOBALS['pdo']->query($sql);
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		while ($row = $sth->fetch()) {
			$sql2 = "SELECT count(s.service) as service_count, s.service from queries as q, services as s where q.id = s. query_id AND q.branch_office = '".$row['branch_office']."' AND DATE(q.date_created) between '".$fromLowerDate."' AND  '".$fromUpperDate."' group by s.service";
			$sth2 = $GLOBALS['pdo']->query($sql2);
			$sth2->setFetchMode(PDO::FETCH_ASSOC);
			while ($row2 = $sth2->fetch()) 
			{
				if(!isset($previousMonthBranches[$row['branch_office']]))
					$previousMonthBranches[$row['branch_office']] = array();
				$previousMonthBranches[$row['branch_office']][$row2['service']] = $row2['service_count'];
			}
		}


		// $sql = "SELECT distinct branch_office from queries as q where DATE(q.date_created) between '".$toLowerDate."' AND  '".$toUpperDate."' group by q.branch_office";
		// $sth = $GLOBALS['pdo']->query($sql);
		// $sth->setFetchMode(PDO::FETCH_ASSOC);
		// while ($row = $sth->fetch()) {
		// 	$sql2 = "SELECT count(s.service) as service_count, s.service from queries as q, services as s where q.id = s. query_id AND q.branch_office = '".$row['branch_office']."' AND DATE(q.date_created) between '".$toLowerDate."' AND  '".$toUpperDate."' group by s.service";
		// 	$sth2 = $GLOBALS['pdo']->query($sql2);
		// 	$sth2->setFetchMode(PDO::FETCH_ASSOC);
		// 	while ($row2 = $sth2->fetch()) 
		// 	{
		// 		if(!isset($currentMonthBranches[$row['branch_office']]))
		// 			$currentMonthBranches[$row['branch_office']] = array();
		// 		$currentMonthBranches[$row['branch_office']][$row2['service']] = $row2['service_count'];
		// 	}
		// }


		// if(!empty($currentMonthBranches))
		// {
		// 	foreach ($currentMonthBranches as $singleBranch => $currentMonthBranch) {
		// 		if(!array_key_exists($singleBranch, $previousMonthBranches))
		// 		{
		// 			$previousMonthBranches[$singleBranch] = array();
		// 		}

		// 		if(!empty($currentMonthBranch))
		// 		{
		// 			foreach ($currentMonthBranch as $singleService => $value) {
		// 				if(!isset($previousMonthBranch[$singleBranch][$singleService]))
		// 					$previousMonthBranch[$singleBranch][$singleService] = 0;
		// 			}
		// 		}				
		// 	}
		// }

		// if(!empty($previousMonthBranches))
		// {
		// 	foreach ($previousMonthBranches as $singleBranch => $previousMonthBranch) {
		// 		if(!isset($previousMonthBranche[$singleBranch]))
		// 		{
		// 			$currentMonthBranches[$singleBranch] = array();
		// 		}
		// 		if(!empty($previousMonthBranch))
		// 		{
		// 			foreach ($previousMonthBranch as $singleService => $value) {
		// 				if(!isset($currentMonthBranches[$singleBranch][$singleService]))
		// 					$currentMonthBranches[$singleBranch][$singleService] = 0;
		// 			}
		// 		}
		// 	}
		// }

		// echo "<pre>";
		// print_r($previousMonthBranches);

		$finalData = array();
		if(!empty($previousMonthBranches))
		{
			$key = 1;
			foreach ($previousMonthBranches as $branch => $services) {
				$finalData[$branch]  = array();
				if(!empty($services))
				{
					foreach ($services as $service => $counts) 
					{
						$finalData[$branch][] = array('name' => utf8_encode($service), 'y' => (int) $counts);
					}
				}
				
				++$key;

			}
		}

		return $finalData;

	}


	public function getOtherByType($fromDate, $toDate, $type)
	{
		$finalData = array();
		if($type == 'branch_office')
			$sql = "SELECT ".$type.", COUNT(DISTINCT id) downloads FROM queries where DATE(date_created) between '".$fromDate."'  AND  '".$toDate."' AND (branch_office != 'Escandon' AND branch_office != 'San Jeronimo' AND branch_office != 'San Angel') group by ".$type;
		else if($type == 'refer_by')
			$sql = "SELECT ".$type.", COUNT(DISTINCT id) downloads FROM queries where DATE(date_created) between '".$fromDate."'  AND  '".$toDate."' AND (refer_by != 'Google' AND refer_by != 'Recomendación' AND refer_by != 'Youtube' AND refer_by != 'Bing' AND refer_by != 'Facebook' AND refer_by != 'Publicidad exterior' AND refer_by != 'Otro - especificar:') group by ".$type;
		else if($type == 'service')
		{
			$sql = "Select s.* from services as s, queries as q where (s.service != 'Estimulación Temprana' && s.service != 'Inglés' && s.service != 'Guarderia' && s.service != 'Guarderia Express' && s.service != 'Lactantes' && s.service != 'Maternal') AND q.id = s.query_id AND DATE(date_created) between '".$fromDate."'  AND  '".$toDate."'   group by s.service ";
		}

		$sth = $GLOBALS['pdo']->query($sql);
		$sth->setFetchMode(PDO::FETCH_ASSOC);

		while ($row = $sth->fetch()) 
		{
			// if($type == 'services')
			// {
			// 	echo "<pre>";
			// 	print_r($row);			
			// }

			if(isset($row[$type]))
			{
				if(!empty(trim($row[$type])))
					$finalData[] = utf8_encode($row[$type]);				
			}

		}



		return $finalData;

	}

	public function getFormattedDate($date)
	{
		return date('d F Y', strtotime($date));
	}

	public function getDownloadComparison($fromLowerDate, $fromUpperDate, $toLowerDate, $toUpperDate)
	{
		$sql = "SELECT COUNT(DISTINCT id) downloads FROM queries where DATE(date_created) between '".$fromLowerDate."' AND  '".$fromUpperDate."'";
		$sth = $GLOBALS['pdo']->query($sql);
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$lastMonthDownload = $sth->fetch();

		$sql = "SELECT COUNT(DISTINCT id) downloads FROM queries where DATE(date_created) between '".$toLowerDate."' AND  '".$toUpperDate."'";
		$sth = $GLOBALS['pdo']->query($sql);
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$curMonthDownload = $sth->fetch();

		$currentMonthstring = date('F-Y', strtotime($fromUpperDate));
		$lastMonthstring = date('F-Y', strtotime($toUpperDate));


		if(!isset($lastMonthDownload['downloads']))
			$lastMonthDownload['downloads'] = 0;
		if(!isset($curMonthDownload['downloads']))
			$curMonthDownload['downloads'] = 0;

		$percentage = $this->getPercentage($curMonthDownload['downloads'], $lastMonthDownload['downloads']);

		return array('current_downloads' => $curMonthDownload['downloads'],
		 			 'last_downloads' => $lastMonthDownload['downloads'],
		 			 'percentage' => $percentage,
		 			 'current' => 'Total downloads in '.$currentMonthstring,
		 			 'last' => 'Total downloads in '.$lastMonthstring,
		 			 );
	}

	public function getDownloadComparisonByType($fromLowerDate, $fromUpperDate, $toLowerDate, $toUpperDate, $type)
	{
		$finalData = array();
		$finalBranches1 = array();
		$finalBranches2 = array();		
		$sql = "SELECT ".$type.", COUNT(DISTINCT id) downloads FROM queries where DATE(date_created) between '".$fromLowerDate."' AND  '".$fromUpperDate."' group by ".$type;
		$sth = $GLOBALS['pdo']->query($sql);
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		while($row = $sth->fetch())
		{
			$finalBranches1[$row[$type]] = $row['downloads'];
		}


		$sql = "SELECT ".$type.", COUNT(DISTINCT id) downloads FROM queries where DATE(date_created) between '".$toLowerDate."' AND  '".$toUpperDate."'  group by ".$type;
		$sth = $GLOBALS['pdo']->query($sql);
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		while($row = $sth->fetch())
		{
			$finalBranches2[$row[$type]] = $row['downloads'];
		}

		$currentMonthstring = date('F-Y', strtotime($fromUpperDate));
		$lastMonthstring = date('F-Y', strtotime($toUpperDate));

		if(!empty($finalBranches1))
		{
			foreach ($finalBranches1 as $singleBranch => $downloads) {
				if(!array_key_exists($singleBranch, $finalBranches2))
				{
					$finalBranches2[$singleBranch] = 0;
				}
			}
		}

		if(!empty($finalBranches2))
		{
			foreach ($finalBranches2 as $singleBranch => $downloads) {
				if(!array_key_exists($singleBranch, $finalBranches1))
				{
					$finalBranches1[$singleBranch] = 0;
				}
			}
		}

		if(!empty($finalBranches2))
		{
			foreach ($finalBranches2 as $singleBranch => $curDownloads) {
				$lastDownlaods = $finalBranches1[$singleBranch];
				$percentage = $this->getPercentage($curDownloads, $lastDownlaods);
				$finalData[] = array('name' => utf8_encode($singleBranch) . ' '.$percentage.'%', 'data' => array( (int) $lastDownlaods, (int) $curDownloads), 'real_name' => utf8_encode($singleBranch), 'downlaods' => $curDownloads);
			}
		}

		return $finalData;
	}
	public function getPercentage($curDownloads, $lastDownloads)
	{
		$percentage = '-';
		if($curDownloads == 0 && $lastDownloads == 0)
			$percentage = 0;
		else
		{
			if($curDownloads > $lastDownloads)
			{
				if($lastDownloads > 0)
					$percentage = ($curDownloads / $lastDownloads) * 100;
				else
					$percentage = '-';
			}
			else
			{
				if($lastDownloads > 0 && $curDownloads > 0)
					$percentage = (($curDownloads / $lastDownloads) - 1) * 100;
				else
					$percentage = '-';
			}
		}	
		$percentage = round($percentage);	
		return $percentage;	
	}
}
