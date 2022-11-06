<?php
        helper('table');
        helper('modal');
        helper('form');
        helper('thai');

        $meettingRows=array();
        //print_r($meettingData);
        foreach($meettingData as $k=>$v){
            $meettingRows[]=array(
                'book_no'=>$v['book_no'],
                'meetting_date'=>dateThai($v['meetting_date']),
                'meetting_place'=>$v['meetting_place'],
                'subject'=>$v['subject'],
                'attach'=>'
                <a href="'.site_url('public/gov/viewMeettingRecord/'.$k).'" target="_blank" class="btn btn-xs btn-danger waves-effect"><i class="material-icons">picture_as_pdf</i>PDF</a>
                <a href="'.site_url('public/gov/viewMeettingPicture/'.$k).'" class="btn btn-xs btn-success waves-effect"><i class="material-icons">image</i>PIC</a>',
                'manage'=>'
                <a href="'.site_url('public/gov/meettingPrint/'.$k).'" target="_blank" class="btn btn-xs btn-primary waves-effect"><i class="material-icons">print</i></a>
                <a href="'.site_url('public/gov/meettingDetail/'.$k).'" class="btn btn-xs btn-warning waves-effect"><i class="material-icons">edit</i></a>
                <a href="'.site_url('public/gov/meettingDelete/'.$k).'" class="btn btn-xs btn-danger" onclick="return confirm(\'ยืนยันการลบข้อมูล\');"><i class="material-icons">delete</i></a>',
            );
        }
        $meettingArr=array('thead'=>array(
            'ครั้งที่',
            'วันที่ประชุม',
            'สถานที่ประชุม',
            'หัวข้อการประชุม',
            'ไฟล์แนบ<br>(เอกสาร/รูปภาพ)',
            'จัดการ<br>(พิมพ์/แก้ไข/ลบ)',
    ),
    'tbody'=>$meettingRows,
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
                                                <a  href="<?php print site_url('public/gov/meettingAdd'); ?>" class="btn btn-warning" id="addBusiness"><i class="material-icons">add</i> เพิ่มรายงานการประชุม</a>
                                        </div>
                                    </div>
                                </div>
                               </form>
                        <div class="body">
                        <div class="table-responsive">
                        <?php
                                print genTable($meettingArr);
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