<?php
include_once('link.php');
include_once('function.php');

$PNo=isset($_POST['PNo'])&&trim($_POST['PNo'])?trim($_POST['PNo']):'';
$r_nid=isset($_POST['r_nid'])&&trim($_POST['r_nid'])?trim($_POST['r_nid']):'';
$c_status=isset($_POST['c_status'])&&trim($_POST['c_status'])?trim($_POST['c_status']):'';

$raceSqlSubstring="SELECT race.rid,race.r_nid,race.sid,race.status,race.gid,grouping.gid,grouping.age,grouping.lowest_birth
FROM
(
  SELECT race.rid,race.r_nid,race.sid,race.status,SUBSTRING_INDEX(SUBSTRING_INDEX(race.gid, ',', numbers.n), ',', -1) gid
  FROM
  (
   SELECT 1 n
   UNION ALL SELECT 2
   UNION ALL SELECT 3
   UNION ALL SELECT 4
   UNION ALL SELECT 5
   UNION ALL SELECT 6
   UNION ALL SELECT 7
   UNION ALL SELECT 8
   UNION ALL SELECT 9
   UNION ALL SELECT 10
  ) numbers
  INNER JOIN race
  ON (CHAR_LENGTH(race.gid)-CHAR_LENGTH(REPLACE(race.gid, ',', '')))>=numbers.n-1
  where appear=1 and status=$c_status and r_nid=$r_nid and sid=$PNo
)race
inner JOIN
(
  select *
  from grouping
)grouping
on race.gid=grouping.gid
order by grouping.gid";
$raceSqlSubstringResult=$link->prepare($raceSqlSubstring);
$raceSqlSubstringResult->execute();
$raceSqlSubstringNums=$raceSqlSubstringResult->rowcount();
// $raceSqlSubstringRows=$raceSqlSubstringResult->fetchall();
// pre($raceSqlSubstring);exit;

$raceChk=$raceSqlSubstringNums>0?1:0;

echo $raceChk==1?"<option value=''>請選擇</option>":"<option value=''>請選擇</option>";
while($raceSqlSubstringRows=$raceSqlSubstringResult->fetch())
{
  echo $raceChk==1?"<option value='".$raceSqlSubstringRows['gid']."'>".$raceSqlSubstringRows['age'].$raceSqlSubstringRows['lowest_birth']."後出生</option>":"<option value=''>請選擇</option>";
}
?>
