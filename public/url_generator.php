<?php
/**
 * Created by PhpStorm.
 * User: Rishabh
 * Date: 10/25/15
 * Time: 12:31 AM
 */

$con= mysqli_connect('localhost','payment','payment','zapdel');
$import="SELECT `id`,`name`,`area_id` FROM `restaurant_details`";

$result= mysqli_query($con,$import);
$con1= mysqli_connect('localhost','payment','payment','boibanit');
while($row1=mysqli_fetch_row($result)){

    $import="SELECT `name` FROM `bb_area` WHERE   `area_id`='$row1[2]'";
    //echo $import;
    $result1= mysqli_query($con1,$import);
    $row2=mysqli_fetch_row($result1);
    $row1[1]=$row1[1].'-'.$row2[0];

    $row1[1]=str_replace("&amp;","And",$row1[1]);
    $row1[1]=str_replace("(","",$row1[1]);
    $row1[1]=str_replace(")","",$row1[1]);
    $row1[1]=str_replace(" ","-",$row1[1]);

    $row1[1]=str_replace("--","-",$row1[1]);
    $row1[1]=str_replace("--","-",$row1[1]);



    if($row1[1][strlen($row1[1])-1]=='-')
    {
        //echo "yes";
        $row1[1][strlen($row1[1])-1]='';
    }
    $import="UPDATE `restaurant_details` SET `url_name`='$row1[1]' WHERE `id`='$row1[0]'";
    echo $import.'<br><br><br>';
    $result3= mysqli_query($con,$import);
    //var_dump($row1);

}