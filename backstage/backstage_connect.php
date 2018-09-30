<?php
include_once dirname(dirname(__FILE__)).'/link.php';
include_once dirname(dirname(__FILE__)).'/function.php';
include_once 'get_search_member.php';
header('Content-Type:text/html; charset=utf-8');

//獲取phone,email
$Result = Post_curl($url, $header, $postdata);
$analytic = simplexml_load_string($Result);

if (!empty($analytic)) {
    foreach ($analytic->search_member->list as $k => $v) {
        $m_id = $v->m_id;
        $phone = $v->phone;
        $email = $v->email;

        $memberUpdate = "UPDATE member set member_phone='$phone',member_email='$email' where m_id=$m_id";
        $memberUpdateRe = $link->prepare($memberUpdate);
        $memberUpdateRe->execute();
    }
}

$r_nid = isset($_GET['r_nid']) && trim($_GET['r_nid']) ? trim($_GET['r_nid']) : 0;
$said = isset($_GET['said']) && trim($_GET['said']) ? trim($_GET['said']) : 0;
$c_status = isset($_GET['c_status']) && trim($_GET['c_status']) ? trim($_GET['c_status']) : 0;
$keyword = isset($_GET['keyword']) && trim($_GET['keyword']) ? trim($_GET['keyword']) : 0;
$kind = !empty($_GET['kind']) && trim($_GET['kind']) ? trim($_GET['kind']) : 0;
$t_status = !empty($_GET['t_status']) && trim($_GET['t_status']) ? trim($_GET['t_status']) : 0;
// pre($keyword); exit;

