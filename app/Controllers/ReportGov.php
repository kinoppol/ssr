<?php

namespace App\Controllers;

class ReportGov extends BaseController
{
	public function gov_01($title,$print=false)
	{
		
		if(!isset($title))$title=$_POST['title'];
		helper('report');
		helper('table');
		helper('user');
		helper('org');
		helper('thai');
		helper('string');
		
		

		if(isset($_POST['org_id'])&&$_POST['org_id']=='0'){
			$org_code=0;
			$org_name='ทุกกลุ่ม กรอ.อศ.';
		}else{
			$org_code=isset($_POST['org_id'])?$_POST['org_id']:current_user('org_code');
			$org_name=isset($_POST['org_id'])?org_name($_POST['org_id']):'';
		}

		$data=array(
			'org_code'=>$org_code,
			'org_name'=>$org_name,
		);
		$org_type_name=org_type_name($data);
		if($org_code!=0){
			$signBox=signBox($data);
		}else{
			$signBox='';
		}

		$data=array(
			'title'=>$title,
			'label'=>'ปีที่ประชุม',
			'org_ids'=>gov_ids(),
		);
		$form=govYearFilter($data);
		$result='';
		$resultHead=array(
			'ครั้งที่',
			'วันที่ประชุม',
			'สถานที่ประชุม',
			'หัวข้อการประชุม',
			'หมายเหตุ',
		);
		if($org_code==0){
			$resultHead[0]='กลุ่ม กรอ.อศ.';
		}
		if(isset($_POST['year'])){

			$caption='<b>'.$title.' ปี '.($_POST['year']+543).'</b><br>'.$org_name;


		    $govModel = model('App\Models\GovModel');
            if($org_code!=0){
				$resultData=$govModel->getMeetting(['gov_id'=>$_POST['org_id'],'year'=>$_POST['year']]);
			}else{
				$resultData=$govModel->getMeetting(['year'=>$_POST['year']]);
			}
			$i=0;
            $resultRows=array();
			foreach ($resultData as $meetting){
				$i++;
				$resultRows[]=array(
					$org_code!=0?$i:strLim(org_name($meetting['gov_id']),$print?30:false),
					'meetting_date'=>dateThai($meetting['meetting_date'],true,false,true),
					'meetting_place'=>strLim($meetting['meetting_place'],$print?30:false),
					'subject'=>strLim($meetting['subject'],$print?30:false),
					'note'=>'',
				);
			}

			$mouArr=array(
					'caption'=>$caption,
					'thead'=>$resultHead,
					'tbody'=>$resultRows,
			);
			$result=genTable($mouArr,$export=true,$noFoot=true);
		}else{

			$result='โปรดกดปุม "ตกลง" เพื่อดูรายงาน';
		}
		$data=array(
			'form'=>$form,
			'result'=>$result,
		);
		if($print==false){
		$data=array(
			'title'=>$title,
			'mainMenu'=>view('_menu'),
            'content'=>view('reportView',$data),
			'notification'=>'',
			'task'=>'',
		);
		}else{
			$result.=$signBox;
			error_reporting(0);
			helper('mpdf');
			//return $result;
			$pdf_data=array(
				'html'=>$result,
				'size'=>"A4-L",
				'fontsize'=>16,
				'marginL'=>20,
				'marginR'=>10,
				'marginT'=>25,
				'marginB'=>10,
				'header'=>'',
				'wartermark'=>'',
				'wartermarkimage'=>'',
				'footer'=>'เอกสารนี้ออกโดย'.SYSTEMNAME.' สำนักความร่วมมือ สำนักงานคณะกรรมการการอาชีวศึกษา '.date('Y-m-d H:i:s'),
				'header'=>'<div style="text-align: right; font-weight: normal;"><br> หน้า{PAGENO}/{nbpg}</div>'
			);
			$location=FCPATH.'/pdf/';
			$fname=$_POST['org_id'].'_school_01.pdf';
			$filePdf=genPdf($pdf_data,$pageNo=NULL,$location,$fname);
			//return '';
			return '<meta http-equiv="refresh" content="0;url='.site_url('public/pdf/'.$filePdf).'?'.time().'">';
		}
		return view('_main',$data);
	}

