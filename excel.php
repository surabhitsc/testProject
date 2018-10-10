<?php

ini_set('memory_limit','-1');

require 'vendor/autoload.php';
$conn = new mysqli('localhost', 'root', 'admin123', 'excelreader');
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as ExcelWriter;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ExcelReader;

//$excelFile = 'Template_25042018.xlsx';


	if(isset($_POST['btn-upload']))
	{    
		 $file = $_FILES['file']['name'];
		 $file_loc = $_FILES['file']['tmp_name'];
		 $file_size = $_FILES['file']['size'];
		 $file_type = $_FILES['file']['type'];
		 $folder="uploads/";
	 
		 move_uploaded_file($file_loc,$folder.$file);
		 $excelFile = 'uploads/' . $file;

		 $reader = new ExcelReader();
		 $spreadsheet = $reader->load($excelFile);

		 $sheetNames = $spreadsheet->getSheetNames();
		 $totalSheets = count($sheetNames);

		 $activeIndex = (isset($_REQUEST['currentIndex']) ? $_REQUEST('currentIndex') : 2);

		 $mergedSpreadsheet = new Spreadsheet();
		 $mergedSheet = $mergedSpreadsheet->getActiveSheet();
		 $dataArr= array();
		 
		 foreach($sheetNames as $index => $sheetName) {
			if ($index >= 2 && $index <= $totalSheets) {
				$worksheet = $spreadsheet->getSheetByName($sheetName);
		
				$highestRow = $worksheet->getHighestRow(); // e.g. 10
				$highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
				$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5

				$rowData = [];	
				for ($row = 2; $row <= $highestRow; ++$row) {
					for ($col = 1; $col <= $highestColumnIndex; ++$col) {
						$value = $worksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
						$dataArr[$row][$col] = $value;
					}
				}
	//echo '<pre>'; print_r($dataArr);  die;
				foreach($dataArr as $val){
					$query = $conn->query("INSERT INTO data SET FromCurrencyId = '" . $conn->real_escape_string($val['1']) . "', ToCurrencyId = '" . $conn->real_escape_string($val['2']) . "', FromCurrencyId1 = '" . $conn->real_escape_string($val['3']) . "', ToCurrencyId1 = '" . $conn->real_escape_string($val['4']) . "', EffectiveDate = '" . $conn->real_escape_string($val['5']) . "', ExchangeRate = '" . $conn->real_escape_string($val['6']) . "'");
				}
				unset($_POST);
				
				
			}
		}
		
	echo 'data has been uploaded successfully';
} 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Merge Excel Sheets into one sheet</title>
</head>
<body>
<form action="" method="post" enctype="multipart/form-data">
<input type="file" name="file" />
<br><br>
<button type="submit" name="btn-upload">upload</button>
</form>
</body>
</html>




