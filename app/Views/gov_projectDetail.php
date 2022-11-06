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
           'def'=>isset($projectData->id)?$projectData->id:'',
           ),
        array(
           'label'=>'ชื่อโครงการ',
           'type'=>'text',
           'id'=>'subject',
           'def'=>isset($projectData->subject)?$projectData->subject:'',
           'placeholder'=>'เช่น การศึกษาดูงาน',
           'required'=>true,
            ),   
        array(
           'label'=>'สถานที่ดำเนินการโครงการ',
           'type'=>'text',
           'id'=>'project_place',
           'def'=>isset($projectData->project_place)?$projectData->project_place:'',
           'placeholder'=>'เช่น โรงเอเชีย',
           'required'=>true,
            ),
        array(
           'label'=>'วิธีดำเนินโครงการ',
           'type'=>'text',
           'id'=>'method',
           'def'=>isset($projectData->method)?$projectData->method:'',
           'placeholder'=>'เช่น การอบรบเชิงปฏิบติการ',
           'required'=>true,
            ),
        array(
           'label'=>'วัตถุประสงค์ของโครงการ',
           'type'=>'textarea',
           'id'=>'objective',
           'def'=>isset($projectData->objective)?$projectData->objective:'',
           'placeholder'=>'เช่น เพื่อพัฒนาสภาพแวดล้อม',
           'required'=>true,
            ),    
        array(
           'label'=>'กลุ่มเป้าหมาย',
           'type'=>'text',
           'id'=>'target',
           'def'=>isset($projectData->target)?$projectData->target:'',
           'placeholder'=>'เช่น นักเรียน/นักศึกษา ครู บุคลากร',
           'required'=>true,
            ),    
        array(
           'label'=>'จำนวนเป้าหมาย (คน)',
           'type'=>'number',
           'min'=>'0',
           'id'=>'target_amount',
           'def'=>isset($projectData->target_amount)?$projectData->target_amount:'',
           'placeholder'=>'ระบุจำนวนคน',
           'required'=>true,
            ),  
        array(
           'label'=>'ผลผลิต',
           'type'=>'text',
           'id'=>'product',
           'def'=>isset($projectData->product)?$projectData->product:'',
           'placeholder'=>'เช่น หลักสูตรระยะสั้น จำนวน 12 หลักสูตร',
           'required'=>true,
            ),    
        array(
           'label'=>'ผลลัพธ์',
           'type'=>'text',
           'id'=>'result',
           'def'=>isset($projectData->result)?$projectData->result:'',
           'placeholder'=>'เช่น ผู้เรียนมีทักษะเฉพาะทางเพิ่มขึ้น',
           'required'=>true,
            ),    
        array(
           'label'=>'วันที่เริ่มดำเนินโครงการ',
           'type'=>'date',
           'id'=>'start_date',
           'def'=>isset($projectData->start_date)?$projectData->start_date:date('Y-m-d'),
           'required'=>true,
            ),  
        array(
           'label'=>'วันที่สิ้นสุดการดำเนินโครงการ',
           'type'=>'date',
           'id'=>'end_date',
           'def'=>isset($projectData->end_date)?$projectData->end_date:date('Y-m-d'),
           'required'=>true,
            ),  
        array(
           'label'=>'บันทึกเพิ่มเติม (หมายเหตุ)',
           'type'=>'text',
           'id'=>'note',
           'def'=>isset($projectData->note)?$projectData->note:'',
           'placeholder'=>'ถ้ามีโปรดระบุ',
            ),  
        array(
            'label'=>'แนบไฟล์เอกสารประกอบ(สแกนเป็น PDF ไฟล์)',
            'type'=>'file',
            'id'=>'attach_file',
            'accept'=>'application/pdf',
            'def'=>'',
            'multiple'=>true,
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
        'formName'=>'ข้อมูลการดำเนินโครงการอื่น ๆ ',
        'inputs'=>$data,
        'action'=>site_url('public/gov/projectSave'),
        'method'=>'post',
        'enctype'=>'multipart/form-data',
    );
    
    print genForm($form);