	public function gov_06($title,$print=false)
	{
		
		if(!isset($title))$title=$_POST['title'];
		helper('report');
		helper('table');
		helper('user');
		helper('org');
		helper('thai');
		helper('string');
		
		if(isset($_POST['org_id'])&&$_POST['org_id']=='0'){
			$org_code=0;
			$org_name='ทุกกลุ่ม กรอ.อศ.';
		}else{
			$org_code=isset($_POST['org_id'])?$_POST['org_id']:current_user('org_code');
			$org_name=isset($_POST['org_id'])?org_name($_POST['org_id']):'';
		}

		$data=array(
			'org_code'=>$org_code,
			'org_name'=>$org_name,
		);
		$org_type_name=org_type_name($data);

		if($org_code!=0){
			$signBox=signBox($data);
		}else{
			$signBox='';
		}

		$data=array(
			'title'=>$title,
			'label'=>'ปีที่พัฒนาครูฝึก',
			'org_ids'=>gov_ids(),
		);
		$form=govYearFilter($data);
		$result='';
		$resultHead=array(
			'ครั้งที่',
			'วันที่ประชุม',
			'สถานที่ประชุม',
			'หัวข้อการประชุม',
			'จำนวนผู้เข้า<br>รับการพัฒนา',
			'หมายเหตุ',
		);
		if($org_code==0){
			$resultHead[0]='กลุ่ม กรอ.อศ.';
		}
		if(isset($_POST['year'])){

			$caption='<b>'.$title.' ปี '.($_POST['year']+543).'</b><br>'.$org_name;

			if($org_code!=0){
				$data=array(
					'gov_id'=>$_POST['org_id'],
					'year'=>$_POST['year'],
				);
			}else{
				$data=array(
					'year'=>$_POST['year'],
				);
			}
		    $govModel = model('App\Models\GovModel');
            $resultData=$govModel->getTrainerDev($data);
			$i=0;
            $resultRows=array();
			foreach ($resultData as $td){
				$i++;
				$resultRows[]=array(
					$org_code!=0?$i:strLim(org_name($td->gov_id),$print?30:false),
					'dev_date'=>dateThai($td->start_date,true,false,true).' ถึง <br>'.dateThai($td->end_date,true,false,true),
					'dev_place'=>strLim($td->dev_place,$print?30:false),
					'subject'=>strLim($td->subject,$print?30:false),
					'person_count'=>0,
					'note'=>'',
				);
			}

			$mouArr=array(
					'caption'=>$caption,
					'thead'=>$resultHead,
					'tbody'=>$resultRows,
			);
			$result=genTable($mouArr,$export=true,$noFoot=true);
		}else{

			$result='โปรดกดปุม "ตกลง" เพื่อดูรายงาน';
		}
		$data=array(
			'form'=>$form,
			'result'=>$result,
		);
		if($print==false){
		$data=array(
			'title'=>$title,
			'mainMenu'=>view('_menu'),
            'content'=>view('reportView',$data),
			'notification'=>'',
			'task'=>'',
		);
		}else{
			$result.=$signBox;
			error_reporting(0);
			helper('mpdf');
			//return $result;
			$pdf_data=array(
				'html'=>$result,
				'size'=>"A4-L",
				'fontsize'=>16,
				'marginL'=>20,
				'marginR'=>10,
				'marginT'=>10,
				'marginB'=>10,
				'header'=>'',
				'wartermark'=>'',
				'wartermarkimage'=>'',
				'footer'=>'เอกสารนี้ออกโดย'.SYSTEMNAME.' สำนักความร่วมมือ สำนักงานคณะกรรมการการอาชีวศึกษา '.date('Y-m-d H:i:s'),
				'header'=>'<div style="text-align: right; font-weight: normal;"><b>แบบฟอร์มที่ 6</b><br> หน้า{PAGENO}/{nbpg}</div>'
			);
			$location=FCPATH.'/pdf/';
			$fname=$_POST['org_id'].'_school_01.pdf';
			$filePdf=genPdf($pdf_data,$pageNo=NULL,$location,$fname);
			//return '';
			return '<meta http-equiv="refresh" content="0;url='.site_url('public/pdf/'.$filePdf).'?'.time().'">';
		}
		return view('_main',$data);
	}
	