if (!empty($keyword)) {
    if ($c_status == 1) {
        $connectSql = "SELECT
    connect.cid,
    connect.mid as connect_mid,
    member.member_name,
    member.mid,member.member_name,member.member_phone,member.member_email,
    connect.tid as connect_tid,ticket.number,
    connect.r_nid,
    race_name.name,race_name.appmaker_name,
    connect.sid,site.place,site.said,
    connect.gid,grouping.age,
		connect.reveal,
    connect.status,
    connect.team_name,
    participate.count_pid,participate.tid as participate_tid,
    connect.start_time,
    connect.end_time
    FROM
    (
      SELECT *
      FROM connect
      where status=$c_status
    )connect
    inner JOIN member
    on connect.mid=member.mid
    inner join ticket
    on connect.tid=ticket.tid
    inner join race_name
    on connect.r_nid=race_name.r_nid
    inner join
    (
      select *
      from race
      where status>0
    )race
    on connect.rid=race.rid
    inner join
    (
      select *
      from site
    )site
    on connect.sid=site.sid
    inner join grouping
    on connect.gid=grouping.gid
    left join
    (
      select partid,mid,tid,r_nid,pid,status,count(pid) as count_pid
      from participate
      where status=1
      and r_nid in
        (
          SELECT
          connect.r_nid
          FROM
          (
            SELECT *
            FROM connect
            where status=$c_status
          )connect
          inner JOIN member
          on connect.mid=member.mid
          inner join ticket
          on connect.tid=ticket.tid
          inner join race_name
          on connect.r_nid=race_name.r_nid
          inner join
          (
            select *
            from race
            where status>0
          )race
          on connect.rid=race.rid
          inner join
          (
            select *
            from site
          )site
          on connect.sid=site.sid
          inner join grouping
          on connect.gid=grouping.gid
          left join
          (
            select partid,mid,tid,r_nid,pid,status,count(pid) as count_pid
            from participate
            where status=1 and r_nid=$r_nid and pid>0
            group by tid
          )participate
          on connect.tid=participate.tid
          where concat (connect.start_time,ticket.number,race_name.name,member.member_name,member.member_phone,member.member_email,connect.team_name) like '%$keyword%'
          order by cid desc
        )
       and pid>0
      group by tid
    )participate
    on connect.tid=participate.tid
    where concat (connect.start_time,ticket.number,race_name.name,member.member_name,member.member_phone,member.member_email,connect.team_name) like '%$keyword%'
    order by cid desc";
    // pre($connectSql);
        // exit;
    } elseif ($c_status == 2) {
        $connectSql = "SELECT
    connect.cid,
    connect.mid as connect_mid,
    member.member_name,
    member.mid,member.member_name,member.member_phone,member.member_email,
    connect.tid as connect_tid,ticket.number,
    connect.r_nid,
    race_name.name,race_name.appmaker_name,
    connect.sid,site.place,site.said,
    connect.gid,grouping.age,
    connect.reveal,
		connect.status,
    connect.team_name,
    participate_finals.count_pid,participate_finals.tid as participate_finals_tid,
    connect.start_time,
    connect.end_time
    FROM
    (
      SELECT *
      FROM connect
      where status=$c_status
    )connect
    inner JOIN member
    on connect.mid=member.mid
    inner join ticket
    on connect.tid=ticket.tid
    inner join race_name
    on connect.r_nid=race_name.r_nid
    inner join
    (
      select *
      from race
      where status>0
    )race
    on connect.rid=race.rid
    inner join
    (
      select *
      from site
    )site
    on connect.sid=site.sid
    inner join grouping
    on connect.gid=grouping.gid
    left join
    (
      select part_fid,mid,tid,r_nid,pid,status,count(pid) as count_pid
      from participate_finals
      where status=1
      and r_nid in
      (
        SELECT
        connect.r_nid
        FROM
        (
          SELECT *
          FROM connect
          where status=$c_status
        )connect
        inner JOIN member
        on connect.mid=member.mid
        inner join ticket
        on connect.tid=ticket.tid
        inner join race_name
        on connect.r_nid=race_name.r_nid
        inner join
        (
          select *
          from race
          where status>0
        )race
        on connect.rid=race.rid
        inner join
        (
          select *
          from site
        )site
        on connect.sid=site.sid
        inner join grouping
        on connect.gid=grouping.gid
        left join
        (
          select part_fid,mid,tid,r_nid,pid,status,count(pid) as count_pid
          from participate_finals
          where status=1 and r_nid=$r_nid and pid>0
          group by tid
        )participate_finals
        on connect.tid=participate_finals.tid
        where concat (connect.start_time,ticket.number,race_name.name,member.member_name,member.member_phone,member.member_email,connect.team_name) like '%$keyword%'
        order by cid desc
      )
      and pid>0
      group by tid
    )participate_finals
    on connect.tid=participate_finals.tid
    where concat (connect.start_time,ticket.number,race_name.name,member.member_name,member.member_phone,member.member_email,connect.team_name) like '%$keyword%'
    order by cid desc";
    } elseif ($c_status == 0) {
        if (empty($t_status)) {
            if ($kind == 1) {
                $connectSql = "SELECT
        connect.cid,
        connect.mid as connect_mid,
        member.member_name,
        member.mid,member.member_name,member.member_phone,member.member_email,
        connect.tid as connect_tid,ticket.number,
        connect.r_nid,
        race_name.name,race_name.appmaker_name,
        connect.sid,site.place,site.said,
        connect.gid,grouping.age,
        connect.reveal,
				connect.status,
        connect.team_name,
        participate.count_pid,participate.tid as participate_tid,
        connect.start_time,
        connect.end_time
        FROM
        (
          SELECT *
          FROM connect
          where status=$kind
        )connect
        inner JOIN member
        on connect.mid=member.mid
        inner join ticket
        on connect.tid=ticket.tid
        inner join race_name
        on connect.r_nid=race_name.r_nid
        inner join
        (
          select *
          from race
          where status=0
        )race
        on connect.rid=race.rid
        inner join
        (
          select *
          from site
        )site
        on connect.sid=site.sid
        inner join grouping
        on connect.gid=grouping.gid
        left join
        (
          select partid,mid,tid,r_nid,pid,status,count(pid) as count_pid
          from participate
          where status=1
          and r_nid in
          (
            SELECT
            connect.r_nid
            FROM
            (
              SELECT *
              FROM connect
              where status=$kind
            )connect
            inner JOIN member
            on connect.mid=member.mid
            inner join ticket
            on connect.tid=ticket.tid
            inner join race_name
            on connect.r_nid=race_name.r_nid
            inner join
            (
              select *
              from race
              where status=0
            )race
            on connect.rid=race.rid
            inner join
            (
              select *
              from site
            )site
            on connect.sid=site.sid
            inner join grouping
            on connect.gid=grouping.gid
            left join
            (
              select partid,mid,tid,r_nid,pid,status,count(pid) as count_pid
              from participate
              where status=1 and r_nid=$r_nid and pid>0
              group by tid
            )participate
            on connect.tid=participate.tid
            where concat (connect.start_time,ticket.number,race_name.name,member.member_name,member.member_phone,member.member_email,connect.team_name) like '%$keyword%'
            order by cid desc
          )
          and pid>0
          group by tid
        )participate
        on connect.tid=participate.tid
        where concat (connect.start_time,ticket.number,race_name.name,member.member_name,member.member_phone,member.member_email,connect.team_name) like '%$keyword%'
        order by cid desc";
            } elseif ($kind == 2) {
                $connectSql = "SELECT
        connect.cid,
        connect.mid as connect_mid,
        member.member_name,
        member.mid,member.member_name,member.member_phone,member.member_email,
        connect.tid as connect_tid,ticket.number,
        connect.r_nid,
        race_name.name,race_name.appmaker_name,
        connect.sid,site.place,site.said,
        connect.gid,grouping.age,
        connect.reveal,
				connect.status,
        connect.team_name,
        participate_finals.count_pid,participate_finals.tid as participate_finals_tid,
        connect.start_time,
        connect.end_time
        FROM
        (
          SELECT *
          FROM connect
          where status=$kind
        )connect
        inner JOIN member
        on connect.mid=member.mid
        inner join ticket
        on connect.tid=ticket.tid
        inner join race_name
        on connect.r_nid=race_name.r_nid
        inner join
        (
          select *
          from race
          where status=0
        )race
        on connect.rid=race.rid
        inner join
        (
          select *
          from site
        )site
        on connect.sid=site.sid
        inner join grouping
        on connect.gid=grouping.gid
        left join
        (
          select part_fid,mid,tid,r_nid,pid,status,count(pid) as count_pid
          from participate_finals
          where status=1
          and r_nid in
          (
            SELECT
            connect.r_nid
            FROM
            (
              SELECT *
              FROM connect
              where status=$kind
            )connect
            inner JOIN member
            on connect.mid=member.mid
            inner join ticket
            on connect.tid=ticket.tid
            inner join race_name
            on connect.r_nid=race_name.r_nid
            inner join
            (
              select *
              from race
              where status=0
            )race
            on connect.rid=race.rid
            inner join
            (
              select *
              from site
            )site
            on connect.sid=site.sid
            inner join grouping
            on connect.gid=grouping.gid
            left join
            (
              select part_fid,mid,tid,r_nid,pid,status,count(pid) as count_pid
              from participate_finals
              where status=1 and r_nid=$r_nid and pid>0
              group by tid
            )participate_finals
            on connect.tid=participate_finals.tid
            where concat (connect.start_time,ticket.number,race_name.name,member.member_name,member.member_phone,member.member_email,connect.team_name) like '%$keyword%'
            order by cid desc
          )
          and pid>0
          group by tid
        )participate_finals
        on connect.tid=participate_finals.tid
        where concat (connect.start_time,ticket.number,race_name.name,member.member_name,member.member_phone,member.member_email,connect.team_name) like '%$keyword%'
        order by cid desc";
            }
        } elseif (!empty($t_status)) {
            if ($kind == 1) {
                $connectSql = "SELECT
        connect.cid,
        connect.mid as connect_mid,
        member.member_name,
        member.mid,member.member_name,member.member_phone,member.member_email,
        connect.tid as connect_tid,
        ticket.number,
        connect.r_nid,
        race_name.name,race_name.appmaker_name,
        connect.sid,site.place,site.said,
        connect.gid,
        grouping.age,
        connect.reveal,
				connect.status,
        connect.team_name,
        participate.count_pid,participate.tid as participate_tid,
        connect.start_time,
        connect.end_time
        FROM
        (
          SELECT *
          FROM connect
          where kind=$kind and reveal=0
        )connect
        inner JOIN member
        on connect.mid=member.mid
        inner join
        (
          select *
          from ticket
          where status=0
        )ticket
        on connect.tid=ticket.tid
        inner join race_name
        on connect.r_nid=race_name.r_nid
        inner join
        (
          select *
          from race
          -- where status=0
        )race
        on connect.rid=race.rid
        inner join
        (
          select *
          from site
        )site
        on connect.sid=site.sid
        inner join grouping
        on connect.gid=grouping.gid
        left join
        (
          select partid,mid,tid,r_nid,pid,status,count(pid) as count_pid
          from participate
          where status=1
          and r_nid in
          (
            SELECT
            connect.r_nid
            FROM
            (
              SELECT *
              FROM connect
              where kind=$kind and reveal=0
            )connect
            inner JOIN member
            on connect.mid=member.mid
            inner join
            (
              select *
              from ticket
              where status=0
            )ticket
            on connect.tid=ticket.tid
            inner join race_name
            on connect.r_nid=race_name.r_nid
            inner join
            (
              select *
              from race
              -- where status=0
            )race
            on connect.rid=race.rid
            inner join
            (
              select *
              from site
            )site
            on connect.sid=site.sid
            inner join grouping
            on connect.gid=grouping.gid
            left join
            (
              select partid,mid,tid,r_nid,pid,status,count(pid) as count_pid
              from participate
              where status=1 and r_nid=$r_nid and pid>0
              group by tid
            )participate
            on connect.tid=participate.tid
            where concat (connect.start_time,ticket.number,race_name.name,member.member_name,member.member_phone,member.member_email,connect.team_name) like '%$keyword%'
            order by cid desc
          )
          and pid>0
          group by tid
        )participate
        on connect.tid=participate.tid
        where concat (connect.start_time,ticket.number,race_name.name,member.member_name,member.member_phone,member.member_email,connect.team_name) like '%$keyword%'
        order by connect.end_time desc";
            } elseif ($kind == 2) {
                $connectSql = "SELECT
        connect.cid,
        connect.mid as connect_mid,
        member.member_name,
        member.mid,member.member_name,member.member_phone,member.member_email,
        connect.tid as connect_tid,ticket.number,
        connect.r_nid,
        race_name.name,race_name.appmaker_name,
        connect.sid,site.place,site.said,
        connect.gid,grouping.age,
        connect.reveal,
				connect.status,
        connect.team_name,
        participate_finals.count_pid,participate_finals.tid as participate_finals_tid,
        connect.start_time,
        connect.end_time
        FROM
        (
          SELECT *
          FROM connect
          where kind=$kind and reveal=0
        )connect
        inner JOIN member
        on connect.mid=member.mid
        inner join
        (
          select *
          from ticket
          where status=0
        )ticket
        on connect.tid=ticket.tid
        inner join race_name
        on connect.r_nid=race_name.r_nid
        inner join
        (
          select *
          from race
          -- where status=0
        )race
        on connect.rid=race.rid
        inner join
        (
          select *
          from site
        )site
        on connect.sid=site.sid
        inner join grouping
        on connect.gid=grouping.gid
        left join
        (
          select part_fid,mid,tid,r_nid,pid,status,count(pid) as count_pid
          from participate_finals
          where status=1
          and r_nid in
          (
            SELECT
            connect.r_nid
            FROM
            (
              SELECT *
              FROM connect
              where kind=$kind and reveal=0
            )connect
            inner JOIN member
            on connect.mid=member.mid
            inner join
            (
              select *
              from ticket
              where status=0
            )ticket
            on connect.tid=ticket.tid
            inner join race_name
            on connect.r_nid=race_name.r_nid
            inner join
            (
              select *
              from race
              -- where status=0
            )race
            on connect.rid=race.rid
            inner join
            (
              select *
              from site
            )site
            on connect.sid=site.sid
            inner join grouping
            on connect.gid=grouping.gid
            left join
            (
              select part_fid,mid,tid,r_nid,pid,status,count(pid) as count_pid
              from participate_finals
              where status=1 and r_nid=$r_nid and pid>0
              group by tid
            )participate_finals
            on connect.tid=participate_finals.tid
            where concat (connect.start_time,ticket.number,race_name.name,member.member_name,member.member_phone,member.member_email,connect.team_name) like '%$keyword%'
            order by cid desc
          )
          and pid>0
          group by tid
        )participate_finals
        on connect.tid=participate_finals.tid
        where concat (connect.start_time,ticket.number,race_name.name,member.member_name,member.member_phone,member.member_email,connect.team_name) like '%$keyword%'
        order by connect.end_time desc";
            }
        }
    }
} elseif (empty($keyword)) {
    if ($c_status == 1) {
        $connectSql = "SELECT
    connect.cid,
    connect.mid as connect_mid,
    member.member_name,
    member.mid,member.member_name,member.member_phone,member.member_email,
    connect.tid as connect_tid,ticket.number,
    connect.r_nid,
    race_name.name,race_name.appmaker_name,
    connect.sid,
    site.place,site.said,
    connect.gid,grouping.age,
    connect.reveal,
		connect.status,
    connect.team_name,
    participate.count_pid,participate.tid as participate_tid,
    connect.start_time,
    connect.end_time
    FROM
    (
      SELECT *
      FROM connect
      where status=$c_status and r_nid=$r_nid
    )connect
    inner JOIN member
    on connect.mid=member.mid
    inner join ticket
    on connect.tid=ticket.tid
    inner join race_name
    on connect.r_nid=race_name.r_nid
    inner join
    (
      select *
      from site
      where said=$said
    )site
    on connect.sid=site.sid
    inner join grouping
    on connect.gid=grouping.gid
    left join
    (
      select partid,mid,tid,r_nid,pid,status,count(pid) as count_pid
      from participate
      where status=1 and r_nid=$r_nid and pid>0
      group by tid
    )participate
    on connect.tid=participate.tid
    order by cid desc";
    } elseif ($c_status == 2) {
        $connectSql = "SELECT
    connect.cid,
    connect.mid as connect_mid,
    member.member_name,
    member.mid,member.member_name,member.member_phone,member.member_email,
    connect.tid as connect_tid,ticket.number,
    connect.r_nid,
    race_name.name,race_name.appmaker_name,
    connect.sid,
    site.place,site.said,
    connect.gid,grouping.age,
    connect.reveal,
		connect.status,
    connect.team_name,
    participate_finals.count_pid,participate_finals.tid as participate_finals_tid,
    connect.start_time,
    connect.end_time
    FROM
    (
      SELECT *
      FROM connect
      where status=$c_status and r_nid=$r_nid
    )connect
    inner JOIN member
    on connect.mid=member.mid
    inner join ticket
    on connect.tid=ticket.tid
    inner join race_name
    on connect.r_nid=race_name.r_nid
    inner join
    (
      select *
      from site
      where said=$said
    )site
    on connect.sid=site.sid
    inner join grouping
    on connect.gid=grouping.gid
    left join
    (
      select part_fid,mid,tid,r_nid,pid,status,count(pid) as count_pid
      from participate_finals
      where status=1 and r_nid=$r_nid and pid>0
      group by tid
    )participate_finals
    on connect.tid=participate_finals.tid
    order by cid desc";
    } elseif ($c_status == 0) {
        if (empty($t_status)) {
            if ($kind == 1) {
                $connectSql = "SELECT
        connect.cid,
        connect.mid as connect_mid,
        member.member_name,
        member.mid,member.member_name,member.member_phone,member.member_email,
        connect.tid as connect_tid,ticket.number,
        connect.r_nid,
        race_name.name,race_name.appmaker_name,
        connect.sid,
        site.place,site.said,
        connect.gid,grouping.age,
        connect.reveal,
				connect.status,
        connect.team_name,
        participate.count_pid,participate.tid as participate_tid,
        connect.start_time,
        connect.end_time
        FROM
        (
          SELECT *
          FROM connect
          where status=$kind and r_nid=$r_nid
        )connect
        inner JOIN member
        on connect.mid=member.mid
        inner join ticket
        on connect.tid=ticket.tid
        inner join race_name
        on connect.r_nid=race_name.r_nid
        inner join
        (
          select *
          from site
          where said=$said
        )site
        on connect.sid=site.sid
        inner join grouping
        on connect.gid=grouping.gid
        left join
        (
          select partid,mid,tid,r_nid,pid,status,count(pid) as count_pid
          from participate
          where status=1 and r_nid=$r_nid and pid>0
          group by tid
        )participate
        on connect.tid=participate.tid
        order by cid desc";
            } elseif ($kind == 2) {
                $connectSql = "SELECT
        connect.cid,
        connect.mid as connect_mid,
        member.member_name,
        member.mid,member.member_name,member.member_phone,member.member_email,
        connect.tid as connect_tid,ticket.number,
        connect.r_nid,
        race_name.name,race_name.appmaker_name,
        connect.sid,
        site.place,site.said,
        connect.gid,grouping.age,
        connect.reveal,
				connect.status,
        connect.team_name,
        participate_finals.count_pid,participate_finals.tid as participate_finals_tid,
        connect.start_time,
        connect.end_time
        FROM
        (
          SELECT *
          FROM connect
          where status=$kind and r_nid=$r_nid
        )connect
        inner JOIN member
        on connect.mid=member.mid
        inner join ticket
        on connect.tid=ticket.tid
        inner join race_name
        on connect.r_nid=race_name.r_nid
        inner join
        (
          select *
          from site
          where said=$said
        )site
        on connect.sid=site.sid
        inner join grouping
        on connect.gid=grouping.gid
        left join
        (
          select part_fid,mid,tid,r_nid,pid,status,count(pid) as count_pid
          from participate_finals
          where status=1 and r_nid=$r_nid and pid>0
          group by tid
        )participate_finals
        on connect.tid=participate_finals.tid
        order by cid desc";
            }
        } elseif (!empty($t_status)) {
            if ($kind == 1) {
                $connectSql = "SELECT
        connect.cid,
        connect.mid as connect_mid,
        member.member_name,
        member.mid,member.member_name,member.member_phone,member.member_email,
        connect.tid as connect_tid,
        connect.r_nid,
        ticket.number,
        race_name.name,race_name.appmaker_name,
        connect.sid,
        site.place,site.said,
        connect.gid,
        connect.reveal,
				connect.status,
        grouping.age,
        connect.team_name,
        participate.count_pid,participate.tid as participate_tid,
        connect.start_time,
        connect.end_time
        FROM
        (
          SELECT *
          FROM connect
          where kind=$kind and reveal=0
        )connect
        inner JOIN member
        on connect.mid=member.mid
        inner join
        (
          select *
          from ticket
          where status=0
        )ticket
        on connect.tid=ticket.tid
        inner join race_name
        on connect.r_nid=race_name.r_nid
        inner join
        (
          select *
          from site
        )site
        on connect.sid=site.sid
        inner join grouping
        on connect.gid=grouping.gid
        left join
        (
          select partid,mid,tid,r_nid,pid,status,count(pid) as count_pid
          from participate
          where status=1 and pid>0
          group by tid
        )participate
        on connect.tid=participate.tid
        order by connect.end_time desc";
            } elseif ($kind == 2) {
                $connectSql = "SELECT
        connect.cid,
        connect.mid as connect_mid,
        member.member_name,
        member.mid,member.member_name,member.member_phone,member.member_email,
        connect.tid as connect_tid,ticket.number,
        connect.r_nid,
        race_name.name,race_name.appmaker_name,
        connect.sid,
        site.place,site.said,
        connect.gid,grouping.age,
        connect.reveal,
				connect.status,
        connect.team_name,
        participate_finals.count_pid,participate_finals.tid as participate_finals_tid,
        connect.start_time,
        connect.end_time
        FROM
        (
          SELECT *
          FROM connect
          where kind=$kind and reveal=0
        )connect
        inner JOIN member
        on connect.mid=member.mid
        inner join ticket
        on connect.tid=ticket.tid
        inner join race_name
        on connect.r_nid=race_name.r_nid
        inner join
        (
          select *
          from site
        )site
        on connect.sid=site.sid
        inner join grouping
        on connect.gid=grouping.gid
        left join
        (
          select part_fid,mid,tid,r_nid,pid,status,count(pid) as count_pid
          from participate_finals
          where status=1 and pid>0
          group by tid
        )participate_finals
        on connect.tid=participate_finals.tid
        order by connect.end_time desc";
            }
        }
    }
}
// pre($connectSql);exit;
$connectSqlResult = $link->prepare($connectSql);
// pre($connectSql); exit;
$connectSqlResult->execute();
$connectSqlRows = $connectSqlResult->fetchall(PDO::FETCH_ASSOC);
// pre($connectSqlRows);exit;

