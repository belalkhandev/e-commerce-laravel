<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//include PhpSpreadsheet library
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;	
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;	
use PhpOffice\PhpSpreadsheet\IOFactory;

class TransactionsExportController extends Controller
{
	
	//Transactions Excel Export
    public function TransactionsExcelExport(Request $request){
		$gtext = gtext();
		
		$start_date = $request->start_date;
		$end_date = $request->end_date;
		
		if(($start_date != '') && ($end_date != '')){

			$datalist = DB::table('order_masters as a')
				->leftJoin('users as b', 'a.customer_id', '=', 'b.id')
				->join('users as c', 'a.seller_id', '=', 'c.id')
				->join('payment_method as d', 'a.payment_method_id', '=', 'd.id')
				->join('payment_status as e', 'a.payment_status_id', '=', 'e.id')
				->join('order_status as f', 'a.order_status_id', '=', 'f.id')
				->join('order_items as g', 'a.id', '=', 'g.order_master_id')
				->select('a.id', 'a.customer_id', 'a.transaction_no', 'a.payment_status_id', 'a.order_status_id', 'a.order_no', 'a.created_at', 'a.shipping_fee', DB::raw("SUM(g.total_price) as total_amount"), DB::raw("SUM(g.tax) as tax"), 'b.name', 'c.shop_name', 'd.method_name', 'e.pstatus_name', 'f.ostatus_name')
				->whereBetween('a.created_at', [$start_date, $end_date])
				->groupBy('a.customer_id', 'a.transaction_no', 'a.payment_status_id', 'a.order_status_id', 'a.created_at', 'f.ostatus_name', 'e.pstatus_name', 'd.method_name', 'a.shipping_fee', 'b.name', 'c.shop_name', 'a.order_no', 'a.id')
				->orderBy('a.created_at','desc')
				->get();
				
		}else{
			$datalist = DB::table('order_masters as a')
				->leftJoin('users as b', 'a.customer_id', '=', 'b.id')
				->join('users as c', 'a.seller_id', '=', 'c.id')
				->join('payment_method as d', 'a.payment_method_id', '=', 'd.id')
				->join('payment_status as e', 'a.payment_status_id', '=', 'e.id')
				->join('order_status as f', 'a.order_status_id', '=', 'f.id')
				->join('order_items as g', 'a.id', '=', 'g.order_master_id')
				->select('a.id', 'a.customer_id', 'a.transaction_no', 'a.payment_status_id', 'a.order_status_id', 'a.order_no', 'a.created_at', 'a.shipping_fee', DB::raw("SUM(g.total_price) as total_amount"), DB::raw("SUM(g.tax) as tax"), 'b.name', 'c.shop_name', 'd.method_name', 'e.pstatus_name', 'f.ostatus_name')
				->groupBy('a.customer_id', 'a.transaction_no', 'a.payment_status_id', 'a.order_status_id', 'a.created_at', 'f.ostatus_name', 'e.pstatus_name', 'd.method_name', 'a.shipping_fee', 'b.name', 'c.shop_name', 'a.order_no', 'a.id')
				->orderBy('a.created_at','desc')
				->get();
		}
		
		$spreadsheet = new Spreadsheet();

		//Page Setup
		//Page Orientation(ORIENTATION_LANDSCAPE/ORIENTATION_PORTRAIT), 
		//Paper Size(PAPERSIZE_A3,PAPERSIZE_A4,PAPERSIZE_A5,PAPERSIZE_LETTER,PAPERSIZE_LEGAL etc)
		$spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
		$spreadsheet->getActiveSheet()->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);

