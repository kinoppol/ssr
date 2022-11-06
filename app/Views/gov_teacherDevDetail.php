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
           'def'=>isset($teacherDevData->id)?$teacherDevData->id:'',
           ),
        array(
           'label'=>'สถานที่ดำเนินการอบรม',
           'type'=>'text',
           'id'=>'dev_place',
           'def'=>isset($teacherDevData->dev_place)?$teacherDevData->dev_place:'',
           'placeholder'=>'เช่น โรงเอเชีย',
           'required'=>true,
            ),
        array(
           'label'=>'หัวข้อการอบรม',
           'type'=>'text',
           'id'=>'subject',
           'def'=>isset($teacherDevData->subject)?$teacherDevData->subject:'',
           'placeholder'=>'เช่น การเขียนแผนฝึกอาชีพ',
           'required'=>true,
            ),    
        array(
           'label'=>'วันที่เริ่มการอบรม',
           'type'=>'date',
           'id'=>'start_date',
           'def'=>isset($teacherDevData->start_date)?$teacherDevData->start_date:date('Y-m-d'),
           'required'=>true,
            ),  
        array(
           'label'=>'วันที่สิ้นสุดการอบรม',
           'type'=>'date',
           'id'=>'end_date',
           'def'=>isset($teacherDevData->end_date)?$teacherDevData->end_date:date('Y-m-d'),
           'required'=>true,
            ),  
        array(
           'label'=>'จำนวนผู้เข้ารับการอบรม',
           'type'=>'number',
           'id'=>'person',
           'def'=>isset($studentDevData->person)?$studentDevData->person:0,
           'required'=>true,
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
            'id'=>'picture',
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
        'formName'=>'ข้อมูลการอบรมครูผู้สอนในสถานศึกษา',
        'inputs'=>$data,
        'action'=>site_url('public/gov/teacherDevSave'),
        'method'=>'post',
        'enctype'=>'multipart/form-data',
    );
    
    print genForm($form);