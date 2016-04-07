<!DOCTYPE html>
<html>
<head>
    <title>Eric's Playground</title>
</head>

<body>

<div>
    <!--    --><?//=__DIR__?>
    <!--    --><?//="<br/>"?>
    <!--    --><?//=__FILE__?>
    <!--    --><?//="<br/>"?>
    <!--    --><?//=$_SERVER['DOCUMENT_ROOT'] . '/spider'?>
    <!--    --><?//="<br/>"?>

    <?php
    //    define( "__PROJECT_ROOT__", $_SERVER['DOCUMENT_ROOT'] . '/spider');
    //    echo __PROJECT_ROOT__;
    //    echo "<br/>";
    //    echo dirname(__FILE__);
    //    echo "<br/>";
    //    echo rmdir(__PROJECT_ROOT__ . '/raw_json');
    //
    //    $reportData = new stdClass();
    //    $reportData->postData = new stdClass();
    //
    //    print_r(json_encode($reportData));

    $reportData = array(
        'postID'=>array(
            'postMessage'=>'Facebook post contents',
            'matchedWords'=>array(
                'wordBank1'=>array(1,2,3),
                'wordBank2'=>array(4,5,6),
                'wordBank3'=>array(7,8,9),
                'wordBank4'=>array(11,22,33),
                'wordBank5'=>array(44,55,66)
            )
        ),
        'postID2'=>array(
            'postMessage'=>'Facebook post contents',
            'matchedWords'=>array(
                'wordBank1'=>array(1,2,3),
                'wordBank2'=>array(4,5,6),
                'wordBank3'=>array(7,8,9),
                'wordBank4'=>array(11,22,33),
                'wordBank5'=>array(44,55,66)
            )
        )
    );

    $test = $reportData['postID2'];
    $testArray = $test['matchedWords'];
    $subTestArray = $testArray['wordBank6'] = array(00,99,88,77,66);
    echo '<pre>';
    var_dump($test);

    var_dump($testArray);

    var_dump($subTestArray);
    echo '</pre>';


    $bob = $reportData[$postID] = array();
    ?>
</div>

</body>

</html>