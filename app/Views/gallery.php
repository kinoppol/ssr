<?php
    helper('modal');
?>
<style>
    .gallery_image{
        object-fit: cover;
    }
</style>
<div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header"><?php
                           print $galleryName;
                        ?>
                        </div>
                        <div class="body">
                                        <div id="aniimated-thumbnials" class="list-unstyled row clearfix">
                            <?php
                            if(count($pictures)<1){
                                print "ขออภัยไม่พบข้อมูลรูปภาพ";
                            }
                                foreach($pictures as $pic){
                                    $picData=explode('/',$pic['url']);
                                    $picName=end($picData);
                                    ?>
                                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                <a href="<?php print $pic['url']; ?>" class="picView" picName="<?php print $picName; ?>" onClick="return false;">
                                                    <img class="gallery_image" width="200" height="200" class="img-responsive thumbnail" src="<?php print $pic['url']; ?>">
                                                </a>
                                            </div>
                                    <?php
                                }
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
</div>
<?php
$_SESSION['FOOTSCRIPT'].='
        $(".picView").click(function(){
            var $this = $(this);
            var imageName=$this.attr("picName");
            //alert("Hello");
            $("#viewPic").modal("show");
            $("#picViewer").html("<img src=\""+$this.attr("href")+"\" width=\"100%\"><br><br><div style=\"text-align:center;\"><a href=\"'.$deleteLink.'"+imageName+"\" class=\"btn btn-danger\" onClick=\"return confirm(\'ลบรูป?\');\"><i class=\"material-icons\">delete</i> ลบ</a></div>");
        });
';

$data=array(
    'id'=>'viewPic',
    'title'=>'ดูรูป',
    'content'=>'<div id="picViewer">โปรดรอสักครู่..</div>',
);
print genModal($data);