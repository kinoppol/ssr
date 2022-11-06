<?php

function ln_gen_data($data){
    $ret='';
        foreach($data['data'] as $row){
            if($ret!='')$ret.=',';
            $r='';
            foreach($row as $k=>$v){
                if($r!='')$r.=',';
                    if($v)$r.=$k.':"'.$v.'"';
                    else $r.=$k.':0';
            }
            $ret.='{'.$r.'}';
        }

    return $ret;

}



function ln_gen_param($data,$lineWidth=3){
    $color='';
    foreach($data['color'] as $c){
        if($color!='')$color.=',';
        $color.="'".$c."'";
    }
    $label='';
    foreach($data['label'] as $l){
        if($label!='')$label.=',';
        $label.="'".$l."'";
    }
    $first_set=array_shift($data['data']);
    $keys=array();
    foreach($first_set as $k=>$v){
        $keys[]=$k;
    }
    $xkey=array_shift($keys);

    $ykey='';
    foreach($keys as $k){
        if($ykey!='')$ykey.=',';
        $ykey.="'".$k."'";
    }

    $ret="xkey: '".$xkey."',
    ykeys: [".$ykey."],
    labels: [".$label."],
    lineColors: [".$color."],
    lineWidth: ".$lineWidth;

    return $ret;

}

function bar_gen_param($data){
    $color='';
    foreach($data['color'] as $c){
        if($color!='')$color.=',';
        $color.="'".$c."'";
    }
    $label='';
    foreach($data['label'] as $l){
        if($label!='')$label.=',';
        $label.="'".$l."'";
    }
    $first_set=array_shift($data['data']);
    $keys=array();
    foreach($first_set as $k=>$v){
        $keys[]=$k;
    }
    $xkey=array_shift($keys);

    $ykey='';
    foreach($keys as $k){
        if($ykey!='')$ykey.=',';
        $ykey.="'".$k."'";
    }

    $ret="xkey: '".$xkey."',
    ykeys: [".$ykey."],
    labels: [".$label."],
    barColors: [".$color."],";

    return $ret;

}


function ln_table($table){
    helper('string');
    $ret='<table width="100%" class="table table-bordered table-striped table-hover"  border="1" cellspacing="0" style="overflow: wrap; border-collapse: collapse; ">';
        $ret.='<thead><tr>';
        foreach($table['head'] as $th){
            $ret.='<th>'.$th.'</th>';
        }
        $ret.='</tr></thead><tbody>';
        foreach($table['rows'] as $tr){
            $data_rows='';
            foreach($tr['data'] as $d){
                $data_rows.='<td align="right">'.number_format($d,0).'</td>';
            }

            $ret.='<tr>';
                $ret.='<td style="color:'.(isset($tr['color'])?$tr['color']:'black').';">'.strlim($tr['label'],15).'</td>'.$data_rows;
            $ret.='</tr>';
        }
    $ret.='</tbody></table>';
    return $ret;
}

function bar_table($table){
    helper('string');
    $ret='<table width="100%" class="table table-bordered table-striped table-hover"  border="1" cellspacing="0" style="overflow: wrap; border-collapse: collapse; ">';
        $ret.='<thead><tr>';
        foreach($table['head'] as $th){
            $ret.='<th>'.$th.'</th>';
        }
        $ret.='</tr></thead><tbody>';
        foreach($table['rows'] as $tr){
            $data_rows='';
            foreach($tr['data'] as $d){
                $data_rows.='<td align="right">'.number_format($d,0).'</td>';
            }

            $ret.='<tr>';
                $ret.='<td style="color:'.(isset($tr['color'])?$tr['color']:'black').';">'.$tr['label'].'</td>'.$data_rows;
            $ret.='</tr>';
        }
    $ret.='</tbody></table>';
    return $ret;
}