	public function gov_07($title,$print=false)
	{
		
		if(!isset($title))$title=$_POST['title'];
		helper('report');
		helper('table');
		helper('user');
		helper('org');
		helper('thai');
		helper('string');
		
		if(isset($_POST['org_id'])&&$_POST['org_id']=='0'){
			$org_code=0;
			$org_name='ทุกกลุ่ม กรอ.อศ.';
		}else{
			$org_code=isset($_POST['org_id'])?$_POST['org_id']:current_user('org_code');
			$org_name=isset($_POST['org_id'])?org_name($_POST['org_id']):'';
		}

		$data=array(
			'org_code'=>$org_code,
			'org_name'=>$org_name,
		);
		$org_type_name=org_type_name($data);
		if($org_code!=0){
			$signBox=signBox($data);
		}else{
			$signBox='';
		}

		$data=array(
			'title'=>$title,
			'label'=>'ปีที่ประชาสัมพันธ์',
			'org_ids'=>gov_ids(),
		);
		$form=govYearFilter($data);
		$result='';
		$resultHead=array(
			'ครั้งที่',
			'ระยะเวลาดำเนินการ',
			'สถานที่ประชาสัมพันธ์',
			'วิธีการประชาสัมพันธ์',
			'จำนวนผู้เรียน<br>เป้าหมาย (คน)',
			'จำนวนผู้เรียน<br>ที่สมัครเรียน (คน)',
		);
		if($org_code==0){
			$resultHead[0]='กลุ่ม กรอ.อศ.';
		}
		if(isset($_POST['year'])){

			$caption='<b>'.$title.' ปี '.($_POST['year']+543).'</b><br>'.$org_name;

			
			if($org_code!=0){
				$data=array(
					'gov_id'=>$_POST['org_id'],
					'year'=>$_POST['year'],
				);
			}else{
				$data=array(
					'year'=>$_POST['year'],
				);
			}

		    $govModel = model('App\Models\GovModel');
            $resultData=$govModel->getPublic($data);
			$i=0;
            $resultRows=array();
			foreach ($resultData as $pr){
				$i++;
				$resultRows[]=array(
					
					$org_code!=0?$i:strLim(org_name($pr->gov_id),$print?30:false),
					'date'=>dateThai($pr->start_date,true,false,true).' ถึง <br>'.dateThai($pr->end_date,true,false,true),
					'place'=>$pr->public_place,
					'method'=>$pr->public_method,
					'student_target'=>$pr->student_target,
					'student_apply'=>$pr->student_apply,
				);
			}

			$mouArr=array(
					'caption'=>$caption,
					'thead'=>$resultHead,
					'tbody'=>$resultRows,
			);
			$result=genTable($mouArr,$export=true,$noFoot=true);
		}else{

			$result='โปรดกดปุม "ตกลง" เพื่อดูรายงาน';
		}
		$data=array(
			'form'=>$form,
			'result'=>$result,
		);
		if($print==false){
		$data=array(
			'title'=>$title,
			'mainMenu'=>view('_menu'),
            'content'=>view('reportView',$data),
			'notification'=>'',
			'task'=>'',
		);
		}else{
			$result.=$signBox;
			error_reporting(0);
			helper('mpdf');
			//return $result;
			$pdf_data=array(
				'html'=>$result,
				'size'=>"A4",
				'fontsize'=>16,
				'marginL'=>20,
				'marginR'=>10,
				'marginT'=>10,
				'marginB'=>10,
				'header'=>'',
				'wartermark'=>'',
				'wartermarkimage'=>'',
				'footer'=>'เอกสารนี้ออกโดย'.SYSTEMNAME.' สำนักความร่วมมือ สำนักงานคณะกรรมการการอาชีวศึกษา '.date('Y-m-d H:i:s'),
				'header'=>'<div style="text-align: right; font-weight: normal;"><b>แบบฟอร์มที่ 7</b><br> หน้า{PAGENO}/{nbpg}</div>'
			);
			$location=FCPATH.'/pdf/';
			$fname=$_POST['org_id'].'_school_01.pdf';
			$filePdf=genPdf($pdf_data,$pageNo=NULL,$location,$fname);
			//return '';
			return '<meta http-equiv="refresh" content="0;url='.site_url('public/pdf/'.$filePdf).'?'.time().'">';
		}
		return view('_main',$data);
	}

