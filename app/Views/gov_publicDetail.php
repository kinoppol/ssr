<?php

helper('tab');
helper('user');
    //print_r($schoolData);
    $years=array();
    for($i=date('Y')+1;$i>(date('Y')-5);$i--){
        $years[$i]=$i+543;
    }
    $businessData=array();
    foreach($mouData['business'] as $k=>$v){
        $businessData[$k]=$v['business_name'];
    }
    helper('form');
    $data=array(
        array(
           'type'=>'hidden',
           'id'=>'gov_id',
           'def'=>current_user('org_code'),
           ),
        array(
           'type'=>'hidden',
           'id'=>'id',
           'def'=>isset($publicData->id)?$publicData->id:'',
           ),
        array(
           'label'=>'สถานที่ดำเนินการประชาสัมพันธ์',
           'type'=>'text',
           'id'=>'public_place',
           'def'=>isset($publicData->public_place)?$publicData->public_place:'',
           'placeholder'=>'เช่น โรงเรียนไทยรัฐ',
            ),
        array(
           'label'=>'รูปแบบการประชาสัมพันธ์',
           'type'=>'text',
           'id'=>'public_method',
           'def'=>isset($publicData->public_method)?$publicData->public_method:'',
           'placeholder'=>'เช่น ออกบูท ประกาศ ป้ายประชาสัมพันธ์',
            ),    
        array(
            'label'=>'สถานประกอบการที่มีส่วนร่วม',
            'type'=>'select',
            'items'=>$businessData,
            'class'=>'show-tick',
            'noneLabel'=>'โปรดเลือกสถานประกอบการทุกแห่งที่มีส่วนร่วม',
            'id'=>'business_id',
            'multiple'=>true,
            'def'=>isset($publicData->business_id)?explode(',',$publicData->business_id):'',
            ),  
        array(
           'label'=>'วันที่เริ่มประชาสัมพันธ์',
           'type'=>'date',
           'id'=>'start_date',
           'def'=>isset($publicData->start_date)?$publicData->start_date:date('Y-m-d'),
            ),  
        array(
           'label'=>'วันที่สิ้นสุดการประชาสัมพันธ์',
           'type'=>'date',
           'id'=>'end_date',
           'def'=>isset($publicData->end_date)?$publicData->end_date:date('Y-m-d'),
            ),  
        array(
           'label'=>'จำนวนผู้เรียนเป้าหมาย (คน)',
           'type'=>'number',
           'id'=>'student_target',
           'placeholder'=>'จำนวนผู้เรียนที่คาดหวัง',
           'def'=>isset($publicData->student_target)?$publicData->student_target:'',
            ),     
        array(
           'label'=>'ผู้เรียนที่สมัครเรียน (คน)',
           'type'=>'number',
           'id'=>'student_apply',
           'placeholder'=>'จำนวนผู้เรียนที่สมัครเรียน',
           'def'=>isset($publicData->student_apply)?$publicData->student_apply:'',
            ), 
        array(
            'label'=>'แนบไฟล์เอกสารประกอบ(สแกนเป็น PDF ไฟล์)',
            'type'=>'file',
            'id'=>'attach_file',
            'accept'=>'application/pdf',
            'def'=>'',
        ),
        array(
            'label'=>'แนบไฟล์รูปภาพ (โปรดแนบรูปอย่างน้อย 2 รูป)',
            'type'=>'file',
            'id'=>'pictures',
            'accept'=>'image/jpeg,image/png',
            'def'=>'',
            'multiple'=>true,
        ),
        array(
            'label'=>'บันทึกข้อมูล',
            'type'=>'submit',
        ),
            ); 

    $form=array(
        'formName'=>'ข้อมูลการประชาสัมพันธ์',
        'inputs'=>$data,
        'action'=>site_url('public/gov/publicSave'),
        'method'=>'post',
        'enctype'=>'multipart/form-data',
    );
    
    print genForm($form);