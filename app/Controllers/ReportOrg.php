<?php

namespace App\Controllers;

class ReportOrg extends BaseController
{
	public function school_01($title,$print=false)
	{
		
		if(!isset($title))$title=$_POST['title'];
		helper('report');
		helper('table');
		helper('user');
		helper('org');
		helper('thai');
		helper('string');
		
		$org_code=isset($_POST['org_id'])?$_POST['org_id']:current_user('org_code');
		if($org_code=='all'){
			$org_name="สถานศึกษาทุกแห่ง ในสังกัด".org_name(current_user('org_code'));
			$org_id=$_SESSION['subORG'];
		}else if(mb_substr($org_code,0,1)=='p'){
			$province_code=mb_substr($org_code,1,mb_strlen($org_code));
			$org_name="สถานศึกษาทุกแห่ง ในจังหวัด".provinceName($province_code);
			$org_id=schoolInProvince($province_code);
		}else if(mb_substr($org_code,0,1)=='z'){
			$zone_id=mb_substr($org_code,1,mb_strlen($org_code));
			$org_name="สถานศึกษาทุกแห่ง ในภาค".zoneName($zone_id);
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
		$form=orgYearFilter($data);
		$result='';
		$resultHead=array(
			'ที่',
			'หน่วยงาน',
			'สถานประกอบการ',
			'ลักษณะงาน',
			'ระดับ<br>ความ<br>ร่วมมือ',
			'การร่วม<br>ลงทุน',
			'ระดับ<br>การ<br>ศึกษา',
			//'วันที่ลงนาม',
			'วันที่เริ่ม<br>ความ<br>ร่วมมือ',
			'วันที่สิ้นสุด<br>ความ<br>ร่วมมือ',
			'สถานที่ลงนาม',
			'การฝึกงาน',
		);
		if(!$print)array_push($resultHead,'ไฟล์ MOU');
		if(isset($_POST['year'])){

			$caption='<b>'.$title.'</b><br>ระหว่าง สถานประกอบการและ'.$org_name.'ปี พ.ศ. '.($_POST['year']+543).'<br>';//.$org_name;

			$mouModel = model('App\Models\MouModel');
			$resultData=$mouModel->getMou(['year'=>$_POST['year'],
											'school_id'=>$org_id]);
			
			$school=$resultData['school'];
			$school['1300000000']='สอศ.';
			$business=$resultData['business'];
			$gov=$resultData['gov'];
			$resultRows=array();
			$i=0;
			$orgArr=orgArr();
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
				if($mou['support_local_training']=='Y')$trainingPlace='ฝึกในประเทศ';
				if($mou['support_oversea_training']=='Y'){if($trainingPlace!='')$trainingPlace.='<br>'; $trainingPlace.='ฝึกงานต่างประเทศ';}

				$org_name='';
					if(isset($school[$mou['school_id']]))$org_name=$school[$mou['school_id']];
					else if(isset($gov[$mou['school_id']]))$org_name=$gov[$mou['school_id']];

					
				$arow=array(
					$i,
					'school_id'=>strlim($orgArr[$mou['school_id']],35),
					'business_id'=>strlim($business[$mou['business_id']]['business_name'],35),
					'job_description'=>strlim($business[$mou['business_id']]['job_description'],30),
					'level'=>isset($mou['level'])&&$mou['level']!=''?'ระดับ '.$mou['level']:'',
					'investment'=>strlim(isset($mou['investment'])&&$mou['investment']!=''?$mou['investment']:'ยังไม่มี',10),
					'support_edu'=>$supEdu,
					//'mou_date'=>dateThai($mou['mou_date']),
					'mou_start_date'=>dateThai($mou['mou_start_date']),
					'mou_end_date'=>$mou['no_expire']=='N'?dateThai($mou['mou_end_date']):'ไม่ได้ระบุ',
					'mou_sign_place'=>strlim($mou['mou_sign_place'],30),
					'note'=>$trainingPlace,
				);
				if(!$print)array_push($arow,'<a href="'.site_url('docs/mou/'.$mou['mou_file']).'" class="btn btn-danger" target="_blankf"><i class="material-icons">picture_as_pdf</i> ดูไฟล์</a>');
				$resultRows[]=$arow;
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
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ระดับ ๓ หมายถึง ดำเนินกิจกรรมเกี่ยวกับ CSR การฝึกงาน กิจกรรมเฉพาะกิจ (รวมระยะสั้น) และจัดการเรียนการสอนทวิภาคี และมีการร่วมลงทุนระหว่างสถานประกอบการและสถานศึกษา
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

	///// Report 2
	public function school_02($title,$print=false)
	{
		$skill=array(
			'1'=>'1. ยานยนต์สมัยใหม่',
			'2'=>'2. อิเล็กทรอนิกส์อัจฉริยะ',
			'3'=>'3. ท่องเที่ยวกลุ่มรายได้ดี',
			'4'=>'4. เกษตรและเทคโนโลยีชีวภาพ',
			'5'=>'5. แปรรูปอาหาร',
			'6'=>'6. หุ่นยนต์เพื่อการอุตสาหกรรม',
			'7'=>'7. การบินและโลจิสติกส์',
			'8'=>'8. เชื้อเพลิงชีวภาพเคมีชีวภาพ',
			'9'=>'9. ดิจิตอล',
			'10'=>'10. การแพทย์ครบวงจร',
		);
        $check='✔';
		if($print)$check='<img src="'.site_url('images/check.jpg').'" width="16">';
		
		if(!isset($title))$title=$_POST['title'];
		helper('report');
		helper('table');
		helper('user');
		helper('org');
		helper('thai');
		helper('string');
		
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

		$title='<b>รายงานการพัฒนาหลักสูตร</b><br>ระหว่าง สถานประกอบการและ'.$org_name;

		$data=array(
			'title'=>$title,
			'label'=>'ปีที่จัดอบรม',
			'org_ids'=>org_ids(),
		);
		$form=orgYearFilter($data);
		$result='';
		$resultHead=array(
			'ที่',
			'สถานประกอบการ',
			'ชื่อหลักสูตร',
			'ระดับ<br>ปวช.',
			'ระดับ<br>ปวส.',
			'ระดับ<br>ทล.บ.',
			'ระยะ<br>สั้น',
			'ไม่ระบุ<br>ระดับ',
			'Skill Gap',
			'S-Curve',
			'จำนวน<br>ชั่วโมง',
			'เป้าหมาย<br>(คน)',
			'ผู้เข้า<br>อบรม<br>(คน)',
			//'หมายเหตุ',
		);
		if(isset($_POST['year'])){

			$caption=$title.' ปี พ.ศ. '.($_POST['year']+543);

			$mouModel = model('App\Models\MouModel');
			$resultData=$mouModel->curriculumGet(['curriculum_year'=>$_POST['year'],
											'school_id'=>$org_id]);
			
			//$school=$resultData['school'];
			$business=$resultData['business'];
			//$gov=$resultData['gov'];
			$resultRows=array();
			$i=0;
			foreach ($resultData['curriculum'] as $cur){
				$i++;
				//$cur = get_object_vars($curriculum);

				$KG='';

				if($cur->skill_01=='Y'){$KG.=$skill['1'];}				
				if($cur->skill_02=='Y'){if($KG!='')$KG.='<br>';$KG.=$skill['2'];}				
				if($cur->skill_03=='Y'){if($KG!='')$KG.='<br>';$KG.=$skill['3'];}				
				if($cur->skill_04=='Y'){if($KG!='')$KG.='<br>';$KG.=$skill['4'];}				
				if($cur->skill_05=='Y'){if($KG!='')$KG.='<br>';$KG.=$skill['5'];}				
				if($cur->skill_06=='Y'){if($KG!='')$KG.='<br>';$KG.=$skill['6'];}				
				if($cur->skill_07=='Y'){if($KG!='')$KG.='<br>';$KG.=$skill['7'];}				
				if($cur->skill_08=='Y'){if($KG!='')$KG.='<br>';$KG.=$skill['8'];}				
				if($cur->skill_09=='Y'){if($KG!='')$KG.='<br>';$KG.=$skill['9'];}				
				if($cur->skill_10=='Y'){if($KG!='')$KG.='<br>';$KG.=$skill['10'];}

				$resultRows[]=array(
					$i,
					'business_id'=>strlim($business[$cur->business_id]['business_name'],35),
					'curriculum_name'=>strlim($cur->curriculum_name,25),	
					'support_vc_edu'=>$cur->support_vc_edu=='Y'?$check:'',
					'support_hvc_edu'=>$cur->support_hvc_edu=='Y'?$check:'',
					'support_btech_edu'=>$cur->support_btech_edu=='Y'?$check:'',
					'support_short_course'=>$cur->support_short_course=='Y'?$check:'',
					'support_no_specific'=>$cur->support_no_specific=='Y'?$check:'',	
					'Skill_Gap'=>strlim($cur->skill_gap,20),			
					'scurve'=>$KG,			
					'curriculum_hour'=>$cur->curriculum_hour,			
					'curriculum_target'=>$cur->curriculum_target,	
					'training_amount'=>$cur->training_amount,
					//'note'=>'',
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
				'fontsize'=>14,
				'marginL'=>20,
				'marginR'=>10,
				'marginT'=>10,
				'marginB'=>15,
				'header'=>'',
				'wartermark'=>'',
				'wartermarkimage'=>'',
				'footer'=>'เอกสารนี้ออกโดย'.SYSTEMNAME.' สำนักความร่วมมือ สำนักงานคณะกรรมการการอาชีวศึกษา '.date('Y-m-d H:i:s'),
				'header'=>'<div style="text-align: right; font-weight: normal;"><b>แบบฟอร์มที่ 3</b> <br> หน้า {PAGENO}/{nbpg}</div>'
			);
			$location=FCPATH.'/pdf/';
			$fname=$_POST['org_id'].'_school_01.pdf';
			$filePdf=genPdf($pdf_data,$pageNo=NULL,$location,$fname);
			//return '';
			return '<meta http-equiv="refresh" content="0;url='.site_url('public/pdf/'.$filePdf).'?'.time().'">';
		}
		return view('_main',$data);
	}

	//////// Report 3
	
	public function school_03($title,$print=false)
	{	
		if(!isset($title))$title=$_POST['title'];
		helper('report');
		helper('table');
		helper('user');
		helper('org');
		helper('thai');
		helper('string');
		
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

		if(mb_strlen($org_code)<10){
			$org_type_name=' อ.กรอ.อศ. ';
			$signData=array(
				'positionP1'=>'ผู้จัดทำข้อมูล',
				'positionP2'=>'อนุกรรมการและผู้ช่วยเลขานุการ อ.กรอ.อศ. <br>'.$org_name,
				'positionP3'=>'อนุกรรมการและเลขานุการ อ.กรอ.อศ.  <br>'.$org_name,
			);
		}else{
			$org_type_name='สถานศึกษา';
			$signData=array(
				'positionP1'=>'หัวหน้างานความร่วมมือ',
				'positionP2'=>'รองผู้อำนวยการฝ่ายแผนงานและความร่วมมือ<br>&nbsp;',
				'positionP3'=>'ผู้อำนวยการ'.$org_name.'<br>&nbsp;',
			);
		}

		
		$title='<b>รายงานผลสัมฤทธิ์ของการร่วมมือ</b><br>ระหว่างสถานประกอบการและ';
		$data=array(
			'title'=>$title,
			'label'=>'ปีที่เกิดผลสัมฤทธิ์',
			'org_ids'=>org_ids(),
		);
		$form=orgYearFilter($data);
		$result='';
		$resultHead=array(
			'ที่',
			'สถานประกอบการ',
			'สาขาที่รับ<br>นร.นศ. ฝึกงาน/ฝึกอาชีพ',
			'รับ<br>นร.นศ.<br> ฝึกงาน/<br>ฝึกอาชีพ <br>(คน)',
			'สาขาที่รับ<br>ผู้สำเร็จ<br>การศึกษา<br>เข้าทำงาน',
			'รับ<br>ผู้สำเร็จ<br>การศึกษา<br>เข้าทำงาน<br>(คน)',
			'การ<br>สนับสนุน<br>การศึกษา',
			'มูลค่า<br>การสนับสนุน',
			'การสนับสนุน<br>การศึกษา<br>ด้านอื่นๆ',
			//'หมายเหตุ',
		);
		if(isset($_POST['year'])){

			$caption=$title.$org_name.' ปี พ.ศ. '.($_POST['year']+543).'<br>';

			$mouModel = model('App\Models\MouModel');
			$resultData=$mouModel->resultGet(['result_year'=>$_POST['year'],
											'school_id'=>$org_id]);
			
			//$school=$resultData['school'];
			$business=$resultData['business'];
			//$gov=$resultData['gov'];
			$resultRows=array();
			$i=0;
			foreach ($resultData['result'] as $res){
				//$cur = get_object_vars($curriculum);
				$i++;
				$tm=explode(',',$res->trainee_majors);
				$t_m='';
				foreach($tm as $m){
					if($t_m!='')$t_m.=',<br>';
					$t_m.=strlim(trim($m),40);
				}

				$em=explode(',',$res->employee_majors);
				$e_m='';
				foreach($em as $m){
					if($e_m!='')$t_m.=',<br>';
					$e_m.=strlim(trim($m),25);
				}

				$resultRows[]=array(
					$i,
					'business_id'=>strlim($business[$res->business_id]['business_name'],35),
					'trainee_majors'=>$t_m,	
					'trainee_amount'=>$res->trainee_amount,
					'employee_majors'=>$e_m,
					'employee_amount'=>$res->employee_amount,
					'donate_detail'=>$res->donate_detail,
					'donate_value'=>'<div style="text-align:right;">'.number_format($res->donate_value,0,'.',',').($res->donate_value>0?' บาท':'').'</div>',
					'donate_other'=>$res->donate_other==''?'&nbsp;&nbsp;&nbsp;':$res->donate_other,
					//'note'=>'',
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
				'fontsize'=>14,
				'marginL'=>20,
				'marginR'=>10,
				'marginT'=>10,
				'marginB'=>15,
				'header'=>'',
				'wartermark'=>'',
				'wartermarkimage'=>'',
				'footer'=>'เอกสารนี้ออกโดย'.SYSTEMNAME.' สำนักความร่วมมือ สำนักงานคณะกรรมการการอาชีวศึกษา '.date('Y-m-d H:i:s'),
				'header'=>'<div style="text-align: right; font-weight: normal;">หน้า {PAGENO}/{nbpg}</div>'
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