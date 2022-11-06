<?php

function dn_gen_data($dn_data){
    $ret='';
    foreach($dn_data as $row){
        if($ret!='')$ret.=',';
        $ret.="{
            label: '".$row['label']."',
            value: '".number_format($row['percent'],2)."'
        }";

    }
    return $ret;
}

function dn_gen_color($dn_data){
    $ret='';
    foreach($dn_data as $row){
        if($ret!='')$ret.=',';
        $ret.="'".$row['color']."'";
    }
    return $ret;
}

function dn_table($table){
    $ret='<table width="100%" class="table table-bordered table-striped table-hover"  border="1" cellspacing="0" style="overflow: wrap; border-collapse: collapse; ">';
        $ret.='<thead><tr>';
        foreach($table['head'] as $th){
            $ret.='<th>'.$th.'</th>';
        }
        $ret.='</tr></thead><tbody>';
        foreach($table['rows'] as $tr){
            $ret.='<tr>';
                $ret.='<td style="color:'.(isset($tr['color'])?$tr['color']:'black').';">'.$tr['label'].'</td><td align="right">'.number_format($tr['percent'],2).'</td>';
            $ret.='</tr>';
        }
    $ret.='</tbody></table>';
    return $ret;
}