	public function gov_08($title,$print=false)
	{
		
		if(!isset($title))$title=$_POST['title'];
		helper('report');
		helper('table');
		helper('user');
		helper('org');
		helper('thai');
		helper('string');
		
		
		if(isset($_POST['org_id'])&&$_POST['org_id']=='0'){
			$org_code=0;
			$org_name='ทุกกลุ่ม กรอ.อศ.';
		}else{
			$org_code=isset($_POST['org_id'])?$_POST['org_id']:current_user('org_code');
			$org_name=isset($_POST['org_id'])?org_name($_POST['org_id']):'';
		}
		
		

		$data=array(
			'org_code'=>$org_code,
			'org_name'=>$org_name,
		);

		$org_type_name=org_type_name($data);
		if($org_code!=0){
			$signBox=signBox($data);
		}else{
			$signBox='';
		}

		$data=array(
			'title'=>$title,
			'label'=>'ปีที่อบรมครูผู้สอนในสถานศึกษา',
			'org_ids'=>gov_ids(),
		);
		$form=govYearFilter($data);
		$result='';
		$resultHead=array(
			'ครั้งที่',
			'ระยะเวลาดำเนินการ',
			'สถานที่ดำเนินการ',
			'หัวข้อการอบรม',
			'จำนวนผู้<br>เข้ารับการอบรม (คน)',
			'หมายเหตุ',
		);
		if($org_code==0){
			$resultHead[0]='กลุ่ม กรอ.อศ.';
		}
		if(isset($_POST['year'])){

			$caption='<b>'.$title.' ปี '.($_POST['year']+543).'</b><br>'.$org_name;

			if($org_code!=0){
				$data=array(
					'gov_id'=>$_POST['org_id'],
					'year'=>$_POST['year'],
				);
			}else{
				$data=array(
					'year'=>$_POST['year'],
				);
			}
		    $govModel = model('App\Models\GovModel');
            $resultData=$govModel->getTeacherDev($data);
			$i=0;
            $resultRows=array();
			foreach ($resultData as $td){
				$i++;
				$resultRows[]=array(
					
					$org_code!=0?$i:strLim(org_name($td->gov_id),$print?30:false),
					'date'=>dateThai($td->start_date,true,false,true).' ถึง <br>'.dateThai($td->end_date,true,false,true),
					'place'=>$td->dev_place,
					'subject'=>$td->subject,
					'person'=>$td->person,
					'note'=>'',
				);
			}

			$mouArr=array(
					'caption'=>$caption,
					'thead'=>$resultHead,
					'tbody'=>$resultRows,
			);
			$result=genTable($mouArr,$export=true,$noFoot=true);
		}else{

			$result='โปรดกดปุม "ตกลง" เพื่อดูรายงาน';
		}
		$data=array(
			'form'=>$form,
			'result'=>$result,
		);
		if($print==false){
		$data=array(
			'title'=>$title,
			'mainMenu'=>view('_menu'),
            'content'=>view('reportView',$data),
			'notification'=>'',
			'task'=>'',
		);
		}else{
			$result.=$signBox;
			error_reporting(0);
			helper('mpdf');
			//return $result;
			$pdf_data=array(
				'html'=>$result,
				'size'=>"A4",
				'fontsize'=>16,
				'marginL'=>20,
				'marginR'=>10,
				'marginT'=>10,
				'marginB'=>10,
				'header'=>'',
				'wartermark'=>'',
				'wartermarkimage'=>'',
				'footer'=>'เอกสารนี้ออกโดย'.SYSTEMNAME.' สำนักความร่วมมือ สำนักงานคณะกรรมการการอาชีวศึกษา '.date('Y-m-d H:i:s'),
				'header'=>'<div style="text-align: right; font-weight: normal;"><b>แบบฟอร์มที่ 8</b><br> หน้า{PAGENO}/{nbpg}</div>'
			);
			$location=FCPATH.'/pdf/';
			$fname=$_POST['org_id'].'_school_01.pdf';
			$filePdf=genPdf($pdf_data,$pageNo=NULL,$location,$fname);
			//return '';
			return '<meta http-equiv="refresh" content="0;url='.site_url('public/pdf/'.$filePdf).'?'.time().'">';
		}
		return view('_main',$data);
	}

