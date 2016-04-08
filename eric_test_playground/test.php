<!DOCTYPE html>
<html>
<head>
    <title>Eric's Playground</title>
</head>

<body>

<div>
    <?php
        $user = 'ewoktheg_spider';
        $pass = '1qaz2wsx!QAZ@WSX';
        $db = new PDO('mysql:host=localhost;dbname=ewoktheg_spider;charset=utf8mb4', $user, $pass);
        $something = $db->exec("INSERT INTO applicant(applicantID, fb_auth_token, firstName, lastName, primaryEmail, profileLink, password)
                                            values('1', '123saasdqwea', 'bob', 'smith', 'eric@ewokthegreat.com', 'blah', 'blah')");
        $stmt = $db->query('SELECT * from applicant');
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo '<pre>';
        print_r($results);
        echo '</pre>';
    ?>
<!--//    $reportData = array(-->
<!--//        'postID'=>array(-->
<!--//            'postMessage'=>'Facebook post contents',-->
<!--//            'matchedWords'=>array(-->
<!--//                'wordBank1'=>array(1,2,3),-->
<!--//                'wordBank2'=>array(4,5,6),-->
<!--//                'wordBank3'=>array(7,8,9),-->
<!--//                'wordBank4'=>array(11,22,33),-->
<!--//                'wordBank5'=>array(44,55,66)-->
<!--//            )-->
<!--//        ),-->
<!--//        'postID2'=>array(-->
<!--//            'postMessage'=>'Facebook post contents',-->
<!--//            'matchedWords'=>array(-->
<!--//                'wordBank1'=>array(1,2,3),-->
<!--//                'wordBank2'=>array(4,5,6),-->
<!--//                'wordBank3'=>array(7,8,9),-->
<!--//                'wordBank4'=>array(11,22,33),-->
<!--//                'wordBank5'=>array(44,55,66)-->
<!--//            )-->
<!--//        )-->
<!--//    );-->
</div>

</body>

</html>