<?php
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