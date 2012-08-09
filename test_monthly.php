<?
$stns = $_GET['stns'];
if ($stns=="") {
  $stns = "286053, 286062, 286055";
}
$elem = $_GET['elem'];
if ($elem=="") {
  $elem = "maxt";
}

$data_mly_all = array();
$data_yly_all = array();
if (!is_array($stns)) {
  $stns = explode(",",$stns);
}
if ($elem=="maxt" or $elem=="mint" or $elem=="avgt") {
  $reduce = "mean";
} else if ($elem=="pcpn" or $elem=="snow") {
  $reduce = "sum";
}
$stn_name = "";
$stn_id = "";
foreach ($stns as $stn) {
  $stn = trim($stn);
  $input_mly = array('sId' => $stn, 'sDate' => 'por', 'eDate' => 'por',
                     'elems' => array(array('name' => $elem,
                                            'interval' => 'mly',
                                            'duration' => 'mly',
                                            'maxmissing' => 3,
                                            'reduce' => array('reduce'=>$reduce, 'add' => 'mcnt') ) ),
                     'meta' => array('sids','name','state','valid_daterange') );
  $string_mly = file_get_contents('http://data.rcc-acis.org/StnData?params=' . urlencode(json_encode($input_mly)) . '&output=json');
  $data_mly = json_decode($string_mly, true);
  $data_mly_all = array_merge($data_mly_all,$data_mly['data']);
  if ($stn_name=="") {
    $stn_name = $data_mly['meta']['name'];
  } else {
    $stn_name = $stn_name . " / " . $data_mly['meta']['name'];
  }

  if ($stn_id=="") {
    $stn_id = $stn;
  } else {
    $stn_id = $stn_id . " / " . $stn;
    }

  $input_yly = array('sId' => $stn, 'sDate' => 'por', 'eDate' => 'por',
		     'elems' => array(array('name' => $elem,
					    'interval' => 'yly',
					    'duration' => 'yly',
					    'maxmissing' => 36,
					    'reduce' => array('reduce'=>$reduce, 'add' => 'mcnt') ) ),
		     'meta' => array('sids','name','state','valid_daterange') );
  $string_yly = file_get_contents('http://data.rcc-acis.org/StnData?params=' . urlencode(json_encode($input_yly)) . '&output=json');
  $data_yly = json_decode($string_yly, true);
  $data_yly_all = array_merge($data_yly_all,$data_yly['data']);
}

sort($data_mly_all);
sort($data_yly_all);

$data = array();
$years = range(substr($data_mly_all[0][0],0,4), substr($data_mly_all[count($data_mly_all)-1][0],0,4));
foreach ($years as $year) {
  $data[$year] = array();
  foreach (range(0,13) as $n) {
    $data[$year][] = array('-999','z');
  }
}

$monthlys = array(array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array());

$letters = array('','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','z','z','z','z','z');

$titles = array('maxt' => 'Monthly Mean Maximum Temperature',
		'mint' => 'Monthly Mean Minimum Temperature',
		'avgt' => 'Monthly Mean Temperature',
		'pcpn' => 'Monthly Precipitation',
		'snow' => 'Monthly Snowfall');

$decimals = 1;
if ($elem=='pcpn') {
  $decimals = 2;
}

foreach ($data_mly_all as $line) {
  $year = substr($line[0],0,4) * 1;
  $month = substr($line[0],5,2) * 1;
  if ($line[1][0]=="T") {
    $val = number_format(0, $decimals);
  } else if (is_numeric($line[1][0])) {
    $val = number_format($line[1][0], $decimals);
  } else {
    $val = -999;
  }
  $data[$year][$month] = array($val, $letters[$line[1][1]]);
  if ($val!=-999) {
    $monthlys[$month][] = $val;
  }
}

foreach ($data_yly_all as $line) {
  $year = $line[0] * 1;
  if ($line[1][0]=="T") {
    $val = number_format(0, $decimals);
  } else if (is_numeric($line[1][0])) {
    $val = number_format($line[1][0], $decimals);
  } else {
    $val = -999;
  }
  $data[$year][13] = array($val, '');
  if ($val!=-999) {
    $monthlys[13][] = $val;
  }
}
?>

<html>
<head>
<title>Monthly Mean Maximum Temperature :: New Brunswick, NJ</title>
<script type="text/javascript" src="tablesorter/jquery-latest.js"></script>
<script type="text/javascript" src="tablesorter/jquery.tablesorter.js"></script>
<script language="Javascript" type="text/javascript">
  $(document).ready(function() {
      $('#myTable').tablesorter( {sortList: [[0,0]]} );
    });
</script>
<link rel="stylesheet" href="tablesorter/themes/blue/style.css" type="text/css" id="" media="print, projection, screen" />
<style type="text/css">
  table {
width: auto;
}
thead th {
width: 35px;
}
tbody th {
width: 40px;
}
  td {
  text-align: right;
  width: auto;
  width: 35px;
}
</style>
</head>
<body>
<h3>Monthly Mean Maximum Temperature</h3>
    <h4>Station: <? echo($stn_name) ?></h4>
    <h4>ID: <? echo($stn_id) ?></h4>