	public function gov_09($title,$print=false)
	{
		
		if(!isset($title))$title=$_POST['title'];
		helper('report');
		helper('table');
		helper('user');
		helper('org');
		helper('thai');
		helper('string');
		
		if(isset($_POST['org_id'])&&$_POST['org_id']=='0'){
			$org_code=0;
			$org_name='ทุกกลุ่ม กรอ.อศ.';
		}else{
			$org_code=isset($_POST['org_id'])?$_POST['org_id']:current_user('org_code');
			$org_name=isset($_POST['org_id'])?org_name($_POST['org_id']):'';
		}

		$data=array(
			'org_code'=>$org_code,
			'org_name'=>$org_name,
		);

		$org_type_name=org_type_name($data);
		if($org_code!=0){
			$signBox=signBox($data);
		}else{
			$signBox='';
		}

		$data=array(
			'title'=>$title,
			'label'=>'ปีที่พัฒนาผู้เรียน',
			'org_ids'=>gov_ids(),
		);
		$form=govYearFilter($data);
		$result='';
		$resultHead=array(
			'ครั้งที่',
			'ระยะเวลาดำเนินการ',
			'สถานที่ดำเนินการ',
			'หัวข้อการอบรม',
			'จำนวนผู้<br>เข้ารับการอบรม (คน)',
			'หมายเหตุ',
		);
		if($org_code==0){
			$resultHead[0]='กลุ่ม กรอ.อศ.';
		}
		if(isset($_POST['year'])){

			$caption='<b>'.$title.' ปี '.($_POST['year']+543).'</b><br>'.$org_name;

			if($org_code!=0){
				$data=array(
					'gov_id'=>$_POST['org_id'],
					'year'=>$_POST['year'],
				);
			}else{
				$data=array(
					'year'=>$_POST['year'],
				);
			}

		    $govModel = model('App\Models\GovModel');
            $resultData=$govModel->getStudentDev($data);
			$i=0;
            $resultRows=array();
			foreach ($resultData as $td){
				$i++;
				$resultRows[]=array(
					
					$org_code!=0?$i:strLim(org_name($td->gov_id),$print?30:false),
					'date'=>dateThai($td->start_date,true,false,true).' ถึง <br>'.dateThai($td->end_date,true,false,true),
					'place'=>$td->dev_place,
					'subject'=>$td->subject,
					'person'=>$td->person,
					'note'=>'',
				);
			}

			$mouArr=array(
					'caption'=>$caption,
					'thead'=>$resultHead,
					'tbody'=>$resultRows,
			);
			$result=genTable($mouArr,$export=true,$noFoot=true);
		}else{

			$result='โปรดกดปุม "ตกลง" เพื่อดูรายงาน';
		}
		$data=array(
			'form'=>$form,
			'result'=>$result,
		);
		if($print==false){
		$data=array(
			'title'=>$title,
			'mainMenu'=>view('_menu'),
            'content'=>view('reportView',$data),
			'notification'=>'',
			'task'=>'',
		);
		}else{
			$result.=$signBox;
			error_reporting(0);
			helper('mpdf');
			//return $result;
			$pdf_data=array(
				'html'=>$result,
				'size'=>"A4-L",
				'fontsize'=>16,
				'marginL'=>20,
				'marginR'=>10,
				'marginT'=>10,
				'marginB'=>10,
				'header'=>'',
				'wartermark'=>'',
				'wartermarkimage'=>'',
				'footer'=>'เอกสารนี้ออกโดย'.SYSTEMNAME.' สำนักความร่วมมือ สำนักงานคณะกรรมการการอาชีวศึกษา '.date('Y-m-d H:i:s'),
				'header'=>'<div style="text-align: right; font-weight: normal;"><b>แบบฟอร์มที่ 9</b><br> หน้า{PAGENO}/{nbpg}</div>'
			);
			$location=FCPATH.'/pdf/';
			$fname=$_POST['org_id'].'_school_01.pdf';
			$filePdf=genPdf($pdf_data,$pageNo=NULL,$location,$fname);
			//return '';
			return '<meta http-equiv="refresh" content="0;url='.site_url('public/pdf/'.$filePdf).'?'.time().'">';
		}
		return view('_main',$data);
	}