		//Set Page Margins for a Worksheet
		$spreadsheet->getActiveSheet()->getPageMargins()->setTop(0.75);
		$spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.70);
		$spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.70);
		$spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0.75);

		//Center a page horizontally/vertically
		$spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
		$spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

		//Show/hide gridlines(true/false)
		$spreadsheet->getActiveSheet()->setShowGridlines(true);

		//Activate work sheet
		$spreadsheet->createSheet(0);
		$spreadsheet->setActiveSheetIndex(0);
		$spreadsheet->getActiveSheet(0);
		//work sheet name
		$spreadsheet->getActiveSheet()->setTitle('Data');	
		//Default Font Set
		$spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
		//Default Font Size Set
		$spreadsheet->getDefaultStyle()->getFont()->setSize(11); 

		//Border color
		$styleThinBlackBorderOutline = array('borders' => array('outline'=> array('borderStyle' => Border::BORDER_THIN, 'color' => array('argb' => '5a5a5a'))));
		$spreadsheet->getActiveSheet()->SetCellValue('A2', __('Transactions'));
		$spreadsheet->getActiveSheet()->getStyle('A2')->getFont();

		//Font Size for Cells
		$spreadsheet -> getActiveSheet()->getStyle('A2') -> applyFromArray(array('font' => array('size' => '14', 'bold' => true)), 'A2');

		//Text Alignment Horizontal(HORIZONTAL_LEFT,HORIZONTAL_CENTER,HORIZONTAL_RIGHT)
		$spreadsheet -> getActiveSheet()->getStyle('A2') -> getAlignment()->setHorizontal(Alignment::VERTICAL_CENTER);

		//Text Alignment Vertical(VERTICAL_TOP,VERTICAL_CENTER,VERTICAL_BOTTOM)
		$spreadsheet -> getActiveSheet() -> getStyle('A2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

		//merge Cell
		$spreadsheet -> getActiveSheet() -> mergeCells('A2:I2');

		//Value Set for Cells
		$spreadsheet -> getActiveSheet()				
					->SetCellValue('A4', '#')							
					->SetCellValue('B4', __('Order#'))
					->SetCellValue('C4', __('Transaction#'))							
					->SetCellValue('D4', __('Order Date'))							
					->SetCellValue('E4', __('Customer'))							
					->SetCellValue('F4', __('Store'))							
					->SetCellValue('G4', __('Amount').'('.$gtext['currency_icon'].')')
					->SetCellValue('H4', __('Payment Method'))
					->SetCellValue('I4', __('Payment Status'));

		//Font Size for Cells
		$spreadsheet -> getActiveSheet()->getStyle('A4') -> applyFromArray(array('font' => array('size' => '12', 'bold' => true)), 'A4');	
		$spreadsheet -> getActiveSheet()->getStyle('B4') -> applyFromArray(array('font' => array('size' => '12', 'bold' => true)), 'B4');
		$spreadsheet -> getActiveSheet()->getStyle('C4') -> applyFromArray(array('font' => array('size' => '12', 'bold' => true)), 'C4');
		$spreadsheet -> getActiveSheet()->getStyle('D4') -> applyFromArray(array('font' => array('size' => '12', 'bold' => true)), 'D4');
		$spreadsheet -> getActiveSheet()->getStyle('E4') -> applyFromArray(array('font' => array('size' => '12', 'bold' => true)), 'E4');
		$spreadsheet -> getActiveSheet()->getStyle('F4') -> applyFromArray(array('font' => array('size' => '12', 'bold' => true)), 'F4');
		$spreadsheet -> getActiveSheet()->getStyle('G4') -> applyFromArray(array('font' => array('size' => '12', 'bold' => true)), 'G4');
		$spreadsheet -> getActiveSheet()->getStyle('H4') -> applyFromArray(array('font' => array('size' => '12', 'bold' => true)), 'H4');
		$spreadsheet -> getActiveSheet()->getStyle('I4') -> applyFromArray(array('font' => array('size' => '12', 'bold' => true)), 'H4');

		//Text Alignment Horizontal(HORIZONTAL_LEFT,HORIZONTAL_CENTER,HORIZONTAL_RIGHT)
		$spreadsheet -> getActiveSheet()->getStyle('A4') -> getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet -> getActiveSheet()->getStyle('B4') -> getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
		$spreadsheet -> getActiveSheet()->getStyle('C4') -> getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
		$spreadsheet -> getActiveSheet()->getStyle('D4') -> getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
		$spreadsheet -> getActiveSheet()->getStyle('E4') -> getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
		$spreadsheet -> getActiveSheet()->getStyle('F4') -> getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
		$spreadsheet -> getActiveSheet()->getStyle('G4') -> getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
		$spreadsheet -> getActiveSheet()->getStyle('H4') -> getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
		$spreadsheet -> getActiveSheet()->getStyle('I4') -> getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

		//Text Alignment Vertical(VERTICAL_TOP,VERTICAL_CENTER,VERTICAL_BOTTOM)
		$spreadsheet -> getActiveSheet() -> getStyle('A4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		$spreadsheet -> getActiveSheet() -> getStyle('B4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		$spreadsheet -> getActiveSheet() -> getStyle('C4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		$spreadsheet -> getActiveSheet() -> getStyle('D4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		$spreadsheet -> getActiveSheet() -> getStyle('E4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		$spreadsheet -> getActiveSheet() -> getStyle('F4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		$spreadsheet -> getActiveSheet() -> getStyle('G4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		$spreadsheet -> getActiveSheet() -> getStyle('H4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		$spreadsheet -> getActiveSheet() -> getStyle('I4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

		//Width for Cells
		$spreadsheet -> getActiveSheet() -> getColumnDimension('A') -> setWidth(5);
		$spreadsheet -> getActiveSheet() -> getColumnDimension('B') -> setWidth(25);
		$spreadsheet -> getActiveSheet() -> getColumnDimension('C') -> setWidth(25);
		$spreadsheet -> getActiveSheet() -> getColumnDimension('D') -> setWidth(20);
		$spreadsheet -> getActiveSheet() -> getColumnDimension('E') -> setWidth(25);
		$spreadsheet -> getActiveSheet() -> getColumnDimension('F') -> setWidth(25);
		$spreadsheet -> getActiveSheet() -> getColumnDimension('G') -> setWidth(25);
		$spreadsheet -> getActiveSheet() -> getColumnDimension('H') -> setWidth(25);
		$spreadsheet -> getActiveSheet() -> getColumnDimension('I') -> setWidth(25);

		//Wrap text
		$spreadsheet->getActiveSheet()->getStyle('A4')->getAlignment()->setWrapText(true);

		//*border color set for cells
		$spreadsheet -> getActiveSheet() -> getStyle('A4:A4') -> applyFromArray($styleThinBlackBorderOutline);
		$spreadsheet -> getActiveSheet() -> getStyle('B4:B4') -> applyFromArray($styleThinBlackBorderOutline);
		$spreadsheet -> getActiveSheet() -> getStyle('C4:C4') -> applyFromArray($styleThinBlackBorderOutline);
		$spreadsheet -> getActiveSheet() -> getStyle('D4:D4') -> applyFromArray($styleThinBlackBorderOutline);
		$spreadsheet -> getActiveSheet() -> getStyle('E4:E4') -> applyFromArray($styleThinBlackBorderOutline);
		$spreadsheet -> getActiveSheet() -> getStyle('F4:F4') -> applyFromArray($styleThinBlackBorderOutline);
		$spreadsheet -> getActiveSheet() -> getStyle('G4:G4') -> applyFromArray($styleThinBlackBorderOutline);
		$spreadsheet -> getActiveSheet() -> getStyle('H4:H4') -> applyFromArray($styleThinBlackBorderOutline);
		$spreadsheet -> getActiveSheet() -> getStyle('I4:I4') -> applyFromArray($styleThinBlackBorderOutline);

		$i=1; 
		$j=5;
		foreach($datalist as $row){
			
			$date = date('d-m-Y', strtotime($row->created_at));

			if($row->customer_id !=''){
				$customer = $row->name;
			}else{
				$customer = __('Guest User');
			}
			
			$total_amount = $row->total_amount + $row->tax + $row->shipping_fee;
			
			//Value Set for Cells
			$spreadsheet->getActiveSheet()
						->SetCellValue('A'.$j, $i)							
						->SetCellValue('B'.$j, $row->order_no)	
						->SetCellValue('C'.$j, $row->transaction_no)																
						->SetCellValue('D'.$j, $date)																
						->SetCellValue('E'.$j, $customer)																
						->SetCellValue('F'.$j, $row->shop_name)																
						->SetCellValue('G'.$j, $total_amount)
						->SetCellValue('H'.$j, $row->method_name)
						->SetCellValue('I'.$j, $row->pstatus_name);

			//border color set for cells
			$spreadsheet -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			$spreadsheet -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			$spreadsheet -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			$spreadsheet -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			$spreadsheet -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			$spreadsheet -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			$spreadsheet -> getActiveSheet() -> getStyle('G' . $j . ':G' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			$spreadsheet -> getActiveSheet() -> getStyle('H' . $j . ':H' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			$spreadsheet -> getActiveSheet() -> getStyle('I' . $j . ':I' . $j) -> applyFromArray($styleThinBlackBorderOutline);

			//Text Alignment Horizontal(HORIZONTAL_LEFT,HORIZONTAL_CENTER,HORIZONTAL_RIGHT)
			$spreadsheet -> getActiveSheet()->getStyle('A' . $j . ':A' . $j) -> getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
			$spreadsheet -> getActiveSheet()->getStyle('B' . $j . ':B' . $j) -> getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
			$spreadsheet -> getActiveSheet()->getStyle('C' . $j . ':C' . $j) -> getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
			$spreadsheet -> getActiveSheet()->getStyle('D' . $j . ':D' . $j) -> getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
			$spreadsheet -> getActiveSheet()->getStyle('E' . $j . ':E' . $j) -> getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
			$spreadsheet -> getActiveSheet()->getStyle('F' . $j . ':F' . $j) -> getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
			$spreadsheet -> getActiveSheet()->getStyle('G' . $j . ':G' . $j) -> getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
			$spreadsheet -> getActiveSheet()->getStyle('H' . $j . ':H' . $j) -> getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
			$spreadsheet -> getActiveSheet()->getStyle('I' . $j . ':I' . $j) -> getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

			//Text Alignment Vertical(VERTICAL_TOP,VERTICAL_CENTER,VERTICAL_BOTTOM)
			$spreadsheet -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
			$spreadsheet -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
			$spreadsheet -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
			$spreadsheet -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
			$spreadsheet -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
			$spreadsheet -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
			$spreadsheet -> getActiveSheet() -> getStyle('G' . $j . ':G' . $j)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
			$spreadsheet -> getActiveSheet() -> getStyle('H' . $j . ':H' . $j)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
			$spreadsheet -> getActiveSheet() -> getStyle('I' . $j . ':I' . $j)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

			//DateTime format Cell D
			$spreadsheet->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode('dd-mm-yyyy'); //Date Format

			//Number format Cell G
			$spreadsheet->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$spreadsheet->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode('#,##0.00'); 
						
			$i++; $j++;
		}
		
		$exportTime = date("Y-m-d-His", time());	
		$writer = new Xlsx($spreadsheet);
		$file = 'transactions-'.$exportTime. '.xlsx';
		$writer->save('public/export/' . $file);
		
		echo $file;
	}
	
	//Transactions CSV Export
    public function TransactionsCSVExport(Request $request){
		$gtext = gtext();
		
		$start_date = $request->start_date;
		$end_date = $request->end_date;

		if(($start_date != '') && ($end_date != '')){

			$datalist = DB::table('order_masters as a')
				->leftJoin('users as b', 'a.customer_id', '=', 'b.id')
				->join('users as c', 'a.seller_id', '=', 'c.id')
				->join('payment_method as d', 'a.payment_method_id', '=', 'd.id')
				->join('payment_status as e', 'a.payment_status_id', '=', 'e.id')
				->join('order_status as f', 'a.order_status_id', '=', 'f.id')
				->join('order_items as g', 'a.id', '=', 'g.order_master_id')
				->select('a.id', 'a.customer_id', 'a.transaction_no', 'a.payment_status_id', 'a.order_status_id', 'a.order_no', 'a.created_at', 'a.shipping_fee', DB::raw("SUM(g.total_price) as total_amount"), DB::raw("SUM(g.tax) as tax"), 'b.name', 'c.shop_name', 'd.method_name', 'e.pstatus_name', 'f.ostatus_name')
				->whereBetween('a.created_at', [$start_date, $end_date])
				->groupBy('a.customer_id', 'a.transaction_no', 'a.payment_status_id', 'a.order_status_id', 'a.created_at', 'f.ostatus_name', 'e.pstatus_name', 'd.method_name', 'a.shipping_fee', 'b.name', 'c.shop_name', 'a.order_no', 'a.id')
				->orderBy('a.created_at','desc')
				->get();
				
		}else{
			$datalist = DB::table('order_masters as a')
				->leftJoin('users as b', 'a.customer_id', '=', 'b.id')
				->join('users as c', 'a.seller_id', '=', 'c.id')
				->join('payment_method as d', 'a.payment_method_id', '=', 'd.id')
				->join('payment_status as e', 'a.payment_status_id', '=', 'e.id')
				->join('order_status as f', 'a.order_status_id', '=', 'f.id')
				->join('order_items as g', 'a.id', '=', 'g.order_master_id')
				->select('a.id', 'a.customer_id', 'a.transaction_no', 'a.payment_status_id', 'a.order_status_id', 'a.order_no', 'a.created_at', 'a.shipping_fee', DB::raw("SUM(g.total_price) as total_amount"), DB::raw("SUM(g.tax) as tax"), 'b.name', 'c.shop_name', 'd.method_name', 'e.pstatus_name', 'f.ostatus_name')
				->groupBy('a.customer_id', 'a.transaction_no', 'a.payment_status_id', 'a.order_status_id', 'a.created_at', 'f.ostatus_name', 'e.pstatus_name', 'd.method_name', 'a.shipping_fee', 'b.name', 'c.shop_name', 'a.order_no', 'a.id')
				->orderBy('a.created_at','desc')
				->get();
		}
		
		$spreadsheet = new Spreadsheet();

		//Activate work sheet
		$spreadsheet->createSheet(0);
		$spreadsheet->setActiveSheetIndex(0);
		
		//work sheet name
		$spreadsheet->getActiveSheet()->setTitle('Data');
		
		//Default Font Set
		$spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
		
		//Default Font Size Set
		$spreadsheet->getDefaultStyle()->getFont()->setSize(11); 

		$spreadsheet->getActiveSheet() -> SetCellValue('E2', __('Transactions'));	

		//Value Set for Cells
		$spreadsheet -> getActiveSheet()				
					->SetCellValue('A4', '#')							
					->SetCellValue('B4', __('Order#'))
					->SetCellValue('C4', __('Transaction#'))							
					->SetCellValue('D4', __('Order Date'))							
					->SetCellValue('E4', __('Customer'))							
					->SetCellValue('F4', __('Store'))							
					->SetCellValue('G4', __('Amount').'('.$gtext['currency_icon'].')')
					->SetCellValue('H4', __('Payment Method'))
					->SetCellValue('I4', __('Payment Status'));
		$i=1; 
		$j=5;
		foreach($datalist as $row){
			
			$date = date('d-m-Y', strtotime($row->created_at));

			if($row->customer_id !=''){
				$customer = $row->name;
			}else{
				$customer = __('Guest User');
			}
			
			$total_amount = $row->total_amount + $row->tax + $row->shipping_fee;
			
			//Value Set for Cells
			$spreadsheet->getActiveSheet()
						->SetCellValue('A'.$j, $i)							
						->SetCellValue('B'.$j, $row->order_no)	
						->SetCellValue('C'.$j, $row->transaction_no)																
						->SetCellValue('D'.$j, $date)																
						->SetCellValue('E'.$j, $customer)																
						->SetCellValue('F'.$j, $row->shop_name)																
						->SetCellValue('G'.$j, $total_amount)
						->SetCellValue('H'.$j, $row->method_name)
						->SetCellValue('I'.$j, $row->pstatus_name);

			//DateTime format Cell D
			$spreadsheet->getActiveSheet()->getStyle('D'.$j)->getNumberFormat()->setFormatCode('dd-mm-yyyy'); //Date Format

			//Number format Cell G
			$spreadsheet->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$spreadsheet->getActiveSheet()->getStyle('G'.$j)->getNumberFormat()->setFormatCode('#,##0.00'); 
						
			$i++; $j++;
		}

		$exportTime = date("Y-m-d-His", time());
		$writer = new Csv($spreadsheet);
		$file = 'transactions-'.$exportTime. '.csv';
		$writer->setUseBOM(true);
		$writer->save('public/export/' . $file);

		echo $file;
	}
}
