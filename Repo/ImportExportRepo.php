<?php
ini_set('max_execution_time', 900);
ini_set('memory_limit', '-1');

require_once dirname(__FILE__) . '/../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
require_once dirname(__FILE__) . '/../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';

class ImportExportRepo{
	
	public function importCsv($request)
	{
		$inputFileType = 'CSV';
		$inputFileName = FileUpload::getRootPath().'data/temp/'.$request['path'];
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$key = 0;
		$importedCol = '';
		$dbCol = 'A';		
		$worksheet = $objPHPExcel->getActiveSheet();
		foreach ($worksheet->getRowIterator() as $row) {
//		    echo 'Row number: ' . $row->getRowIndex() . "\r\n";

		    $cellIterator = $row->getCellIterator();
		    $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
		    foreach ($cellIterator as $cellNo => $cell) {

		        if (!is_null($cell)) {

		        	if($key == 0)
		        	{
		        		if($cell->getValue() == $request['field'])
		        			$importedCol = $cellNo;

		        	}
		        	else
		        	{
			        	if($cellNo == "A")
			        	{
			        		$dbId = $cell->getValue()."\n";
			        	}

			        	if($cellNo == $importedCol)
			        	{
			        		$this->updateVal($dbId, $request['field'], $cell->getValue());
			        	}
			        }

		        }
		    }
		        $key = 1;

		}
	}

	public function updateVal($id, $col, $val)
	{
		$col = strtolower($col);
		$val = trim($val);
		$id = trim($id);
		if(!empty($id))
		{
			$query = $GLOBALS['con']->update('queries', array($col => $val), $id)->execute();			
		}
	}
	
}