	public function gov_10($title,$print=false)
	{
		
		if(!isset($title))$title=$_POST['title'];
		helper('report');
		helper('table');
		helper('user');
		helper('org');
		helper('thai');
		helper('string');
		
		
		if(isset($_POST['org_id'])&&$_POST['org_id']=='0'){
			$org_code=0;
			$org_name='ทุกกลุ่ม กรอ.อศ.';
		}else{
			$org_code=isset($_POST['org_id'])?$_POST['org_id']:current_user('org_code');
			$org_name=isset($_POST['org_id'])?org_name($_POST['org_id']):'';
		}

		$data=array(
			'org_code'=>$org_code,
			'org_name'=>$org_name,
		);

		$org_type_name=org_type_name($data);
		if($org_code!=0){
			$signBox=signBox($data);
		}else{
			$signBox='';
		}

		$data=array(
			'title'=>$title,
			'label'=>'ปีที่ดำเนินโครงการ',
			'org_ids'=>gov_ids(),
		);
		$form=govYearFilter($data);
		$result='';
		$resultHead=array(
			'ครั้งที่',
			'โครงการ',
			'ระยะเวลาดำเนินการ',
			'สถานที่ดำเนินการ',
			'วิธีดำเนินการ',
			'วัตถุประสงค์',
			'กลุ่ม<br>เป้าหมาย',
			'จำนวน<br>เป้าหมาย (คน)',
			'ผลผลิต',
			'ผลลัพธ์',
			'หมายเหตุ',
		);
		if($org_code==0){
			$resultHead[0]='กลุ่ม กรอ.อศ.';
		}
		if(isset($_POST['year'])){

			$caption='<b>'.$title.' ปี '.($_POST['year']+543).'</b><br>'.$org_name;

			if($org_code!=0){
				$data=array(
					'gov_id'=>$_POST['org_id'],
					'year'=>$_POST['year'],
				);
			}else{
				$data=array(
					'year'=>$_POST['year'],
				);
			}
		    $govModel = model('App\Models\GovModel');
            $resultData=$govModel->getProject($data);
			$i=0;
            $resultRows=array();
			foreach ($resultData as $td){
				$i++;
				$resultRows[]=array(
					
					$org_code!=0?$i:strLim(org_name($td->gov_id),$print?30:false),
					'subject'=>$td->subject,
					'date'=>dateThai($td->start_date).' ถึง <br>'.dateThai($td->end_date),
					'place'=>$td->project_place,
					'method'=>$td->method,
					'objective'=>$td->objective,
					'target'=>$td->target,
					'target_amount'=>$td->target_amount,
					'product'=>$td->product,
					'result'=>$td->result,
					'note'=>'',
				);
			}

			$mouArr=array(
					'caption'=>$caption,
					'thead'=>$resultHead,
					'tbody'=>$resultRows,
			);
			$result=genTable($mouArr,$export=true,$noFoot=true);
		}else{

			$result='โปรดกดปุม "ตกลง" เพื่อดูรายงาน';
		}
		$data=array(
			'form'=>$form,
			'result'=>$result,
		);
		if($print==false){
		$data=array(
			'title'=>$title,
			'mainMenu'=>view('_menu'),
            'content'=>view('reportView',$data),
			'notification'=>'',
			'task'=>'',
		);
		}else{
			$result.=$signBox;
			error_reporting(0);
			helper('mpdf');
			//return $result;
			$pdf_data=array(
				'html'=>$result,
				'size'=>"A4-L",
				'fontsize'=>16,
				'marginL'=>20,
				'marginR'=>10,
				'marginT'=>10,
				'marginB'=>10,
				'header'=>'',
				'wartermark'=>'',
				'wartermarkimage'=>'',
				'footer'=>'เอกสารนี้ออกโดย'.SYSTEMNAME.' สำนักความร่วมมือ สำนักงานคณะกรรมการการอาชีวศึกษา '.date('Y-m-d H:i:s'),
				'header'=>'<div style="text-align: right; font-weight: normal;"><b>แบบฟอร์มที่ 10</b><br> หน้า{PAGENO}/{nbpg}</div>'
			);
			$location=FCPATH.'/pdf/';
			$fname=$_POST['org_id'].'_school_01.pdf';
			$filePdf=genPdf($pdf_data,$pageNo=NULL,$location,$fname);
			//return '';
			return '<meta http-equiv="refresh" content="0;url='.site_url('public/pdf/'.$filePdf).'?'.time().'">';
		}
		return view('_main',$data);
	}
}