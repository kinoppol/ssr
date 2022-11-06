<?php

namespace App\Controllers;

class ReportVec extends BaseController
{
	public function vec_01($title,$print=false)
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
			$org_name="หน่วยงานและสถานศึกษาทุกแห่ง ในสังกัด".org_name(current_user('org_code'));
			$org_id=allOrg();
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
			'label'=>'ปีที่ดำเนินการ',
			'org_ids'=>org_ids(),
		);
        
		$form=orgYearFilter($data);
		$result='';
		$resultHead=array(
			'ที่',
			'ชื่อหน่วยงาน',
			'ข้อมูลการลงนามความร่วมมือ',
			'รายงานการประชุม',
			'หมายเหตุ',
		);
		if(isset($_POST['year'])){

			$caption='<b>'.$title.'</b><br>'.$org_name.' ในปี พ.ศ. '.($_POST['year']+543).'<br>';//.$org_name;

			$mouModel = model('App\Models\MouModel');
			$resultData=$mouModel->getMou(['year'=>$_POST['year'],
											'school_id'=>$org_id]);
			

            $govModel = model('App\Models\GovModel');

			$school=$resultData['school'];
			$business=$resultData['business'];
			$gov=$resultData['gov'];
			$resultRows=array();
			$i=0;
            //print_r($org_id);
            if(!is_array($org_id))$org_id=array($org_id);
			foreach ($org_id as $orgId){
                $i++;
                $meettingRecord=$govModel->getMeetting(['year'=>$_POST['year'],
                                                        'gov_id'=>$orgId]);
                $resultData=$mouModel->getMouCount(['year'=>$_POST['year'],
											'org_code'=>$orgId]);

				$resultRows[]=array(
					$i,
					'orgName'=>org_name($orgId),
                    'mouCount'=>$resultData,
                    'meetCount'=>count($meettingRecord),
                    ''
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

}