$connectSqlNums = $connectSqlResult->rowcount();
$connectPer = 10; //每頁呈現幾筆
$connectPages = ceil($connectSqlNums / $connectPer); //(總筆數/每頁呈現幾筆),會出現幾頁
$connectPage = !isset($_GET['connectpage']) ? 1 : (int) $_GET['connectpage']; //取get值
$connectStart = ($connectPage - 1) * $connectPer; //每頁從陣列['0']開始顯示
$connectRange = 10; //每頁顯示的頁碼數
$start = (int) (($connectPage - 1) / $connectRange) * $connectRange + 1;  //$start是設定顯示每頁頁碼的開始值
$end = $start + $connectRange - 1;  //$end是設定顯示每頁頁碼的結束值
$connectSql .= " LIMIT $connectStart,$connectPer"; //陣列['0']開始顯示,呈現幾筆
$connectSqlResult = $link->prepare($connectSql);
$connectSqlResult->execute();
$connectSqlRows = $connectSqlResult->fetchall();
?>
<!DOCTYPE html>
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <title>盃賽報名管理/</title>
    <meta name="description" content="AppUI is a Web App Bootstrap Admin Template created by pixelcave and published on Themeforest">
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">
    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="img/favicon.png">
    <link rel="apple-touch-icon" href="img/icon57.png" sizes="57x57">
    <link rel="apple-touch-icon" href="img/icon72.png" sizes="72x72">
    <link rel="apple-touch-icon" href="img/icon76.png" sizes="76x76">
    <link rel="apple-touch-icon" href="img/icon114.png" sizes="114x114">
    <link rel="apple-touch-icon" href="img/icon120.png" sizes="120x120">
    <link rel="apple-touch-icon" href="img/icon144.png" sizes="144x144">
    <link rel="apple-touch-icon" href="img/icon152.png" sizes="152x152">
    <link rel="apple-touch-icon" href="img/icon180.png" sizes="180x180">
    <!-- END Icons -->
    <!-- Stylesheets -->
    <!-- Bootstrap is included in its original form, unaltered -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Related styles of various icon packs and plugins -->
    <link rel="stylesheet" href="css/plugins.css">
    <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
    <link rel="stylesheet" href="css/main.css">
    <!-- Include a specific file here from css/themes/ folder to alter the default theme of the template -->
    <!-- The themes stylesheet of this template (for using specific theme color in individual elements - must included last) -->
    <link rel="stylesheet" href="css/themes.css">
    <link href="SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css">
    <!-- END Stylesheets -->
    <!-- Modernizr (browser feature detection library) -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    <script src="SpryAssets/SpryAccordion.js" type="text/javascript"></script>
