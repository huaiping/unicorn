<?php
/*
CREATE DATABASE IF NOT EXISTS `exams` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

CREATE TABLE `edongnan` (
  `xh` int(6) NOT NULL,
  `xm` varchar(20) NOT NULL,
  `bj` int(2) NOT NULL,
  `zf` int(4) NOT NULL,
  `bm` int(2) NOT NULL,
  `jm` int(4) NOT NULL,
  `kh` int(15) NOT NULL,
  PRIMARY KEY (`xh`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
