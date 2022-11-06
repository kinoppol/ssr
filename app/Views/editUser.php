<?php
    //print_r($userData);
    $vec=array(
        '1300000000'=>'สำนักงานคณะกรรมการอาชีวศึกษา',
        //'1300000001'=>'สำนักความร่วมมือ',
    );
    $orgs=$vec;
    $orgSchools=array();
    foreach($schools as $k=>$v){
        $orgSchools[$k]=$v;
        $orgs[$k]=$v;
    }
    
    $orgGovs=array();
    foreach($govs as $k=>$v){
        $orgGovs[$k]=$v;
        $orgs[$k]=$v;
    }
    
    $orgInstitutes=array();
    foreach($institutes as $k=>$v){
        $orgInstitutes[$k]=$v;
        $orgs[$k]=$v;
    }

    $orgData=array(
        'สอศ.'=>$vec,
        'เลขานุการ กรอ.อศ.'=>$orgGovs,
        'สถานศึกษา'=>$orgSchools,
        'สถาบันการอาชีวศึกษา'=>$orgInstitutes,
    );
    
    helper('form');
    $data=array(array(
        'label'=>'ชื่อผู้ใช้งาน',
        'type'=>'text',
        'id'=>'username',
        'def'=>$userData->username,
        'disabled'=>false,
         ),
         array(
        'type'=>'hidden',
        'id'=>'user_id',
        'def'=>$userData->user_id,
         ),
         array(
        'label'=>'รหัสผ่าน (ว่างไว้หากไม่ต้องการเปลี่ยน)',
        'type'=>'password',
        'id'=>'password',
        'autocomplete'=>'new-password',
        'def'=>'',
         ),
         array(
        'label'=>'ยืนยันรหัสผ่าน (ว่างไว้หากไม่ต้องการเปลี่ยน)',
        'type'=>'password',
        'id'=>'confirm_password',
        'autocomplete'=>'new-password',
        'def'=>'',
         ),
         array(
        'label'=>'ชื่อ',
        'type'=>'text',
        'id'=>'name',
        'def'=>$userData->name,
        'required'=>true,
         ),
         array(
        'label'=>'สกุล',
        'type'=>'text',
        'id'=>'surname',
        'def'=>$userData->surname,
        'required'=>true,
         ),
         array(
        'label'=>'สกุล',
        'type'=>'text',
        'id'=>'email',
        'def'=>$userData->email,
        'required'=>true,
         ),
         array(
        'label'=>'สถานะ',
        'type'=>'select',
        'id'=>'user_active',
        //'class'=>'show-tick',
        'items'=>array(
            'Y'=>'ใช้งานปรกติ',
            'N'=>'ยังไม่อนุมัติใช้งาน',
            'B'=>'ระงับการใช้งาน',
            ),
        'def'=>$userData->user_active,
        'required'=>true,
         ),
         array(
        'label'=>'ประเภทผู้ใช้งาน',
        'type'=>'select',
        'id'=>'user_type',
        //'class'=>'show-tick',
        'items'=>array(
            'admin'=>'ผู้ดูและระบบ',
            'board'=>'ผู้บริหาร สอศ.',
            'boc'=>'สำนักความร่วมมือ',
            'gov'=>'อ.กรอ.อศ.',
            'institute'=>'สถาบันการอาชีวศึกษา',
            'school'=>'สถานศึกษา',
            'user'=>'ผู้ใช้งานที่ยังไม่ลงทะเบียน',
            ),
        'def'=>$userData->user_type,
        'required'=>true,
         ),
         array(
             'label'=>'ต้นสังกัด/สถานศึกษา/กลุ่มอาชีพ',
             'type'=>'select',
             'id'=>'org_code',
             'class'=>'show-tick',
             'items'=>$orgData,
             //'noneLabel'=>'โปรดเลือกต้นสังกัด/สถานศึกษา/กลุ่มอาชีพ',
             'required'=>true,
             'search'=>true,
             'def'=>isset($userData->org_code)?$userData->org_code:false,
         ),
         array(
             'label'=>'บันทึกข้อมูล',
             'type'=>'submit',
         ),
    );

    $form=array(
        'formName'=>'ข้อมูลผู้ใช้',
        'inputs'=>$data,
        'action'=>site_url('public/admin/saveUser'),
        'method'=>'post',
        'enctype'=>'multipart/form-data',
    );
    
    print genForm($form);

    $_SESSION['FOOTSYSTEM'].='
    <script>
    $(document).ready(function() {
        $(\'.js-example-basic-single\').select2();
    });
    <script>';