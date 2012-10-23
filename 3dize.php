<?php
if(is_uploaded_file($_FILES['leftphoto']['tmp_name'])){
    $leftphoto = $_FILES['leftphoto']['tmp_name'];
    $leftphototype = $_FILES['leftphoto']['type'];
    if(is_uploaded_file($_FILES['rightphoto']['tmp_name'])){ 
        $type = "double";
        $rightphoto = $_FILES['rightphoto']['tmp_name'];
        $rightphototype = $_FILES['rightphoto']['type'];
    }
    else{ 
        $type = "single";
        $rightphoto = $_FILES['leftphoto']['tmp_name'];
        $rightphototype = $leftphototype;
    }
    $glasses = $_POST['glasses'];
    $poptype = $_POST['poptype'];
    
    if($leftphototype === "image/jpeg" || $leftphototype === "image/pjpeg"){
        $bim = imagecreatefromjpeg($leftphoto);
        $rim = imagecreatefromjpeg($rightphoto);
    }
    elseif($leftphototype === "image/png" || $leftphototype === "image/x-png"){
        $bim = imagecreatefrompng($leftphoto);
        $rim = imagecreatefrompng($rightphoto);
    }
    elseif($leftphototype === "image/gif"){ 
        $bim = imagecreatefromgif($leftphoto);
        $rim = imagecreatefromgif($rightphoto);
    }

    unlink($leftphoto);
    if($type==="double") unlink($rightphoto);

    if($glasses === "redblue") $gvalue=0;
    elseif($glasses === "redcyan") $gvalue=255;
    imagefilter($bim, IMG_FILTER_COLORIZE, 0, $gvalue, 255);
    imagefilter($rim, IMG_FILTER_COLORIZE, 255, 0, 0);
    if($type==="double") $offset = 0;
    elseif($poptype==="in") $offset = -50;
    else $offset = 50;
    
    imagecopymerge($rim,$bim,$offset,0,0,0,imagesx($bim),imagesy($bim),50);
    //$textcolor = imagecolorallocate($rim,0,0,0);
    //imagestring($rim,5,(imagesx($rim)/2)-110,imagesy($rim)-50,'www.cranklin.com/3dize.php',$textcolor);
    $wm = imagecreatefrompng('3dizeit.png');
    imagecopy($rim,$wm,(imagesx($rim)/2)-150,imagesy($rim)-300,0,0,imagesx($wm),imagesy($wm));

    header('Content-Type: image/jpeg');
    header('Content-Disposition: attachment; filename="3dizedimage.jpg"');
    imagejpeg($rim);

    imagedestroy($rim);
    imagedestroy($bim);
    imagedestroy($wm);
}

else{
    ?>
    <h1>Cranky's 3D-izer!</h1>
    Convert your normal photos into 3D photos!
    <form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
        You can upload TWO photos (taken side-by-side ~3 inches apart)....<br />
        Or you can upload ONE photo<br /><br />
        Left Photo (or single photo): <input type="file" name="leftphoto" /><br />
        Right Photo: <input type="file" name="rightphoto" /><br />
        3D type (for single photos only): <select name="poptype">
            <option value="in">Pop IN</option>
            <option value="out">Pop OUT</option>
        </select>
        <br />
        3D glasses: <select name="glasses">
            <option value="redcyan">Red / Cyan</option>
            <option value="redblue">Red / Blue</option>
        </select>
        <br /><br />
        <input type="submit" value="3D-IZE IT!" />
    </form>
    <br /><br />
    To see how it works, <a href="http://cranklin.wordpress.com/2011/12/20/convert-your-pictures-to-3d/">CLICK HERE</a>.<br />
    See more at <a href="http://www.cranklin.com">cranklin.com</a>.
    <?
}
?>
