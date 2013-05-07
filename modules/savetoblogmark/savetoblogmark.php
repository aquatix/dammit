<?php

include '../init.php';

$logfilename = 'logs/incoming.log';

//file_put_contents($logfilename, implode('', $_POST));
//file_put_contents($logfilename, implode('', $_REQUEST));
//var_dump($_REQUEST);

//$input = array('item1' => 'object1', 'item2' => 'object2', 'item-n' => 'object-n');
#$output = implode(', ', array_map(function ($v, $k) { return $k . '=' . $v; }, $_REQUEST, array_keys($_REQUEST)));
#file_put_contents($logfilename, $output);

//$data = json_decode('{user: "aquatix", pass: "", title: "Chinese Restaurant Owner Says Robot Noodle Maker Doing “A Good Job!”", description: "<img src=\"http://singularityhub.com/wp-content/uploads/2013/04/image1.jpg\"><br><br>Noodle peelers should probably start looking for other things to do around the kitchen – there’s just no competing with these robots. Not only are they saving restaurants in China money in wages, they can work rapidly and tirelessly for hours.<br><br>via Pocket http://singularityhub.com/2013/04/19/chinese-restaurant-owner-says-robot-noodle-maker-doing-a-good-job/", categories: [\'IFTTT\', \'Pocket\'], post_status: "publish"}');
/*
$dataJSON = '{user: "aquatix", title: "Chinese Restaurant Owner Says Robot Noodle Maker Doing “A Good Job!”", description: "<img src=\"http://singularityhub.com/wp-content/uploads/2013/04/image1.jpg\"><br><br>Noodle peelers should probably start looking for other things to do around the kitchen – there’s just no competing with these robots. Not only are they saving restaurants in China money in wages, they can work rapidly and tirelessly for hours.<br><br>via Pocket http://singularityhub.com/2013/04/19/chinese-restaurant-owner-says-robot-noodle-maker-doing-a-good-job/"}';
echo $dataJSON;
$data = json_decode('{user: "aquatix", title: "Chinese Restaurant Owner Says Robot Noodle Maker Doing “A Good Job!”", description: "<img src=\"http://singularityhub.com/wp-content/uploads/2013/04/image1.jpg\"><br><br>Noodle peelers should probably start looking for other things to do around the kitchen – there’s just no competing with these robots. Not only are they saving restaurants in China money in wages, they can work rapidly and tirelessly for hours.<br><br>via Pocket http://singularityhub.com/2013/04/19/chinese-restaurant-owner-says-robot-noodle-maker-doing-a-good-job/"}');

var_dump($data);
exit();
*/

$dataJSON = file_get_contents('php://input');
if (empty(trim($dataJSON))) { exit(); }
//$dataJSON = '{"user":"aquatix","pass":"","title":"How to one-up the Samsung Galaxy S4 with a voice controlled call answer feature in Tasker","description":"<img src=\"http:\/\/www.pocketables.com\/images\/2013\/05\/proximity-voice-control-304x352.jpg\"><br><br>\nOk, so you already know how to replicate the Samsung Galaxy S4\u2032s wave-to-answer feature on any phone using Tasker. That\u2019s all good an well, but when you find yourself at that pool party with those obnoxious S4 users, you need something to one-up them, not just match them.<br><br>\nvia Pocket http:\/\/www.pocketables.com\/2013\/05\/how-to-one-up-the-s4-with-a-voice-controlled-call-answer-feature-in-tasker.html","categories":["IFTTT","Pocket"],"post_status":"publish"}';
//$data = json_decode(file_get_contents('php://input')); #php
$data = json_decode($dataJSON); #php
$req_dump = print_r($data, TRUE);
$fp = fopen($logfilename, 'a');
fwrite($fp, $dataJSON . "\n");
fwrite($fp, $req_dump . "\n");


$descpieces = explode('<br><br>', $data->description);

$markTitle = trim($data->title);
$markURI = trim($descpieces[2]);
$markURI = substr($markURI, 11);
$markDescription = '<p>' . trim($descpieces[1]) . '</p>';

fwrite($fp, $markTitle . "\n");
fwrite($fp, $markURI . "\n");
fwrite($fp, $markDescription . "\n");
$desc_dump = print_r($descpieces);
fwrite($fp, $desc_dump );
fclose($fp);

addMark($skel, $markTitle, $markURI, 'Pocket', $markDescription); 



/*
{
    user: "username specified in ifttt",
    password: "password specified in ifttt",
    title: "title generated for the recipe in ifttt",
    categories:['array','of','categories','passed'],
    description:"Body of the blog post as created in ifttt recipe"
}


stdClass Object
(   
    [user] => aquatix
    [pass] =>
    [title] => Chinese Restaurant Owner Says Robot Noodle Maker Doing “A Good Job!”
    [description] => <img src="http://singularityhub.com/wp-content/uploads/2013/04/image1.jpg"><br><br>
Noodle peelers should probably start looking for other things to do around the kitchen – there’s just no competing with these robots. Not only are they saving restaurants in China money in wages, they can work rapidly and tirelessly for hours.<br><br>
via Pocket http://singularityhub.com/2013/04/19/chinese-restaurant-owner-says-robot-noodle-maker-doing-a-good-job/
    [categories] => Array
        (   
            [0] => IFTTT
            [1] => Pocket
        )

    [post_status] => publish
)
*/

//$blogmark = $data->title

//addMark($skel, getRequestParam('title', null), getRequestParam('uri', null), getRequestParam('location', null), getRequestParam('description', null)); 

