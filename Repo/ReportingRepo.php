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
		$monthlyBranchComparison = $this->getBranchDownloadComparison($fromLowerDate, $fromUpperDate, $toLowerDate, $toUpperDate);

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
		return array('chart' => $finalData,
					 'cur_month' => $curMonthcomparison, 
					 'last_year' => $yearcomparison, 
					 'cur_month_branch' => $monthlyBranchComparison,
					 'cur_from' => $curStartMonth,
					 'cur_to' => $curTodayMonth,
					 'last_from' => $lastMonthStart,
					 'last_to' => $lastMonthToday,
					 'last_year_from' => $lastYearStart,
					 'last_year_to' => $lastYearToday,

					 );
		// print_r($yearsArr);
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

	public function getBranchDownloadComparison($fromLowerDate, $fromUpperDate, $toLowerDate, $toUpperDate)
	{
		$finalData = array();
		$finalBranches1 = array();
		$finalBranches2 = array();		
		$sql = "SELECT branch_office, COUNT(DISTINCT id) downloads FROM queries where DATE(date_created) between '".$fromLowerDate."' AND  '".$fromUpperDate."' group by branch_office";
		$sth = $GLOBALS['pdo']->query($sql);
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		while($row = $sth->fetch())
		{
			$finalBranches1[$row['branch_office']] = $row['downloads'];
		}


		$sql = "SELECT branch_office, COUNT(DISTINCT id) downloads FROM queries where DATE(date_created) between '".$toLowerDate."' AND  '".$toUpperDate."'  group by branch_office";
		$sth = $GLOBALS['pdo']->query($sql);
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		while($row = $sth->fetch())
		{
			$finalBranches2[$row['branch_office']] = $row['downloads'];
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
				$finalData[] = array('name' => utf8_encode($singleBranch), 'data' => array( (int) $lastDownlaods, (int) $curDownloads));
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
