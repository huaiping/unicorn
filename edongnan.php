<?php
/*
CREATE DATABASE IF NOT EXISTS `exams` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE `exams`.`edongnan` (
  `xh` varchar(6) NOT NULL,
  `xm` varchar(16) NOT NULL,
  `bj` varchar(2) NOT NULL,
  `zf` int(6) NOT NULL,
  `bm` varchar(2) NOT NULL,
  `jm` varchar(4) NOT NULL,
  `kh` varchar(12) NOT NULL,
  PRIMARY KEY (`xh`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
*/


print("<table>");
try {
    $db = new PDO('mysql:host=localhost; dbname=exams', 'root', 'xxxx');
    for ($m = 0; $m < 60; $m++) {
        for ($i = 6; $i < 23; $i++) {
            $query = $db->query("SELECT xh, xm, kh FROM edongnan WHERE bj=$i ORDER BY zf DESC, xh ASC LIMIT $m, 1");
            $row = $query->fetch();
            print_r("<tr><td>".$row['xh']."</td><td>".$row['kh']."</td><td>".$row['xm']."</td></tr>");
        }
    }
    $db = null;
} catch (PDOException $e) {
    echo $e->getMessage();
}
print("</table>");
?>
