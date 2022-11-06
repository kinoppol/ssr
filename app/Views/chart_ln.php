<?php

helper('lnChart');
?>
                <!-- Line Chart -->
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2><?php print $caption; ?></h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div id="line_chart_<?php print $id; ?>" class="graph"></div>
                            <?php
                                if(isset($table)){
                                    $tableArr=array(
                                        'caption'=>$caption,
                                        'head'=>$table['head'],
                                        'rows'=>$table['rows'],
                                    );
                                    print ln_table($tableArr,$export=false,$noFoot=true);
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <!-- #END# Line Chart -->
                <?php


if(!isset($_SESSION['LIB']['MORRIS_CART_LIB'])){
    $_SESSION['LIB']['MORRIS_CART_LIB']='Y';
    $_SESSION['FOOTSYSTEM'].='

<script src="'.site_url("template/adminbsb/plugins/raphael/raphael.min.js").'"></script>
<script src="'.site_url("template/adminbsb/plugins/morrisjs/morris.js").'"></script>
    <script src="'.site_url("template/adminbsb/plugins/flot-charts/jquery.flot.js").'"></script>
    <script src="'.site_url("template/adminbsb/plugins/flot-charts/jquery.flot.pie.js").'"></script>
    <script src="'.site_url("template/adminbsb/plugins/flot-charts/jquery.flot.resize.js").'"></script>
    <script src="'.site_url("template/adminbsb/plugins/flot-charts/jquery.flot.categories.js").'"></script>
    <script src="'.site_url("template/adminbsb/plugins/flot-charts/jquery.flot.time.js").'"></script>';

}

$_SESSION['FOOTSYSTEM'].='
            <script>
            $(function(){
                lineChart_'.$id.'(\'line_chart_'.$id.'\');
            });
                function lineChart_'.$id.'(element) {Morris.Line({
                    element: element,
                    data: ['.ln_gen_data($data).'],
                    '.ln_gen_param($data).'
            });
}
            </script>
            ';