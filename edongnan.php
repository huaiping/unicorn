<?php
/*
CREATE DATABASE IF NOT EXISTS `exams` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE `edongnan` (
  `xh` varchar(6) NOT NULL,
  `xm` varchar(16) NOT NULL,
  `bj` varchar(22) NOT NULL,
  `zf` int(6) NOT NULL,
  `bm` varchar(2) NOT NULL,
  `jm` varchar(4) NOT NULL,
  `kh` varchar(12) NOT NULL,
  PRIMARY KEY (`xh`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
*/


$db = new PDO('mysql:host=localhost; dbname=exams', 'root', 'xxxx');
print("<table>");
try {
    for ($m = 0; $m < 60; $m++) {
        for ($i = 6; $i < 30; $i++) {
            $query = $db->query("select xh,xm,kh from edongnan where bj=$i order by zf desc, xh asc limit $m,1");
            $row = $query->fetch();
            print_r("<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td></tr>");
        }
    }
    $db = null;
} catch (PDOException $e) {
    echo $e->getMessage();
}
print("</table>");
?>
