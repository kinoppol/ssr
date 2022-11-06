<?php

namespace App\Controllers;

class ReportSummary extends BaseController
{
	public function sum_01($title,$print=false)
	{
		
		$locationModel = model('App\Models\LocationModel');
        $SummaryModel = model('App\Models\SummaryModel');
        $mouModel = model('App\Models\MouModel');
		if(!isset($title))$title=$_POST['title'];
		helper('report');
		helper('table');
		helper('user');
		helper('org');
		helper('thai');
		
		$org_code=isset($_POST['org_id'])?$_POST['org_id']:current_user('org_code');
		$org_name=isset($_POST['org_id'])?org_name($_POST['org_id']):'';
		$data=array(
			'org_code'=>$org_code,
			'org_name'=>$org_name,
		);
		$org_type_name=org_type_name($data);
		$signBox=signBox($data);

		$data=array(
			'title'=>$title,
			'label'=>'ปีที่ลงนาม',
			'org_ids'=>gov_ids(),
		);
		$form=yearFilter($data);
		$result='';
		$resultHead=array(
			'ภาค',
			'สถานศึกษา<br>(แห่ง)',
			'สถาน<br>ประกอบการ<br>(แห่ง)',
			'การลงนาม<br>ความร่วมมือ<br>(MOU)',
			'ความ<br>ร่วมมือ<br>ระดับ 1',
			'ความ<br>ร่วมมือ<br>ระดับ 2',
			'ความ<br>ร่วมมือ<br>ระดับ 3',
			'มูลค่า<br>การลงทุน<br>รวม(บาท)',
			'รับผู้เรียน<br>ฝึกงาน/<br>ฝึกอาชีพ',
			'รับผู้สำเร็จ<br>การศึกษา<br>เข้าทำงาน',
			//'หมายเหตุ',
		);
		if(isset($_POST['year'])){

			$caption='<b>'.$title.'<br> ปี พ.ศ. '.($_POST['year']+543).'</b><br>'.$org_name;


		    $zone=$locationModel->getZone();
		
        $resultRows=array();
			$business_ids=array();
		foreach($zone as $row){
            $school=$locationModel->getSchoolZone($row->zone_id);
            $org_id=array();
            foreach($school as $srow){
                $org_id[]=$srow->school_id;
            }

            $data=array(
                'year'=>$_POST['year'],
                'org_code'=>$org_id,
            );
			$businessCount=$mouModel->getBusinessCount($data);
			//print_r($businessCount);
			//print "<br>";
			foreach($businessCount as $k=>$v){
				$business_ids[$k]=$v;
			}
            $mouYear=$mouModel->getMouYearCount($data);
            $data['level']=1;
            $mouYearLevel1=$mouModel->getMouYearCount($data);
            $data['level']=2;
            $mouYearLevel2=$mouModel->getMouYearCount($data);
            $data['level']=3;
            $mouYearLevel3=$mouModel->getMouYearCount($data);
			
            $data=array(
                'year'=>$_POST['year'],
                'org_code'=>$org_id,
            );

            $traineeYear=$mouModel->getResultTraineeYear($data);
            $employeeYear=$mouModel->getResultEmployeeYear($data);
            $donateYear=$mouModel->getResultDonateYear($data);
            $resultRows[]=array(
				'zone'=>'ภาค'.$row->zone_name,
                'school'=>count($school),
				'business'=>number_format(count($businessCount),0),
                'mou'=>number_format($mouYear,0),
                'mouLevel1'=>number_format($mouYearLevel1,0),
                'mouLevel2'=>number_format($mouYearLevel2,0),
                'mouLevel3'=>number_format($mouYearLevel3,0),
                'donate'=>number_format($donateYear,0),
                'trainee'=>number_format($traineeYear,0),
                'employee'=>number_format($employeeYear,0),
                //'&nbsp;',
			);
		}
		$resultRows[]=array('zone'=>'<center>รวม</center>',
							'school'=>number_format(array_sum(str_replace(',','',array_column($resultRows,'school'))),0),
							'business'=>'*'.number_format(count($business_ids),0),
							'mou'=>number_format(array_sum(str_replace(',','',array_column($resultRows,'mou'))),0),
							'mouLevel1'=>number_format(array_sum(str_replace(',','',array_column($resultRows,'mouLevel1'))),0),
							'mouLevel2'=>number_format(array_sum(str_replace(',','',array_column($resultRows,'business'))),0),
							'mouLevel3'=>number_format(array_sum(str_replace(',','',array_column($resultRows,'mouLevel3'))),0),
							'donate'=>number_format(array_sum(str_replace(',','',array_column($resultRows,'donate'))),0),
							'trainee'=>number_format(array_sum(str_replace(',','',array_column($resultRows,'business'))),0),
							'employee'=>number_format(array_sum(str_replace(',','',array_column($resultRows,'employee'))),0),
	);

			$mouArr=array(
					'caption'=>$caption,
					'thead'=>$resultHead,
					'tbody'=>$resultRows,
			);
			$result=genTable($mouArr,$export=true,$noFoot=true);
			$result.='<u>หมายเหตุ</u> * สถานประกอบการหนึ่งแห่งสามารถทำ MOU ได้หลายฉบับ และสามารถทำ MOU กับสถานศึกษาได้หลายแห่ง หลายภาค';
		
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
}