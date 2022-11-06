<?php
helper('form');
helper('user');
//print_r($registerData);
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
//print_r($orgData);
$orgData=array(
    'สอศ.'=>$vec,
    'เลขานุการ กรอ.อศ.'=>$orgGovs,
    'สถานศึกษา'=>$orgSchools,
    'สถาบันการอาชีวศึกษา'=>$orgInstitutes,
);

$userType=array(
    'school'=>'สถานศึกษา',
    'gov'=>'อ.กรอ.อศ.',
    'institute'=>'สถาบันการอาชีวศึกษา',
    'boc'=>'สำนักความร่วมมือ',
    'board'=>'ผู้บริหารสำนักงานคณะกรรมการอาชีวศึกษา',
);

$data=array(array(
                    'label'=>'ชื่อ',
                    'type'=>'text',
                    'id'=>'name',
                    'def'=>current_user('name'),
                    'required'=>true,
                ),
                array(
                    'label'=>'ชนามสกุล',
                    'type'=>'text',
                    'id'=>'surname',
                    'def'=>current_user('surname'),
                    'required'=>true,
                ),
                array(
                    'label'=>'ตำแหน่ง',
                    'type'=>'text',
                    'id'=>'position',
                    'required'=>true,
                ),
                array(
                    'label'=>'หมายเลขโทรศัพท์',
                    'type'=>'text',
                    'id'=>'tel',
                    'required'=>true,
                ),
                array(
                    'label'=>'อีเมล',
                    'type'=>'email',
                    'id'=>'email',
                    'def'=>current_user('email'),
                    'required'=>true,
                ),
                array(
                'label'=>'ประเภทผู้ใช้งาน',
                'type'=>'select',
                'id'=>'user_type',
                'class'=>'show-tick',
                'search'=>'true',
                'items'=>$userType,
                'noneLabel'=>'โปรดเลือกประเภทผู้ใช้งาน',
                'required'=>true,
                'def'=>isset($registerData->user_type)?$registerData->user_type:false,
            ),
            array(
                'label'=>'ต้นสังกัด/สถานศึกษา/กลุ่มอาชีพ',
                'type'=>'select',
                'id'=>'org_code',
                'class'=>'show-tick',
                'search'=>'true',
                'items'=>$orgData,
                'noneLabel'=>'โปรดเลือกต้นสังกัด/สถานศึกษา/กลุ่มอาชีพ',
                'required'=>true,
                'def'=>isset($registerData->org_code)?$registerData->org_code:false,
            ),
            array(
                'label'=>'ลงทะเบียนผู้ใช้งาน',
                'type'=>'submit',
            ),
        );
$form=array(
    'formName'=>'ลงทะเบียนผู้ใช้งานใหม่/แก้ไขการลงทะเบียน',
    'inputs'=>$data,
    'action'=>site_url('public/user/register'),
    'method'=>'post',
);
?>
<div class="card">
    <div class="header">
        <h2>
            ข้อมูลการลงทะเบียน
        </h2>
        <div class="body">
        <?php
            if(!isset($registerData->user_type)){
                print "คุณยังไม่ได้ลงทะเบียน กรุณาดำเนินการลงทะเบียนด้วยแบบฟอร์มด้านล่าง";
            }else{
                print "คุณได้ทำการลงทะเบียนในฐานะ <b>";
                print user_type($registerData->user_type);
                print "</b>";
                print " สังกัด ".$orgs[$registerData->org_code];
            }
            print " สถานะ ";
            switch($registerData->register_status){
                case 'request' : print '<button class="btn btn-primary">รอการอนุมัติ</button>'; break;
                case 'disapproval' : print '<button class="btn btn-danger">ปฏิเสธการลงทะเบียน</button> โปรดทำการลงทะเบียนใหม่'; break;
                case 'banned' : print '<button class="btn btn-default">ห้ามลงทะเบียน</button>'; break;
                case 'approved' : print '<a href="'.site_url('public/user/logout').'" class="btn btn-success">อนุมัติแล้ว</a> คลิกที่ปุ่มเพื่อเข้าสู่ระบบใหม่'; break;
            }
        ?>
        </div>
    </div>
</div>
<?php
if($registerData->register_status!='banned'&&$registerData->register_status!='approved')
print genForm($form);
?>