<?php
include "../connect/connect.php";
include "../connect/session.php";

$quizId = $_GET['quizId'];
$memberId = $_SESSION['memberId'];

// 퀴즈 정보 가져오기
$quizSql = "SELECT * FROM quiz WHERE quizId = '$quizId'";
$quizResult = $connect->query($quizSql);
$quizInfo = $quizResult->fetch_array(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>뇌섹남녀-boardWrite</title>
    <link rel="stylesheet" href="../assets/css/quiz.css">
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        .answerImg {
            width: 90%;
        }
    </style>

</head>

<body>
    <div id="wrap">
        <?php include "../include/header.php" ?>
        <!-- header -->

        <main id="main">
            <section id="quiz_section">
                <div class="quiz_wrap">

                    <div class="quiz_q_wrap quiz_class">
                        <div class="quiz_timer">
                            <span id="timer"><span id="timeLeft">0:00</span></span>
                        </div>
                        <div class="q_question">

                            <div class="question_wrap">
                                <em>Q<i id="q_em">uiz</i></em>
                                <p>
                                    <?= $quizInfo['question1'] ?>
                                </p>
                            </div>
                            <div class="img_wrap">
                                <?php
                                // question2가 있을 경우 출력
                                if (!empty($quizInfo['question2'])) {
                                    echo '<div class="quiz_url">' . $quizInfo['question2'] . '</div>';
                                }

                                // question3가 있을 경우 이미지 출력
                                if (!empty($quizInfo['question3'])) {
                                    echo '<img src="' . $quizInfo['question3'] . '" alt="질문 이미지">';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <form action="checkAnswer.php" method="post" class="q_answer">
                        <input type="hidden" id="quizId" name="quizId" value="<?= $quizId ?>">
                        <label for="answer">정답 : </label>
                        <input type="text" id="answer" name="answer">
                        <input type="submit" id="submit" value="제출">
                    </form>
                    <button id="likeButton" data-quizid="<?= $quizId ?>">좋아요</button>

                </div>
            </section>
        </main>
        <!-- main -->

        <?php include "../include/footer.php" ?>
        <!-- footer -->
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <div class="modal__inner">
                <span class="close">&times;</span>
                <p id="result"></p>
                <p class="m_img"><img src=<?= $quizInfo['descImg'] ?> alt="질문 이미지" id="answerText"
                        class="answerImg blind"></p>
                <p class="hint blind">
                    <?= $quizInfo['hint'] ?>
                </p>
                <div class="m_wrap2">
                    <button id="showAnswer" class="blind">정답 보기</button>
                    <button id="showHint" class="blind">힌트</button>

                    <button id="showRetry" class="blind">다시 풀기</button>
                    <a href="quizHome.php">목록으로</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#submit').click(function (e) {
                e.preventDefault();

                let quizId = $('#quizId').val();
                let answer = $('#answer').val();

                $.ajax({
                    url: 'checkAnswer.php',
                    type: 'post',
                    data: {
                        quizId: quizId,
                        answer: answer
                    },
                    success: function (response) {
                        let result = JSON.parse(response);

                        if (result.correct) {
                            $('#result').text("정답입니다!");
                        } else {
                            $('#result').text("틀렸습니다.");
                            $('#showAnswer').removeClass('blind');
                            $('#showHint').removeClass('blind');
                            $('#showRetry').removeClass('blind');
                            $('#answerText').text(result.answer);
                        }

                        $('#modal').css('display', 'block');
                    }
                });
            });

            $('#showAnswer').click(function () {
                $('#answerText').removeClass('blind');
            });

            $('#showHint').click(function () {
                $('.hint').removeClass('blind');
            });

            $('#showRetry').click(function () {
                location.reload();
            });
            $('#go__list').click(function () {
                location.href = 'quizHome.php';
            });

            $('.close').click(function () {
                $('#modal').css('display', 'none');
            });

            // 좋아요 버튼 클릭 이벤트 핸들러
            $('#likeButton').click(function () {
                let quizId = $(this).data("quizid");

                $.ajax({
                    url: 'likeQuiz.php',
                    type: 'post',
                    data: {
                        quizId: quizId
                    },
                    success: function (response) {
                        if (response === 'liked') {
                            // 좋아요가 성공적으로 추가된 경우
                            alert('좋아요가 추가되었습니다.');
                        } else if (response === 'already_liked') {
                            // 이미 좋아요가 추가된 경우
                            alert('이미 좋아요를 표시했습니다.');
                        }
                    }
                });
            });

            var timeLimit = <?= $quizInfo['timeLimit'] ?>;

            // 타이머 업데이트 함수
            function updateTimer() {
                var minutes = Math.floor(timeLimit / 60);
                var seconds = timeLimit % 60;

                // 시간을 2자리 숫자로 표시
                var minutesStr = (minutes < 10) ? "0" + minutes : minutes;
                var secondsStr = (seconds < 10) ? "0" + seconds : seconds;

                // 시간 표시 업데이트
                $('#timeLeft').text(minutesStr + ":" + secondsStr);

                // 시간 감소
                timeLimit--;

                // 시간 종료 시 처리
                if (timeLimit < 0) {
                    clearInterval(timerInterval);
                    // 여기에서 시간이 종료되었을 때 실행해야 할 코드를 추가할 수 있습니다.
                    $('#result').text("시간이 종료되었습니다.");
                    $('#showAnswer').removeClass('blind');
                    $('#showHint').removeClass('blind');
                    $('#showRetry').removeClass('blind');
                    $('#answerText').text(result.answer);
                    $('#modal').css('display', 'block');

                }
            }

            // 타이머 업데이트 간격 (1초마다)
            var timerInterval = setInterval(updateTimer, 1000);
        });


    </script>
</body>

</html>