</head>

<body>
    <!-- Page Wrapper -->
    <!-- In the PHP version you can set the following options from inc/config file -->
    <!--
            Available classes:

            'page-loading'      enables page preloader
        -->
    <div id="page-wrapper" class="page-loading">
        <!-- Preloader -->
        <!-- Preloader functionality (initialized in js/app.js) - pageLoading() -->
        <!-- Used only if page preloader enabled from inc/config (PHP version) or the class 'page-loading' is added in #page-wrapper element (HTML version) -->
        <div class="preloader">
            <div class="inner">
                <!-- Animation spinner for all modern browsers -->
                <div class="preloader-spinner themed-background hidden-lt-ie10"></div>
                <!-- Text for IE9 -->
                <h3 class="text-primary visible-lt-ie10"><strong>Loading..</strong></h3>
            </div>
        </div>
        <!-- END Preloader -->
        <!-- Page Container -->
        <!-- In the PHP version you can set the following options from inc/config file -->
        <!--
                Available #page-container classes:

                'sidebar-light'                                 for a light main sidebar (You can add it along with any other class)

                'sidebar-visible-lg-mini'                       main sidebar condensed - Mini Navigation (> 991px)
                'sidebar-visible-lg-full'                       main sidebar full - Full Navigation (> 991px)

                'sidebar-alt-visible-lg'                        alternative sidebar visible by default (> 991px) (You can add it along with any other class)

                'header-fixed-top'                              has to be added only if the class 'navbar-fixed-top' was added on header.navbar
                'header-fixed-bottom'                           has to be added only if the class 'navbar-fixed-bottom' was added on header.navbar

                'fixed-width'                                   for a fixed width layout (can only be used with a static header/main sidebar layout)

                'enable-cookies'                                enables cookies for remembering active color theme when changed from the sidebar links (You can add it along with any other class)
            -->
        <div id="page-container" class="header-fixed-top sidebar-visible-lg-full">
            <!-- Main Sidebar -->
            <div id="sidebar">
                <!-- Sidebar Brand -->
                <div id="sidebar-brand" class="themed-background">
                    <a href="index.html" class="sidebar-title">
                            <i class="fa fa-cube"></i> <span class="sidebar-nav-mini-hide">喬立達數位</span>
                        </a>
                </div>
                <!-- END Sidebar Brand -->
                <!-- Wrapper for scrolling functionality -->
                <div id="sidebar-scroll">
                    <!-- Sidebar Content -->
                    <div class="sidebar-content">
                        <!-- Sidebar Navigation -->
                        <ul class="sidebar-nav">
                            <li>
                                <a href="backstage_index.php?<?php echo 'c_status='.$c_status; ?>" class=" "> <i class="fa fa-caret-right sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">盃賽報名管理</span></a>
                            </li>
                            <li>
                                <a href="backstage_participate.php?c_status=&keyword=" class=" "> <i class="fa fa-caret-right sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">球員管理</span></a>
                            </li>
                            <li>
                                <a href="backstage_index.php?c_status=&keyword=&kind=1" class=" "> <i class="fa fa-caret-right sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">封存盃賽查詢</span></a>
                            </li>
                            <li>
                                <a href="backstage_connect.php?c_status=&keyword=&kind=1&t_status=1" class=" "> <i class="fa fa-caret-right sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">封存隊伍查詢</span></a>
                            </li>
                        </ul>
                        <!-- END Sidebar Navigation -->
                    </div>
                    <!-- END Sidebar Content -->
                </div>
                <!-- END Wrapper for scrolling functionality -->
            </div>
            <!-- END Main Sidebar -->
            <!-- Main Container -->
            <div id="main-container">
                <!-- Header -->
                <!-- In the PHP version you can set the following options from inc/config file -->
                <!--
                        Available header.navbar classes:

                        'navbar-default'            for the default light header
                        'navbar-inverse'            for an alternative dark header

                        'navbar-fixed-top'          for a top fixed header (fixed main sidebar with scroll will be auto initialized, functionality can be found in js/app.js - handleSidebar())
                            'header-fixed-top'      has to be added on #page-container only if the class 'navbar-fixed-top' was added

                        'navbar-fixed-bottom'       for a bottom fixed header (fixed main sidebar with scroll will be auto initialized, functionality can be found in js/app.js - handleSidebar()))
                            'header-fixed-bottom'   has to be added on #page-container only if the class 'navbar-fixed-bottom' was added
                    -->
                <header class="navbar navbar-inverse navbar-fixed-top">
                    <!-- Left Header Navigation -->
                    <ul class="nav navbar-nav-custom">
                        <!-- Main Sidebar Toggle Button -->
                        <li>
                            <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar');">
                                    <i class="fa fa-ellipsis-v fa-fw animation-fadeInRight" id="sidebar-toggle-mini"></i>
                                    <i class="fa fa-bars fa-fw animation-fadeInRight" id="sidebar-toggle-full"></i>
                                </a>
                        </li>
                        <!-- END Main Sidebar Toggle Button -->
                        <!-- Header Link -->
                        <!-- END Header Link -->
                    </ul>
                    <!-- END Left Header Navigation -->
                    <!-- Right Header Navigation -->
                    <ul class="nav navbar-nav-custom pull-right">
                        <!-- User Dropdown -->
                        <li class="dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="img/logo.png" alt="avatar">
                                </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a href="logout.php">
                                            <i class="fa fa-power-off fa-fw pull-right"></i>
                                            Log out
                                        </a>
                                </li>
                            </ul>
                        </li>
                        <!-- END User Dropdown -->
                    </ul>
                    <!-- END Right Header Navigation -->
                </header>
                <!-- END Header -->
                <!-- Page content -->
                <div id="page-content">
                    <!-- Widgets Header -->
                    <div class="content-header">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="header-section">
                                <?php
                                  echo empty($t_status) ? empty($kind) ? !empty($keword) ? '<h1>盃賽報名管理/'.$connectSqlRows[0]['appmaker_name'] : ''.'</h1>' : '<h1>封存盃賽查詢/</h1>' : '<h1>封存隊伍查詢/</h1>';
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Widgets Header -->
                    <!-- Partial Responsive Block -->
                    <div class="block">
                        <!-- Partial Responsive Title -->
                        <div class="block-title">
                        <?php
                          echo empty($t_status) ? empty($kind) ? !empty($keword) ? '<h2>盃賽報名管理/'.$connectSqlRows[0]['appmaker_name'] : ''.'</h2>' : '<h2>封存盃賽查詢/</h2>' : '<h2>封存隊伍查詢/</h2>';
                        ?>
                        </div>
                        <!-- END Partial Responsive Title -->
                        <!-- Partial Responsive Content -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="col-sm-6">
                                      <?php $keywordKeep = !empty($keyword) && trim($keyword) ? trim($keyword) : ''; ?>
                                        <input type="text" id="keyword" name="keyword" class="form-control" value="<?php echo $keywordKeep; ?>" placeholder="票券編號/負責人名稱/電話/E-mail/球隊名稱">
                                    </div>
                                    <button type="button" id="search" name="search" class="btn btn-effect-ripple btn-info" style="overflow: hidden; position: relative;">查詢</button>
                                    <a>
                                      <?php
                                        //#預決賽分頁好判斷用
                                        if (empty($t_status)) {
                                            switch ($c_status) {
                                            case 1:
                                              echo '<div data-toggle="tooltip" class="btn btn-xs btn-success">預賽</div>';
                                              break;
                                            case 2:
                                              echo '<div data-toggle="tooltip" class="btn btn-effect-ripple btn-xs btn-danger">決賽</div>';
                                              break;
                                            default:
                                              echo '';
                                          }
                                        } elseif (!empty($t_status)) {
                                            switch ($kind) {
                                            case 1:
                                              echo '<div data-toggle="tooltip" class="btn btn-xs btn-success">預賽</div>';
                                              echo '<a href="backstage_connect.php?c_status=&keyword=&kind=2&t_status=1">決賽</a>';
                                              break;
                                            case 2:
                                              echo '<a href="backstage_connect.php?c_status=&keyword=&kind=1&t_status=1">預賽</a>';
                                              echo '<div data-toggle="tooltip" class="btn btn-effect-ripple btn-xs btn-danger">決賽</div>';
                                              break;
                                            default:
                                              echo '';
                                          }
                                        }
                                      ?>
                                    </a>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                  <?php
                                    echo !empty($t_status) ? '<p style="color:#ff5809;font-size:18px;">此區為<封存隊伍查詢></p>' : '';
                                  ?>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class=" col-lg-12 clearfix" style="    margin-bottom: 20px;">
                            <table id="example-datatable" class="table table-striped table-bordered table-vcenter dataTable no-footer" role="grid" aria-describedby="example-datatable_info">
                                <thead>
                                    <tr role="row">
                                        <th class="text-center ">報名日期</th>
                                        <th class="text-center ">最後更新時間</th>
                                        <th class="text-center ">訂單編號</th>
                                        <th class="text-center ">盃賽票券名稱</th>
                                        <th class="text-center ">區域</th>
                                        <th class="text-center ">場區</th>
                    										<th class="text-center ">年齡組別</th>
                                        <th class="text-center ">報名負責人</th>
                                        <th class="text-center ">行動電話</th>
                                        <th class="text-center ">MAIL</th>
                                        <th class="text-center ">球隊名稱</th>
                                        <th class="text-center ">球員人數</th>
                                        <th class="text-center ">功能</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- <tr role="row" class="odd">
                                        <td class="text-center">2018/01/15</td>
                                        <td class="text-center">AE015155664</td>
                                        <td class="text-center">MiniSoccer迷你足球領導春季盃</td>
                                        <td class="text-center">北部</td>
                                        <td class="text-center">楊教練</td>
                                        <td class="text-center">0988-888888</td>
                                        <td class="text-center">ABCD@gamil.com</td>
                                        <td class="text-center">迷你足球大Q隊</td>
                                        <td class="text-center">10</td>
                                        <td class="text-center">
                                            <a href="player_manage.html" data-toggle="tooltip" class="btn btn-xs btn-success" style="margin-left: 5px;">管理球員</a>
                                            <a href="team_connection.html" data-toggle="tooltip" class="btn btn-xs btn-primary" style="margin-left: 5px;">球隊聯絡資訊</a>
                                            <a href="" data-toggle="tooltip" class="btn btn-xs btn-info" style="margin-left: 5px;">晉級決賽</a>
                                            <a href="" data-toggle="tooltip" class="btn btn-effect-ripple btn-xs btn-danger" style="margin-left: 5px;">封存</a></td>
                                    </tr> -->

                                    <?php
                                    foreach ($connectSqlRows as $c1) {
                                        ?>
                                    <tr role="row" class="odd">
                                      <td class="text-center"><?php echo $c1['start_time']; ?></td>
                                      <td class="text-center"><?php echo $c1['end_time']; ?></td>
                                      <td class="text-center"><?php echo $c1['number']; ?></td>
                                      <td class="text-center"><?php echo $c1['name']; ?></td>
                                      <td class="text-center"><?php
                                        switch ($c1['said']) {
                                          case 1:
                                            echo '北部';
                                            break;
                                          case 2:
                                            echo '中部';
                                            break;
                                          case 3:
                                            echo '南部';
                                            break;
                                          case 4:
                                            echo '東部';
                                            break;
                                          case 5:
                                            echo '西部';
                                            break;
                                          default:
                                            echo 'error2';
                                        } ?>
                                      </td>
                                      <td class="text-center"><?php echo $c1['place']; ?></td>
                  									  <td class="text-center"><?php echo $c1['age']; ?></td>
                                      <td class="text-center"><?php echo $c1['member_name']; ?></td>
                                      <td class="text-center"><?php echo $c1['member_phone']; ?></td>
                                      <td class="text-center"><?php echo $c1['member_email']; ?></td>
                                      <td class="text-center"><?php echo $c1['team_name']; ?></td>
                                      <td class="text-center"><?php echo $c1['count_pid']; ?></td>
                                      <td class="text-center">
                                        <a><input type="button" id="upd_participate<?php echo $c1['connect_tid']; ?>" name="upd_participate" value="管理球員" onclick="//updParticipate(<?php //echo $c1['connect_tid']?>);" data-toggle="tooltip" class="btn btn-xs btn-success" style="margin-left: 5px;"></a>
                                        <a><input type="button" id="upd_connet<?php echo $c1['connect_tid']; ?>" name="upd_connet" value="球隊聯絡資訊" onclick="//updConnect(<?php //echo $c1['connect_tid']?>);" data-toggle="tooltip" class="btn btn-xs btn-primary" style="margin-left: 5px;"></a>
                                        <?php
                                        if (empty($kind)) {
                                            ?>
                                          <a>
                                            <?php
                                            switch ($c_status) {
                                              case 1:
                                                                                                if ($c1['reveal'] == 1) {
                                                                                                    echo '<input type="button" id="upd_finals'.$c1['connect_tid'].'" name="upd_finals" value="晉級決賽" data-toggle="tooltip" class="btn btn-xs btn-info" style="margin-left: 5px;">';
                                                                                                } elseif ($c1['reveal'] == 0) {
                                                                                                    echo '<input type="button" name="upd_finals" value="已晉級決賽" data-toggle="tooltip" class="btn btn-xs btn-info" style="margin-left: 5px;">';
                                                                                                }
                                                break;
                                              case 2:
                                                echo '<input type="button" id="upd_finals'.$c1['connect_tid'].'" name="upd_finals" value="退回預賽" data-toggle="tooltip" class="btn btn-xs btn-info" style="margin-left: 5px;">';
                                                break;
                                              default:
                                                echo '<input type="button" id="upd_finals'.$c1['connect_tid'].'" name="upd_finals" value="error3" data-toggle="tooltip" class="btn btn-xs btn-info" style="margin-left: 5px;">';
                                            } ?>
                                          </a>
                                          <a><input type="button" id="upd_seal<?php echo $c1['connect_tid']; ?>" name="upd_seal" value="封存" onclick="//updSeal(<?php //echo $c1['connect_tid']?>);"data-toggle="tooltip" class="btn btn-effect-ripple btn-xs btn-danger" style="margin-left: 5px;"></a>
                                        <?php
                                        } elseif (!empty($kind)) {
                                            echo '';
                                        } ?>
                                    </tr>
                                    <?php
                                    }?>

                                </tbody>
                            </table>
                            <div class="text-center">
                                <!-- <ul class="pagination">
                                    <li><a href="javascript:void(0)"><i class="fa fa-chevron-left"></i></a></li>
                                    <li><a href="javascript:void(0)">1</a></li>
                                    <li class="active"><a href="javascript:void(0)">2</a></li>
                                    <li><a href="javascript:void(0)">3</a></li>
                                    <li><a href="javascript:void(0)"><i class="fa fa-chevron-right"></i></a></li>
                                </ul> -->

                                <ul class="pagination">
                                <?php
                                 echo $connectPage == 1 ? '' : '<li><a href=?r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&kind='.$kind.'&t_status='.$t_status.'&keyword='.$keyword.'&connectpage=1>首頁</a></li>'.'　';
                                 echo $connectPage == 1 ? '' : '<li><a href=?r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&kind='.$kind.'&t_status='.$t_status.'&keyword='.$keyword.'&connectpage='.($connectPage - 1).'><i class="fa fa-chevron-left"></i></a></li>'.'　'; //上一頁
                                 if ($connectPages <= $connectRange) { //開始輸出頁碼
                                   for ($i = $start; $i <= $connectPages; ++$i) { //在目前頁數裡本身頁數的頁碼就不要連結，如果不是就加上連結
                                     echo $i == $connectPage ? '<li class="active"><a>'.$i.'</a></li>'.'　' : '<li><a href=?r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&kind='.$kind.'&t_status='.$t_status.'&keyword='.$keyword.'&connectpage='.$i.'>'.$i.'</a></li>'.'　'; //當前顯示頁不會有連結,且放大
                                   }
                                 } else { //如果總頁數大於每頁要顯示的頁碼數
                                     //如果目前的頁數大於5，預設定為第6頁開始，每頁的頁碼就往前移動1位  ex 目前的頁數為第6頁，所以輸出 2 3 4 5 6 7 8 9 10 11，如果是第7頁就輸出 3 4 5 6 7 8 9 10 11 12，依此類推
                                     if ($connectPage > 5) {
                                         $end = $connectPage + 5;  //每頁結尾的頁碼就+5
                                     if ($end > $connectPages) {  //如果每頁結尾的頁碼大於總頁數
                                       $end = $connectPages;  //就將每頁結尾的頁碼改寫為最後一頁
                                     }
                                         $start = $end - 9;  //將每頁開頭的頁碼設為結尾的頁碼-9
                                     //開始輸出頁碼
                                     for ($i = $start; $i <= $end; ++$i) { //在目前頁數裡本身頁數的頁碼就不要連結，如果不是就加上連結
                                       echo $i == $connectPage ? '<li class="active"><a>'.$i.'</a></li>'.'　' : '<li><a href=?r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&kind='.$kind.'&t_status='.$t_status.'&keyword='.$keyword.'&connectpage='.$i.'>'.$i.'</a></li>'.'　'; //當前顯示頁不會有連結,且放大
                                     }
                                     } else { //如果目前的頁數小於5
                                     if ($end > $connectPages) { //如果每頁結尾的頁碼大於總頁數
                                       $end = $connectPages;  //就將每頁結尾的頁碼改寫為最後一頁
                                     }
                                         //開始輸出頁碼
                                     for ($i = $start; $i <= $end; ++$i) { //在目前頁數裡本身頁數的頁碼就不要連結，如果不是就加上連結
                                       echo $i == $connectPage ? '<li class="active"><a>'.$i.'</a></li>'.'　' : '<li><a href=?r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&kind='.$kind.'&t_status='.$t_status.'&keyword='.$keyword.'&connectpage='.$i.'>'.$i.'</a></li>'.'　'; //當前顯示頁不會有連結,且放大
                                     }
                                     }
                                 }
                                 echo $connectPage == $connectPages ? '' : '　'.'<li><a href=?r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&kind='.$kind.'&t_status='.$t_status.'&keyword='.$keyword.'&connectpage='.($connectPage + 1).'><i class="fa fa-chevron-right"></i></a></li>'; //下一頁
                                 echo $connectPage == $connectPages ? '' : '　'.'<li><a href=?r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&kind='.$kind.'&t_status='.$t_status.'&keyword='.$keyword.'&connectpage='.$connectPages.'>末頁</a></li>';
                                 echo '<li><a>共'.$connectPages.'頁</a></li>';  //顯示目前總頁數
                                 echo '<li><a>共'.$connectSqlNums.'筆</a></li>'; //顯示總筆數
                                ?>
                                </ul>

                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <!-- END Partial Responsive Content -->
                    </div>
                    <!-- END Partial Responsive Block -->
                </div>
                <!-- END Page Content -->
            </div>
            <!-- END Main Container -->
        </div>
        <!-- END Page Container -->
    </div>
    <!-- END Page Wrapper -->
    <!-- Include Jquery library from Google's CDN but if something goes wrong get Jquery from local file (Remove 'http:' if you have SSL) -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
    !window.jQuery && document.write(decodeURI('%3Cscript src="js/vendor/jquery-2.1.1.min.js"%3E%3C/script%3E'));
    </script>
    <!-- Bootstrap.js, Jquery plugins and Custom JS code -->
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/app.js"></script>
    <!-- Load and execute javascript code used only in this page -->
    <!--以下這段影響js運作-->
    <!-- <script src="js/pages/readyDashboard.js"></script>
    <script>
    $(function() { ReadyDashboard.init(); });
    </script> -->
    <!--以上這段影響js運作-->
    <script>
    $(function()
    {
      <?php
        foreach ($connectSqlRows as $c2) {
            $sid = $c2['sid'];
            $cid = $c2['cid'];
            $connect_tid = $c2['connect_tid'];
            $mid = $c2['connect_mid'];
            $r_nid = $c2['r_nid'];
            $said = $c2['said'];
            $finalsR_nid = $c1['r_nid'];
            $raceFinalsSql = "SELECT * from race where r_nid=$r_nid and kind=2 and appear=1";
            $raceFinalsSqlResult = $link->prepare($raceFinalsSql);
            $raceFinalsSqlResult->execute();
            $raceFinalsSqlRows = $raceFinalsSqlResult->fetchall(); ?>
            $('#upd_participate<?php echo $c2['connect_tid']; ?>').click(function()
            {
              window.location="backstage_participate.php?<?php echo 'r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&connect_tid='.$connect_tid.'&kind='.$kind.'&t_status='.$t_status.'&keyword='; ?>"
            })

            $('#upd_connet<?php echo $c2['connect_tid']; ?>').click(function()
            {
              window.location="backstage_connect_list.php?<?php echo 'r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&connect_tid='.$connect_tid.'&kind='.$kind.'&t_status='.$t_status.'&mid='.$mid.'&keyword='.$keyword; ?>"
            })

            $('#upd_finals<?php echo $c2['connect_tid']; ?>').click(function()
            {
              <?php
              if (!empty($raceFinalsSqlRows)) {
                  ?>
                $.ajax(
                {
                  url:'backstage_connect_finals_ajax.php',
                  type:"post",
                  cache: true,
                  async:false,
                  datatype:"json",
                  data:
                  {
                    "r_nid":<?php echo $r_nid; ?>,
                    "sid":<?php echo $sid; ?>,
                    "cid":<?php echo $cid; ?>,
                    "connect_tid":<?php echo $connect_tid; ?>,
                    "c_status":<?php echo $c_status; ?>,
                  },
                  error:function(data)
                  {
                    alert("編輯失敗");
                  },
                  success:function(data)
                  {
                    // console.log(data);return;
                    var dataobj=$.parseJSON($.trim(data));
                    if(dataobj.status=="success")
                    {
                      switch(dataobj.c_statudMessage)
                      {
                        case 'finals':
                          alert(dataobj.team_name+" 晉級決賽");
                          window.location="backstage_connect.php?<?php echo 'r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&connect_tid='.$connect_tid.'&kind='.$kind.'&keyword='.$keyword; ?>";
                          break;
                        case 'prelims':
                          alert(dataobj.team_name+" 退回預賽");
                          window.location="backstage_connect.php?<?php echo 'r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&connect_tid='.$connect_tid.'&kind='.$kind.'&keyword='.$keyword; ?>";
                          break;
                        default:
                          alert('error4');
                      }
                    }
                  }
                });
            <?php
              } elseif (empty($raceFinalsSqlRows)) {
                  ?>
                alert('請先編輯決賽資訊');
            <?php
              } ?>
            })

            $('#upd_seal<?php echo $c2['connect_tid']; ?>').click(function()
            {
              $.ajax(
              {
                url:'backstage_connect_seal_ajax.php',
                type:"post",
                cache: true,
                async:false,
                datatype:"json",
                data:
                {
                  "cid":<?php echo $cid; ?>,
                  "connect_tid":<?php echo $connect_tid; ?>,
                  "c_status":<?php echo $c_status; ?>,
                },
                error:function(data)
                {
                  alert("編輯失敗");
                },
                success:function(data)
                {
                  // console.log(data);return;
                  var dataobj=$.parseJSON($.trim(data));
                  if(dataobj.status=="success")
                  {
                    alert("已將 "+dataobj.team_name+" 隊伍封存");
                    window.location="backstage_connect.php?<?php echo 'r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&connect_tid='.$connect_tid.'&keyword='.$keyword; ?>";
                  }
                }
              });
            })
      <?php
        }
      ?>
      $('#search').click(function()
      {
        const keyword=$('#keyword').val();
        if(!keyword)
        {
          return false;
        }
        else
        {
          window.location="backstage_connect.php?<?php echo 'r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&kind='.$kind.'&t_status='.$t_status; ?>"+"&keyword="+keyword;
        }
      })
    })
    </script>
</body>

</html>
