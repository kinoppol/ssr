<?php
function genModal($data){
    $size='';
    if($data['size']='modal-lg')$size=' '.$data['size'];
    $ret='<div class="modal fade" id="'.$data['id'].'" tabindex="-1" role="dialog">
    <div class="modal-dialog'.$size.'" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">'.$data['title'].'</h4>
            </div>
            <div class="modal-body">
                '.$data['content'].'
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">ปิด</button>
            </div>
        </div>
        </div>
        </div>';

    return $ret;
}