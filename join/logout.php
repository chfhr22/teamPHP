<?php
session_start();
session_destroy();
header('Location: ../board/board.php'); // 로그아웃 후 이동할 페이지
?>