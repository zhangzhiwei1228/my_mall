<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-7-19
 * Time: 下午3:12
 */
?>
<!DOCTYPE html>
<html>
<head>
    <?php include_once VIEWS.'inc/head.php'; ?>
    <script src="http://api.map.baidu.com/components?ak=ag0kleDQ9ytCYfEi2OBmlgDhHU1Oau9b&v=1.0"></script>
    <style type="text/css">

        #golist {display: none;}
        @media (max-device-width: 800px){#golist{display: block!important;}}

    </style>
</head>

<body>
<?php
$lng = $this->output->result->location->lng;
$lat = $this->output->result->location->lat;
?>
<lbs-map width="100px"  center="<?php echo $lng; ?>">
    <lbs-poi name="<?php echo $this->address?>" location="<?php echo $lng.','.$lat; ?>" addr="<?php echo $this->address ?>"></lbs-poi>
</lbs-map>
</body>
<script type="text/javascript">
    $(window).resize(function(){
        var winH = window.innerHeight ;
        $("body,html,lbs-map").height(winH)
    })
    $(window).ready(function(){
        var winH = window.innerHeight ;
        $("body,html,lbs-map").height(winH)
    })
</script>
</html>
