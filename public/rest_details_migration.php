<?php
/**
 * User: Rishabh
 * Date: 10/22/15
 * Time: 12:43 AM
 */

$con= mysqli_connect('localhost','payment','payment','boibanit');
$import="SELECT  DISTINCT `name` FROM `bb_category_description`";
$result1= mysqli_query($con,$import);
$con1= mysqli_connect('localhost','payment','payment','zapdel');
//$import="SELECT * FROM `temp`";


while($row1=mysqli_fetch_row($result1) ){
    $import = "INSERT INTO `cuisine`(`name`) VALUES ('$row1[0]')";
}



//while($row1=mysqli_fetch_row($result1) ){
//    $import = "SELECT `category_id` FROM `bb_resturant_category` WHERE `resturant_category_id` = '$row1[1]'";
//    $result2= mysqli_query($con,$import);
//    echo $import.'<br>';
//
//    $row2=mysqli_fetch_row($result2);
//    $import = "UPDATE `dish_details` SET `cuisine_id` = 'NULL' WHERE `id` = '$row1[1]'";
//    echo $import.'<br><br><br>';
//    $result3= mysqli_query($con1,$import);
//}


//while($row1=mysqli_fetch_row($result1) ){
//    $import = "INSERT INTO `cuisine`(`id`, `name`) VALUES ('$row1[0]','$row1[1]')";
//    echo $import.'<br><br><br>';
//    $result2= mysqli_query($con,$import);
//}


//while($row1=mysqli_fetch_row($result1) ){
//    $import="UPDATE `restaurant_details` SET `lat`='$row1[1]' ,`lng`='$row1[2]' WHERE `id`='$row1[0]'";
//    echo $import.'<br><br><br>';
//    $result2= mysqli_query($con,$import);
//
//}


//while($row1=mysqli_fetch_row($result1) )
//{
//
//    $import="INSERT INTO `restaurant_details`( `address`, `id`, `phone`, `email`,  `city_id`, `rating`, `img`,
//    `contact_person`, `contact_person_phone`, `timing`, `featured`)
//    VALUES ('$row1[5]','$row1[0]','$row1[3]','$row1[2]','$row1[6]','5','$row1[1]','$row1[7]','$row1[8]','$row1[4]','0') ";//combine both queries
//    echo $import.'<br><br><br>';
//    $result3= mysqli_query($con,$import);
//    //var_dump($row1);
//    //var_dump($row2);
//}

//while($row2=mysqli_fetch_row($result2) )
//{
//    $import="UPDATE `restaurant_details` SET `area_id`='$row2[1]' WHERE `id`='$row2[0]'";
//    echo $import.'<br><br><br>';
//    $result3= mysqli_query($con,$import);
//    //var_dump($row2);
//}