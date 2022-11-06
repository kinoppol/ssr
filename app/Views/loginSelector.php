<body class="loginSelector-page" style="font-family: 'Kanit', sans-serif;">
<div class="row clearfix">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                    <div class="header" style="text-align:center">
                            <h2>
                                <?php print SYSTEMNAME; ?>   
                            </h2>
                    </div>
                    <div class="body">
                       <div style="text-align:center"> <h4>โปรดเลือกประเภทผู้ใช้ (หากเข้าใช้งานครั้งแรกเลือก "ลงทะเบียน")</h4></div>
                       <br>
                    <div class="input-group">
<?php
helper('widget');
$data=array(
    'newUser'=>array(
        'color'=>'blue-grey',
        'text'=>'ลงทะเบียน<br>(ครั้งแรกเท่านั้น)',
        'number'=>'5',
        'icon'=>'person_add',
        'url'=>site_url('public/user/registerNewUser'),
        'class'=>'col-lg-6 col-md-12 col-sm-12 col-xs-12',
    ),
    'school'=>array(
        'color'=>'green',
        'text'=>'สถานศึกษา ',
        'number'=>'5',
        'icon'=>'school',
        'url'=>site_url('public/user/login/school'),
        'class'=>'col-lg-6 col-md-12 col-sm-12 col-xs-12',
    ),
    'gov'=>array(
        'color'=>'blue',
        'text'=>'อ.กรอ.อศ.',
        'number'=>'5',
        'icon'=>'people',
        'url'=>site_url('public/user/login/gov'),
        'class'=>'col-lg-6 col-md-12 col-sm-12 col-xs-12',
    ),
    'institute'=>array(
        'color'=>'pink',
        'text'=>'สถาบันการอาชีวศึกษา',
        'number'=>'5',
        'icon'=>'account_balance',
        'url'=>site_url('public/user/login/institute'),
        'class'=>'col-lg-6 col-md-12 col-sm-12 col-xs-12',
    ),
    'boc'=>array(
        'color'=>'orange',
        'text'=>'สำนักความร่วมมือ',
        'number'=>'สำนักความร่วมมือ',
        'icon'=>'person',
        'url'=>site_url('public/user/login/boc'),
        'class'=>'col-lg-6 col-md-12 col-sm-12 col-xs-12',
    ),
    'board'=>array(
        'color'=>'red',
        'text'=>'ผู้บริหาร สอศ.',
        'number'=>'',
        'icon'=>'perm_identity',
        'url'=>site_url('public/user/login/board'),
        'class'=>'col-lg-6 col-md-12 col-sm-12 col-xs-12',
    ),
);

print genWidget($data);
?>
</div>
&copy; <?php print date('Y'); ?> สำนักความร่วมมือ สำนักงานคณะกรรมการการอาชีวศึกษา
</div>
</div>
</div>
</div>
</body>