<table id="myTable" class="tablesorter" border="1" cellspacing="0" style="width:auto; font-size:10pt;">
<thead>
<tr>
<th colspan="2" rowspan="2" style="text-align:center;">YEAR</th>
<?
    for($n=1; $n<=13; ++$n) {
?>
<td style="text-align:center; background:#e6eeee; border:1 px solid #ffffff;"><a style="text-decoration:none;" href="#"><img src="http://www.dietcontroller.com/DietController/images/Home_Page/New_chart.jpg" style="width:16px;"/></a></td>
<?
    }
?>
</tr>
<tr>
<th>JAN</th>
<th>FEB</th>
<th>MAR</th>
<th>APR</th>
<th>MAY</th>
<th>JUN</th>
<th>JUL</th>
<th>AUG</th>
<th>SEP</th>
<th>OCT</th>
<th>NOV</th>
<th>DEC</th>
<th style="width:60px;">ANNUAL</th>
</tr>
</thead>
<tbody>
<?
  foreach ($years as $year) {
    echo("<tr>");
    echo("<th style=\"text-align:center;\">" . $year . "</th>");
    echo("<td style=\"text-align:center; width:20px;\"><a href=\"#\" style=\"text-decoration:none;\"><img src=\"http://www.dietcontroller.com/DietController/images/Home_Page/New_chart.jpg\" style=\"width:16px;\"/></a></td>");
    $monthly_missing = 0;
    foreach (range(1,13) as $month) {
      if ($data[$year][$month][0]==-999) {
	echo("<td style=\"background:#999999;\">");
      } else if ($data[$year][$month][1]!="") {
	echo("<td style=\"background:#dddddd;\">");
      } else if ($data[$year][$month][0]==max($monthlys[$month])) {
	echo("<td style=\"background:#ffbbbb;\">");
      } else if ($data[$year][$month][0]==min($monthlys[$month])) {
	echo("<td style=\"background:#bbbbff;\">");
      } else {
	echo("<td>");
      }
      if ($month==13) {
	$data[$year][$month][1] = $letters[$monthly_missing];
      } else if ($data[$year][$month][0]==-999) {
	++$monthly_missing;
      }
      echo($data[$year][$month][0] . $data[$year][$month][1] . "</td>");
      echo("</td>");
    }
    echo("</tr>");
  }
?>
</tbody>
</table>
<br/>
<table border="1" cellspacing="0" class="tablesorter" style="width:auto; font-size:10pt;">
<thead>
<tr>
<th colspan="2"></th>
<th>JAN</th>
<th>FEB</th>
<th>MAR</th>
<th>APR</th>
<th>MAY</th>
<th>JUN</th>
<th>JUL</th>
<th>AUG</th>
<th>SEP</th>
<th>OCT</th>
<th>NOV</th>
<th>DEC</th>
<th style="width:60px;">ANNUAL</th>
</tr>
</thead>
<tbody>
<tr>
<th>Mean</th>
<td style="text-align:center; width:20px;"><a href="#" style="text-decoration:none;"><img src="http://www.dietcontroller.com/DietController/images/Home_Page/New_chart.jpg" style="width:16px;"/></a></td>
<?
foreach (range(1,13) as $month) {
  echo("<td>" . number_format(array_sum($monthlys[$month])/count($monthlys[$month]), $decimals) . "</td>");
}
?>
</tr>
<tr>
<th>Median</th>
<td style="text-align:center; width:20px;"><a href="#" style="text-decoration:none;"><img src="http://www.dietcontroller.com/DietController/images/Home_Page/New_chart.jpg" style="width:16px;"/></a></td>
<?
foreach (range(1,13) as $month) {
  sort($monthlys[$month]);
  if ((count($monthlys[$month]) % 2)==0) {
    $ind1 = count($monthlys[$month])/2 - 1;
    $ind2 = count($monthlys[$month])/2;
    $med = ($monthlys[$month][$ind1] + $monthlys[$month][$ind2]) / 2;
  } else {
    $ind1 = (count($monthlys[$month]) - 1) / 2;
    $ind2 = -1;
    $med = $monthlys[$month][$ind1];
  }
  echo("<td>" . number_format($med, $decimals) . "</td>");
}
?>
</tr>
<tr>
<th>Max</th>
<td style="text-align:center; width:20px;"><a href="#" style="text-decoration:none;"><img src="http://www.dietcontroller.com/DietController/images/Home_Page/New_chart.jpg" style="width:16px;"/></a></td>
<?
foreach (range(1,13) as $month) {
  echo("<td style=\"background:#ffbbbb;\">" . number_format(max($monthlys[$month]), $decimals) . "</td>");
}
?>
</tr>
<tr>
<th>Min</th>
<td style="text-align:center; width:20px;"><a href="#" style="text-decoration:none;"><img src="http://www.dietcontroller.com/DietController/images/Home_Page/New_chart.jpg" style="width:16px;"/></a></td>
<?
foreach (range(1,13) as $month) {
  echo("<td style=\"background:#bbbbff;\">" . number_format(min($monthlys[$month]), $decimals) . "</td>");
}
?>
</tr>
</tbody>
</table>
</body>
</html>