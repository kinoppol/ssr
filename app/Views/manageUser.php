<?php
    //print_r($users);
    //exit();
    helper('table');
    helper('user');
    $userRows=array();
    if($user_status=='unregister'){
    $userRegData=array();
            foreach($registerData as $row){
                $userRegData[$row->user_id]=array(
                    'user_type'=>$row->user_type,
                    'register_status'=>$row->register_status,
                );
            }
            
        }

        //print_r($schools);
        //print_r($govs);
        //print_r($institutes);
    foreach($users as $user){
        $user = get_object_vars($user);
        $editLink='<a href="'.site_url('public/admin/editUser/'.$user['user_id']).'" class="btn btn-xs btn-warning waves-effect"><i class="material-icons">edit</i> แก้ไข</a>';
        if($user_status=='registered'){
            $user['mlink']=$editLink;
            $user['user_type']=user_type($user['user_type']);
        }
        else if($user_status=='unregister'){
            

            if(isset($userRegData[$user['user_id']]['register_status'])&&$userRegData[$user['user_id']]['register_status']=='request'){
                $user['mlink']='<a href="'.site_url('public/admin/approveUser/'.$user['user_id']).'" class="btn btn-xs btn-success waves-effect"><i class="material-icons">check</i> อนุมัติ</a>
                                                             <a href="'.site_url('public/admin/disapproveUser/'.$user['user_id']).'" onClick="return confirm(\'ยืนยันการปฏิเสธการลงทะเบียน\')" class="btn btn-xs btn-danger waves-effect"><i class="material-icons">close</i> ปฏิเสธ</a> '.$editLink;
            }else{
                $user['mlink']='<button class="btn btn-default">ผู้ใช้ยังไม่ลงทะเบียน</button> '.$editLink;
            }
                                                             $user['user_type']=isset($userRegData[$user['user_id']]['user_type'])?user_type($userRegData[$user['user_id']]['user_type']):false;                                     
            }
            $org_name=$schools[$user['org_code']];
            if(empty($org_name)){
                $org_name=$govs[$user['org_code']];
            }
            if(empty($org_name)){
                $org_name=$institutes[$user['org_code']];
            }
            if(empty($org_name)){
                $org_n=array(
                    '1300000000'=>'สำนักงานคณะกรรมการการอาชีวศึกษา'
                );
                $org_name=$org_n[$user['org_code']];
            }
            if(empty($org_name)){
                $org_name=$user['org_code'];
            }
        $userRows[]=array(
            $user['user_id'],
            $user['username'],
            $user['name'].' '.$user['surname'],
            $user['email'],
            $user['user_type'],
            $org_name,
            $user['mlink']
        );
    }
    $userArr=array('thead'=>array(
                            'ID',
                            'ชื่อผู้ใช้',
                            'ชื่อ-สกุล',
                            'อีเมล',
                            'ประเภทผู้ใช้',
                            'หน่วยงาน',
                            'จัดการ',
                    ),
                    'tbody'=>$userRows,
    );
?>
<div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                    <div class="header">
                            <h2>
                                <?php print $title; ?>   
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="#" id="addUserBtn">เพิ่มผู้ใช้งาน</a></li>
                                        <li><a href="javascript:void(0);">เพิ่มผู้ใช้งานแบบกลุ่ม</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                        <div class="table-responsive">
                             <?php
                                print genTable($userArr);
                             ?>
                            </div>
                    </div>
                            </div>
                            </div>
                    </div>
                    <div class="modal fade" id="newUserModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="defaultModalLabel">เพิ่มผู้ใช้งานระบบ</h4>
                        </div>
                        <div class="modal-body">
                            โปรดรอสักครู่..
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">ปิด</button>
                        </div>
                    </div>
                </div>
            </div>
            