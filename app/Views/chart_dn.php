<?php

helper('dnChart');
helper('table');
?>
      <!-- Browser Usage -->
      <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="card">
                        <div class="header">
                            <h2><?php print $caption ?></h2>
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
                            <div id="<?php print $id; ?>" class="dashboard-donut-chart"></div>
                            <?php
                                print $alt;
                                if(isset($table)){
                                    $tableArr=array(
                                        'caption'=>$caption,
                                        'head'=>$table['head'],
                                        'rows'=>$table['rows'],
                                    );
                                    print dn_table($tableArr,$export=false,$noFoot=true);
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <!-- #END# Browser Usage -->
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
                initDonutChart_'.$id.'();
            });
                function initDonutChart_'.$id.'() {
    Morris.Donut({
        element: \''.$id.'\',
        data: ['.dn_gen_data($dn_data).',],
        colors: ['.dn_gen_color($dn_data).'],
        formatter: function (y) {
            return y + \'%\'
        }
    });
}
            </script>
            ';