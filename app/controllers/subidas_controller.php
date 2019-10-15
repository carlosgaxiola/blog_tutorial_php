<?php 
class SubidasController extends AppController {
	var $name = 'Subidas';

	var $helpers = array(
		'Form',
		'Html'
	);

	public function index () {
		$this->set('subidas', array('hola', 'adios'));
		// print_r($_FILES);
	}

	public function add () {
		$nombrearchivo = "C://xampp/htdocs/cakephp/blog_tutorial/app/webroot/files/".$this->data['Subida']['archivo']['name'];
		// /* copiamos el archivo*/
		// if (move_uploaded_file($this->data['Subida']['archivo']['tmp_name'], $nombrearchivo)) {
		// 	/* mensaje al usaurio */
		// 	$this->Session->setFlash('Archivo subido satisfactoriamente');
		// } else {
		// 	/* mensaje al usaurio */
		// 	$this->Session->setFlash('Error al subir el archivo, verificar.');
		// }


		// $this->testxls();

		$this->csv2MySQL();

		// $this->set('data', $this->data);
		// $this->Session->setFlash($_FILES['data'])
		// echo "<pre>";
		// print_r($this->data);
		// echo "</pre>";
		// $this->redirect(array('action' => 'index'));
		// $this->render('home');
	}

	public function testxls() { 
	 	App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel.php'));
	 	$folderToSaveXls = 'C://xampp/htdocs/cakephp/blog_tutorial/app/webroot/files'; 
	 	$objPHPExcel = new PHPExcel(); 
	 	$objPHPExcel->getProperties()
	 		->setCreator("Maarten Balliauw") 
		 	->setLastModifiedBy("Maarten Balliauw") 
		 	->setTitle("PHPExcel Test Document") 
		 	->setSubject("PHPExcel Test Document") 
		 	->setDescription("Test document for PHPExcel, generated using PHP classes.") 
		 	->setKeywords("office PHPExcel php") 
		 	->setCategory("Test result file");
		$objPHPExcel->setActiveSheetIndex(0) 
			->setCellValue('A1', 'Hello') 
			->setCellValue('B2', 'world!') 
			->setCellValue('C1', 'Hello') 
			->setCellValue('D2', 'world!'); 
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
		$objWriter->save( $folderToSaveXls . '/test.xls' ); 
	} 

	function xls2csv () {
		App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel.php'));
		$xslPath = __DIR__ . DS . '..' . DS . 'webroot' . DS . 'files' . DS . 'test.xls';
		$csvPath = __DIR__ . DS . '..' . DS . 'webroot' . DS . 'files';

		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objPHPExcelReader = $objReader->load($xslPath);

		$loadedSheetNames = $objPHPExcelReader->getSheetNames();
		// print_r($loadedSheetNames);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcelReader, 'CSV');

		foreach($loadedSheetNames as $sheetIndex => $loadedSheetName) {
		    $objWriter->setSheetIndex($sheetIndex);
		    $csvPath = $csvPath . DS . $loadedSheetName . '.csv';
		    $objWriter->save($csvPath);

		}
	}

	function csv2MySQL () {
		$csvPath =  __DIR__ . DS . '..' . DS . 'webroot' . DS . 'files' . DS . 'Worksheet.csv';
	    $file = fopen($csvPath, "r");
    	$first = true;
        while (($row = fgetcsv($file, 10000, ",")) !== FALSE) {
        	if ($first) {
        		$first = false;
        		$headers = $row;
        		$data = array();
        	} else if (!empty($row[0])) {
        		$this->Subida->create();
        		foreach ($row as $key => $value) {
        			if (!empty($row[$key])) {
		        		$data['Subida'][strtolower($headers[$key])] = $value;
        			}
        		}
        		echo $this->Subida->save($data)? 'subido': 'no';
        	}
        }
	}

	function xls2csv2 () {
		$arr_data = array();
		App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel.php'));
        $objPHPExcel = PHPExcel_IOFactory::load(__DIR__ . DS . '..' . DS . 'webroot' . DS . 'files' . DS . 'test.xls');
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        foreach($cell_collection as $cell)
        {
            $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
            $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
            $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
            //header will / should be in row 1 only. of course this can be modified to suit your need.
            // Skip Rows From Top if you have header in Excel then Change 0 to 1
            if($row == 0)
            {
                $header[$row][$column] = $data_value;
            }
            else
            {
                $arr_data[$row]['row'] = $row;
                $arr_data[$row][$column] = $data_value;
            }
        }
        $data = $arr_data;
        foreach($data as $val1)
        {
            $num_col = sizeof($val1) - 1;  // get number of columns in Excel 
            break;
        }
        $lwrcol=array();    
        foreach($data as $val2)
        {
            $alphaArr = range('A','Z');
            $colArr = range('A',$alphaArr[$num_col - 1]);

            foreach($colArr as $col)
            {
                $lwrcol[$col] = isset($val2[$col]) ? utf8_decode($val2[$col]) : "";
                fwrite($fp,$lwrcol[$col].",");
            }
            fwrite($fp,"\n");   
        }
        chmod(getcwd()."/file.csv", 0777);
    	fclose($fp);
	}

}
?>