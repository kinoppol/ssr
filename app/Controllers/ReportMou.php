<?php

namespace App\Controllers;

class ReportMou extends BaseController
{
	public function mou_01($title,$print=false)
	{
		
		if(!isset($title))$title=$_POST['title'];
		helper('report');
		helper('table');
		helper('user');
		helper('org');
		helper('thai');
		
		$org_code=isset($_POST['org_id'])?$_POST['org_id']:current_user('org_code');
		if($org_code=='all'){
			$org_name="สถานศึกษาทุกแห่งในสังกัด".org_name(current_user('org_code'));
			$org_id=$_SESSION['subORG'];
		}else if(mb_substr($org_code,0,1)=='p'){
			$province_code=mb_substr($org_code,1,mb_strlen($org_code));
			$org_name="สถานศึกษาทุกแห่งในจังหวัด".provinceName($province_code);
			$org_id=schoolInProvince($province_code);
		}else if(mb_substr($org_code,0,1)=='z'){
			$zone_id=mb_substr($org_code,1,mb_strlen($org_code));
			$org_name="สถานศึกษาทุกแห่งในภาค".zoneName($zone_id);
			$org_id=schoolInZone($zone_id);
		}else{
			$org_name=isset($_POST['org_id'])?org_name($_POST['org_id']):org_name(current_user('org_code'));
			$org_id=$org_code;
		}
		$data=array(
			'org_code'=>$org_code=='all'||!is_numeric($org_code)?current_user('org_code'):$org_code,
			'org_name'=>$org_code=='all'||!is_numeric($org_code)?org_name(current_user('org_code')):$org_name,
		);
		$org_type_name=org_type_name($data);
		$signBox=signBox($data);

		$data=array(
			'title'=>$title,
			'label'=>'ปีที่ลงนาม',
			'org_ids'=>org_ids(),
		);
		$form=yearFilter($data);
		$result='';
		$resultHead=array(
			'ที่',
			'สถานประกอบการ',
			'ลักษณะงาน',
			'ระดับ<br>ความร่วมมือ',
			'การร่วมลงทุน<br>กับ'.$org_type_name,
			'ระดับ<br>การศึกษา',
			//'วันที่ลงนาม',
			'วันที่เริ่ม<br>ความร่วมมือ',
			'วันที่สิ้นสุด<br>ความร่วมมือ',
			'สถานที่ลงนาม',
			'หมายเหตุ',
		);
		if(isset($_POST['year'])){

			$caption='<b>'.$title.'</b><br>ระหว่างสถานประกอบการและ'.$org_name.' ปี พ.ศ. '.($_POST['year']+543).'<br>';

			$mouModel = model('App\Models\MouModel');
			$resultData=$mouModel->getMou(['year'=>$_POST['year'],
											'school_id'=>$org_id]);
			
			$school=$resultData['school'];
			$business=$resultData['business'];
			$gov=$resultData['gov'];
			$resultRows=array();
			$i=0;
			foreach ($resultData['mou'] as $mou){
				$mou = get_object_vars($mou);

				if(!isset($business[$mou['business_id']]))continue;
				$i++;

				$supEdu='';
				if($mou['support_vc_edu']=='Y')$supEdu='ปวช.';
				if($mou['support_hvc_edu']=='Y'){if($supEdu!='')$supEdu.='<br>'; $supEdu.='ปวส.';}
				if($mou['support_btech_edu']=='Y'){if($supEdu!='')$supEdu.='<br>'; $supEdu.='ทล.บ.';}
				if($mou['support_short_course']=='Y'){if($supEdu!='')$supEdu.='<br>'; $supEdu.='ระยะสั้น';}
				if($mou['support_no_specific']=='Y'){if($supEdu!='')$supEdu.='<br>'; $supEdu.='ไม่ระบุ';}

				$trainingPlace='';
				if($mou['support_local_training']=='Y')$trainingPlace='ฝึกงานในประเทศ';
				if($mou['support_oversea_training']=='Y'){if($trainingPlace!='')$trainingPlace.='<br>'; $trainingPlace.='ฝึกงานต่างประเทศ';}

				$org_name='';
					if(isset($school[$mou['school_id']]))$org_name=$school[$mou['school_id']];
					else if(isset($gov[$mou['school_id']]))$org_name=$gov[$mou['school_id']];

					
				$resultRows[]=array(
					$i,
					'business_id'=>$business[$mou['business_id']]['business_name'],
					'job_description'=>$business[$mou['business_id']]['job_description'],
					'level'=>isset($mou['level'])&&$mou['level']!=''?'ระดับ '.$mou['level']:'',
					'investment'=>isset($mou['investment'])&&$mou['investment']!=''?$mou['investment']:'ยังไม่มี',
					'support_edu'=>$supEdu,
					//'mou_date'=>dateThai($mou['mou_date']),
					'mou_start_date'=>dateThai($mou['mou_start_date']),
					'mou_end_date'=>dateThai($mou['mou_end_date']),
					'mou_sign_place'=>$mou['mou_sign_place'],
					'note'=>$trainingPlace,
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
			$result.='
			<table width="100%">
			<tr>
			<td>
			<u>หมายเหตุ</u><br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ระดับ ๑ หมายถึง ดำเนินกิจกรรมเกี่ยวกับ CSR การฝึกงาน กิจกรรมเฉพาะกิจ (รวมระยะสั้น)<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ระดับ ๒ หมายถึง ดำเนินกิจกรรมเกี่ยวกับ CSR การฝึกงาน กิจกรรมเฉพาะกิจ (รวมระยะสั้น) และจัดการเรียนการสอนทวิภาคี<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ระดับ ๓ หมายถึง ดำเนินกิจกรรมเกี่ยวกับ CSR การฝึกงาน กิจกรรมเฉพาะกิจ (รวมระยะสั้น) และจัดการเรียนการสอนทวิภาคี และมีการร่วมลงทุนระหว่างสถานประกอบการ และสถานศึกษา
			</td>
			</tr>
			</table>
			'.$signBox;
			error_reporting(0);
			helper('mpdf');
			//return $result;
			$pdf_data=array(
				'html'=>$result,
				'size'=>"A4-L",
				'fontsize'=>14,
				'marginL'=>20,
				'marginR'=>10,
				'marginT'=>25,
				'marginB'=>15,
				'header'=>'',
				'wartermark'=>'',
				'wartermarkimage'=>'',
				'footer'=>'เอกสารนี้ออกโดย'.SYSTEMNAME.' สำนักความร่วมมือ สำนักงานคณะกรรมการการอาชีวศึกษา '.date('Y-m-d H:i:s'),
				'header'=>'<div style="text-align: right; font-weight: normal;"><b>แบบฟอร์มที่ 4</b> <br> หน้า{PAGENO}/{nbpg}</div>'
			);
			$location=FCPATH.'/pdf/';
			$fname=$_POST['org_id'].'_school_01.pdf';
			$filePdf=genPdf($pdf_data,$pageNo=NULL,$location,$fname);
			//return '';
			return '<meta http-equiv="refresh" content="0;url='.site_url('public/pdf/'.$filePdf).'?'.time().'">';
		}
		return view('_main',$data);
	}

	public function mou_02($title,$print=false)
	{
		
		if(!isset($title))$title=$_POST['title'];
		helper('report');
		helper('table');
		helper('user');
		helper('org');
		helper('thai');
		helper('org');
		
		$org_code=isset($_POST['org_id'])?$_POST['org_id']:current_user('org_code');
		if($org_code=='all'){
			$org_name="สถานศึกษาทุกแห่งในสังกัด".org_name(current_user('org_code'));
			$org_id=$_SESSION['subORG'];
		}else if(mb_substr($org_code,0,1)=='p'){
			$province_code=mb_substr($org_code,1,mb_strlen($org_code));
			$org_name="สถานศึกษาทุกแห่งในจังหวัด".provinceName($province_code);
			$org_id=schoolInProvince($province_code);
		}else if(mb_substr($org_code,0,1)=='z'){
			$zone_id=mb_substr($org_code,1,mb_strlen($org_code));
			$org_name="สถานศึกษาทุกแห่งในภาค".zoneName($zone_id);
			$org_id=schoolInZone($zone_id);
		}else{
			$org_name=isset($_POST['org_id'])?org_name($_POST['org_id']):org_name(current_user('org_code'));
			$org_id=$org_code;
		}
		$data=array(
			'org_code'=>$org_code=='all'||!is_numeric($org_code)?current_user('org_code'):$org_code,
			'org_name'=>$org_code=='all'||!is_numeric($org_code)?org_name(current_user('org_code')):$org_name,
		);
		$org_type_name=org_type_name($data);
		$signBox=signBox($data);

		$locationModel = model('App\Models\LocationModel');
		$province=$locationModel->getProvince();
		$businessModel = model('App\Models\BusinessModel');
		$businessData=$businessModel->listBusiness(['withMOU'=>'Y']);
		$business=array();
		foreach($businessData as $row){
			$business_filter[$row['business_id']]=$row['business_name'].' ('.$province[$row['province_id']].')';
			$business[$row['business_id']]=$row['business_name'];
		}
		$data=array(
			'title'=>$title,
			'label'=>'ปีที่ลงนาม',
			'business'=>$business_filter,
		);
		$form=businessYearFilter($data);
		$result='';
		$resultHead=array(
			'ที่',
			'หน่วยงาน',
			'ระดับ<br>ความร่วมมือ',
			'การร่วมลงทุน',
			'ระดับ<br>การศึกษา',
			//'วันที่ลงนาม',
			'วันที่เริ่ม<br>ความร่วมมือ',
			'วันที่สิ้นสุด<br>ความร่วมมือ',
			'สถานที่ลงนาม',
			'การฝึกงาน',
		);
		if(isset($_POST['year'])){
			$orgData=orgArr();
			//print_r($_POST);
			$business_name=$_POST['business_id']==0?'':$business[$_POST['business_id']];
			$yearWord=$_POST['year']==0?'':'ประจำปี พ.ศ. '.($_POST['year']+543);
			$caption='<b>รายงานการลงนามความร่วมมือระหว่างสถานศึกษา สถาบันการอาชีวศึกษา อ.กรอ.อศ สำนักงานคณะกรรมการการอาชีวศึกษา<br>
			และ '.$business_name.' '.$yearWord.'</b><br>';

			$mouModel = model('App\Models\MouModel');
			$resultData=$mouModel->getMou(['year'=>$_POST['year']==0?false:$_POST['year'],
											'business_id'=>$_POST['business_id']==0?false:array($_POST['business_id'])]);
			
			$school=$resultData['school'];
			$business=$resultData['business'];
			$gov=$resultData['gov'];
			$resultRows=array();
			$i=0;
			foreach ($resultData['mou'] as $mou){
				$mou = get_object_vars($mou);

				if(!isset($orgData[$mou['school_id']]))continue;
				$i++;

				$supEdu='';
				if($mou['support_vc_edu']=='Y')$supEdu='ปวช.';
				if($mou['support_hvc_edu']=='Y'){if($supEdu!='')$supEdu.='<br>'; $supEdu.='ปวส.';}
				if($mou['support_btech_edu']=='Y'){if($supEdu!='')$supEdu.='<br>'; $supEdu.='ทล.บ.';}
				if($mou['support_short_course']=='Y'){if($supEdu!='')$supEdu.='<br>'; $supEdu.='ระยะสั้น';}
				if($mou['support_no_specific']=='Y'){if($supEdu!='')$supEdu.='<br>'; $supEdu.='ไม่ระบุ';}

				$trainingPlace='';
				if($mou['support_local_training']=='Y')$trainingPlace='ฝึกในประเทศ';
				if($mou['support_oversea_training']=='Y'){if($trainingPlace!='')$trainingPlace.='<br>'; $trainingPlace.='ฝึกต่างประเทศ';}

				$org_name='';
					if(isset($school[$mou['school_id']]))$org_name=$school[$mou['school_id']];
					else if(isset($gov[$mou['school_id']]))$org_name=$gov[$mou['school_id']];

					
				$resultRows[]=array(
					$i,
					'org_name'=>$orgData[$mou['school_id']],
					//'job_description'=>$business[$mou['business_id']]['job_description'],
					'level'=>isset($mou['level'])&&$mou['level']!=''?'ระดับ '.$mou['level']:'',
					'investment'=>isset($mou['investment'])&&$mou['investment']!=''?$mou['investment']:'ยังไม่มี',
					'support_edu'=>$supEdu,
					//'mou_date'=>dateThai($mou['mou_date']),
					'mou_start_date'=>dateThai($mou['mou_start_date']),
					'mou_end_date'=>$mou['no_expire']=='N'?dateThai($mou['mou_end_date']):'ไม่ได้ระบุ',
					'mou_sign_place'=>$mou['mou_sign_place'],
					'note'=>$trainingPlace,
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
			$result.='
			<table width="100%">
			<tr>
			<td>
			<u>หมายเหตุ</u><br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ระดับ ๑ หมายถึง ดำเนินกิจกรรมเกี่ยวกับ CSR การฝึกงาน กิจกรรมเฉพาะกิจ (รวมระยะสั้น)<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ระดับ ๒ หมายถึง ดำเนินกิจกรรมเกี่ยวกับ CSR การฝึกงาน กิจกรรมเฉพาะกิจ (รวมระยะสั้น) และจัดการเรียนการสอนทวิภาคี<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ระดับ ๓ หมายถึง ดำเนินกิจกรรมเกี่ยวกับ CSR การฝึกงาน กิจกรรมเฉพาะกิจ (รวมระยะสั้น) และจัดการเรียนการสอนทวิภาคี และมีการร่วมลงทุนระหว่างสถานประกอบการ และสถานศึกษา
			</td>
			</tr>
			</table>
			'.$signBox;
			error_reporting(0);
			helper('mpdf');
			//return $result;
			$pdf_data=array(
				'html'=>$result,
				'size'=>"A4-L",
				'fontsize'=>14,
				'marginL'=>20,
				'marginR'=>10,
				'marginT'=>25,
				'marginB'=>15,
				'header'=>'',
				'wartermark'=>'',
				'wartermarkimage'=>'',
				'footer'=>'เอกสารนี้ออกโดย'.SYSTEMNAME.' สำนักความร่วมมือ สำนักงานคณะกรรมการการอาชีวศึกษา '.date('Y-m-d H:i:s'),
				'header'=>'<div style="text-align: right; font-weight: normal;"><b>แบบฟอร์มที่  11</b> <br> หน้า{PAGENO}/{nbpg}</div>'
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