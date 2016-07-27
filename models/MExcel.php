<?php
require_once "Sdks/excel/PHPExcel.php";

class MExcel extends PHPExcel
{
	private $_cells = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N');
	
	public function __construct()
	{
		parent::__construct();
	}
	public function configProperties( $properties )
	{
		if( is_array($properties) )
		{
			foreach ($properties as $key=>$value)
			{
				$this->getProperties()->set{$key}($value);
			}
		}
	}
	public function configHead( $head, $pindex = 0, $offset= 1 )
	{
		if( is_array($head) )
		{
			$this->setActiveSheetIndex($pindex);
			$this->getActiveSheet()->getDefaultRowDimension()->setRowHeight(25);
			foreach ($head as $key=>$value)
			{
				$position = $this->_cells[$key] . "{$offset}";
				$this->getActiveSheet()->setCellValue($position, $value);
				$this->getActiveSheet()->getStyle($position)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$this->getActiveSheet()->getStyle($position)->getFill()->getStartColor()->setARGB('D1D1D1D1');
				$this->getActiveSheet()->getStyle($position)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$this->getActiveSheet()->getStyle($position)->getFont()->setBold(true); 
			}
		}
	}
	public function configBody( $data, $pindex = 0, $width=5, $offset=2 )
	{
		if( is_array($data) )
		{
			$this->setActiveSheetIndex($pindex);
			$this->getActiveSheet()->getDefaultRowDimension()->setRowHeight(25);
			foreach ($data as $key=>$row)
			{
				foreach ($row as $i=>$value)
				{
					$position = "{$this->_cells[$i]}" . ($key + $offset);
					$this->getActiveSheet()->setCellValue($position, $value);
					$this->getActiveSheet()->getStyle($position)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					if($width>0){
                        $this->getActiveSheet()->getDefaultColumnDimension()->setWidth(mb_strlen($value, 'gbk') * $width);
                    }
				}
			}
		}
	}

}

class MExcel_Writer extends PHPExcel_Writer_Excel5
{
	public function __construct($phpExcel)
	{
		parent::__construct($phpExcel);
	}
	public function configHeader( $filename = 'filename.xls' )
	{
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="' . $filename . '"');
		header("Content-Transfer-Encoding:binary");
	}
	public function save_ext( $pFilename = 'php://output' )
	{
		$this->save($pFilename);
	}
}
?>