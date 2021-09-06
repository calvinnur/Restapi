<?php 
header("Content-type:application/json");
$url = "https://play.google.com/store/apps/details?id=".$_GET["app_id"];
$get = file_get_contents($url);

$app_name = explode('<h1 class="AHFaub" itemprop="name">', $get);
$app_name = explode('</h1>', $app_name[1]);
$app_name = strip_tags($app_name[0]);

$desc = explode('<div jsname="sngebd">', $get);
$desc = explode('</div>', $desc[1]);
$desc = strip_tags($desc[0]);

$category = explode('<a itemprop="genre" href="/store/apps/category/GAME_SPORTS" class="hrTbp R8zArc">',$get);
$category = explode('</a>', $category[1]);
$category = strip_tags($category[0]);

$ratings = explode('<div class="K9wGie">',$get);
$ratings = explode('</div>',$ratings[1]);
$ratings = strip_tags($ratings[0]);

$total_ratings = explode('<span class="EymY4b">',$get);
$total_ratings = explode('</span>',$total_ratings[1]);
$total_ratings = strip_tags($total_ratings[1]);

$app_icon = explode('<div class="xSyT2c">',$get);
$app_icon = explode('</div>',$app_icon[1]);
$app_icon = explode('<img src=',$app_icon[0]);
$app_icon = explode('srcset=',$app_icon[1]);
$app_icon = explode('srcset=', $app_icon[0]);



$last_update = explode('<div class="IQ1z0d">',$get);
$last_update = explode('</div>', $last_update[1]);
$last_update = explode('</div>', $last_update[0]);
$last_update = strip_tags($last_update[0]);

$version = explode('<div class="IQ1z0d">',$get);
$version = explode('</div>', $version[4]);
$version = strip_tags($version[0]);

$app_size = explode('<div class="IQ1z0d">',$get);
$app_size = explode('</div>', $app_size[2]);
$app_size = strip_tags($app_size[0]);


$screenshot = explode('<button class="MSLVtf Q4vdJd" jsname="WR0adb">',$get);
$screenshot = explode('</button>', $screenshot[1]);





$build["app_name"] = $app_name;
$build["desc"] = $desc;
$build["category"] = $category;
$build["ratings"] = $ratings;
$build["total_ratings"] = $total_ratings;
$build["app_icon"] = $app_icon;
$build["last_update"] = $last_update;
$build["version"] = $version;
$build["app_size"] = $app_size;
$build["screenshot"] = $screenshot;
echo json_encode($build);



?>