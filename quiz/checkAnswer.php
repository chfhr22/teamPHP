<?php
include "../connect/connect.php";
include "../connect/session.php";

$quizId = $_POST['quizId'];
$userAnswer = $_POST['answer'];

$quizSql = "SELECT * FROM quiz WHERE quizId = '$quizId'";
$quizResult = $connect->query($quizSql);
$quizInfo = $quizResult->fetch_array(MYSQLI_ASSOC);

$isCorrect = $userAnswer === $quizInfo['answer'];

if ($isCorrect) {
    // 사용자가 정답을 맞춘 경우
    $memberId = $_SESSION['memberId']; // 세션에서 사용자 ID 가져오기

    // quizMember 테이블에 데이터 추가
    $insertSql = "INSERT INTO quizMember(memberId, quizId, isSolved) VALUES ('$memberId', '$quizId', 1)";
    $connect->query($insertSql);
}

$response = array(
    'correct' => $isCorrect,
    'answer' => $quizInfo['answer'],
    'hint' => $quizInfo['hint']
);

echo json_encode($response);
?>