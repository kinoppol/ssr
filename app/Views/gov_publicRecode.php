<?php
        helper('table');
        helper('modal');
        helper('form');
        helper('thai');

        $publicRows=array();
        //print_r($meettingData);
        $i=0;
        foreach($publicData as $k=>$v){
            $i++;
            $publicRows[]=array(
                'no'=>$i,
                'public_duration'=>dateThai($v->start_date,true,false,true).' ถึง<br>'.dateThai($v->end_date,true,false,true),
                'public_plcae'=>$v->public_place,
                'public_method'=>$v->public_method,
                'student_target'=>$v->student_target,
                'student_apply'=>$v->student_apply,
                'manage'=>'
                <a href="'.site_url('public/gov/publicDetail/'.$v->id).'" class="btn btn-xs btn-warning waves-effect"><i class="material-icons">edit</i></a>
                <a href="'.site_url('public/gov/publicDelete/'.$v->id).'" class="btn btn-xs btn-danger" onclick="return confirm(\'ยืนยันการลบข้อมูล\');"><i class="material-icons">delete</i></a>',
            );
        }
        $publicArr=array('thead'=>array(
            'ครั้งที่',
            'ช่วงเวลา',
            'สถานที่ประชาสัมพันธ์',
            'วิธีการ',
            'เป้าหมาย',
            'จำนวนผู้เรียน<br>ที่สมัครเข้าเรียน',
            'จัดการ<br>(แก้ไข/ลบ)',
    ),
    'tbody'=>$publicRows,
);
    ?>

<div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                    <div class="header">
                               <form action="<?php print site_url('public/'.$_SERVER['PATH_INFO']); ?>" method="post">
                                    <div class="row clearfix"><div class="row clearfix">
                                      <div class="col-lg-8 col-md-12 col-sm-12 col-xs-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="q" placeholder="คำค้น.." value="<?php print isset($_POST['q'])?$_POST['q']:''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-md-2 col-sm-2 col-xs-3">
                                        <div class="form-group">
                                                <button class="btn btn-primary"><i class="material-icons">search</i> ค้นหา</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">
                                        <div class="form-group">
                                                <!--<button  type="button" class="btn btn-warning" id="addBusiness"><i class="material-icons">add</i> เพิ่มรายงานการประชุม</button>-->
                                                <a  href="<?php print site_url('public/gov/publicAdd'); ?>" class="btn btn-warning" id="addPublic"><i class="material-icons">add</i> เพิ่มรายงานการประชาสัมพันธ์</a>
                                        </div>
                                    </div>
                                </div>
                               </form>
                        <div class="body">
                        <div class="table-responsive">
                        <?php
                                print genTable($publicArr);
                             ?>
                            </div>
                    </div>
                            </div>
                            </div>
                    </div>
                    <script>
                    </script>
                    
                    
                    <?php
                    $data=array(
                        'id'=>'addBusinessModal',
                        'title'=>'เพิ่มข้อมูลสถานประกอบการ',
                        'content'=>'โปรดรอสักครู่...',
                        //'size'=>'modal-lg',
                    );
                    print genModal($data);
                    $_SESSION['FOOTSCRIPT'].='
                    $("#addBusiness").click(function(){
                        $("#addBusinessModal").modal("show");
                    });';
                    ?>