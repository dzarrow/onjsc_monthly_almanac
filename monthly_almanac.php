<?
$get_mly = $_GET['mly'];
if ($get_mly=="") {
  $get_mly = "2012-07";
}
$year = substr($get_mly,0,4);
$month = substr($get_mly,5,2);
?>

<html>
<head>
<title>Monthly Almanac :: ONJSC</title>
<script type="text/javascript" src="tablesorter/jquery-latest.js"></script>
<script type="text/javascript" src="tablesorter/jquery.tablesorter.js"></script>
<script language="Javascript" type="text/javascript">
  function resetHeaders() {
  var elems = document.getElementsByTagName('th');
  for (i in elems) {
    var id = elems[i]['id'];
    if (id && id.indexOf('head_')==0) {
      if (id.indexOf('avgt')==5) {
	document.getElementById(id).style.background = "#bbbbbb";
      } else if (id.indexOf('maxt')==5) {
        document.getElementById(id).style.background = "#ffbbbb";
      } else if (id.indexOf('mint')==5) {
        document.getElementById(id).style.background = "#bbbbff";
      } else if (id.indexOf('pcpn')==5) {
        document.getElementById(id).style.background = "#bbffbb";
      } else if (id.indexOf('snow')==5) {
        document.getElementById(id).style.background = "#ffbbff";
      } else {
	document.getElementById(id).style.background = "#eeeeee";
      }
    }
  }
  return true;
}
$(document).ready(function() {
      $.tablesorter.addParser({ 
        id: 'truncplus', 
	    is: function(s) { return false; },
	    format: function(s) { 
	    s = s.replace("+","").replace("---",999).replace("*",".1").replace("<br/>"," ");
	    var spacer = s.indexOf(" ");
	    if (spacer>0) {
	      s = s.substr(0,spacer);
	    }
	    return s;
	  },
	    type: 'numeric' 
	    }); 
      var myHeaders = {};
      //for (i=2; i<=2; i++) {
      for (i=2; i<=26; i++) {
	myHeaders[i] = { sorter:'truncplus' };
      }
      $(function() { 
	  $("#myTable").tablesorter({ 
	    sortList: [[0,0]],
		headers: myHeaders
		}); 
	});
      var elems = document.getElementsByTagName('th');
      for (i in elems) {
	var id = elems[i]['id'];
	if (id && id.indexOf('head_')==0) {
	  $('#'+id).mouseenter(function() { document.body.style.cursor = 'pointer'; });
	  $('#'+id).mouseleave(function() { document.body.style.cursor = 'auto'; });
	  $('#'+id).click(function() { resetHeaders(); this.style.background='#ffff88'; });
	}
      }
      $('#changeMonth').change(function() {
	  window.location = window.location.href.replace(window.location.search,"") + "?mly=" + this.value;
	});
  });
</script>
<style type="text/css">
table {
  font-size: 8pt;
  border: 2px solid #000000;
  border-spacing: 0;
  padding: 0;
}
img { width:100%; }
.stn { width:240px; }
.county { width:60px; }
.avgt_mean, .maxt_mean, .mint_mean, .pcpn_sum, .snow_sum { width:40px; }
.avgt_depart, .maxt_depart, .mint_depart, .pcpn_depart, .snow_depart { width:40px; }
.avgt_rank, .maxt_rank, .mint_rank, .pcpn_rank, .snow_rank { width:50px; }
.avgt_max, .maxt_max, .mint_min, .pcpn_max, .snow_max { width:40px; }
.avgt_min, .maxt_count, .mint_count, .pcpn_count, .snow_count { width:40px; }

.stn, .county { background:#eeeeee; }
td#head_avgt, td#foot_avgt { background:#666666; color:#ffffff; }
td#head_maxt, td#foot_maxt { background:#660000; color:#ffffff; }
td#head_mint, td#foot_mint { background:#000066; color:#ffffff; }
td#head_pcpn, td#foot_pcpn { background:#006600; color:#ffffff; }
td#head_snow, td#foot_snow { background:#660066; color:#ffffff; }
.avgt_mean, .avgt_depart, .avgt_rank, .avgt_max, .avgt_min { background:#bbbbbb; }
.maxt_mean, .maxt_depart, .maxt_rank, .maxt_max, .maxt_count { background:#ffbbbb; }
.mint_mean, .mint_depart, .mint_rank, .mint_min, .mint_count { background:#bbbbff; }
.pcpn_sum, .pcpn_depart, .pcpn_rank, .pcpn_max, .pcpn_count { background:#bbffbb; }
.snow_sum, .snow_depart, .snow_rank, .snow_max, .snow_count { background:#ffbbff; }

thead th { border-bottom: 3px double #000000; }
thead td.head_image, thead td.head_selector { border-bottom: 3px double #000000; }
tfoot th { border-top: 3px double #000000; }
tbody td { border-bottom: 1px solid #000000; }
thead th#head_county { border-left: 1px solid #666666;}
td { border-left: 1px solid #666666; }
#head_avgt, #foot_avgt, .avgt_mean { border-left: 2px solid #000000; }
#head_maxt, #foot_maxt, .maxt_mean { border-left: 2px solid #000000; }
#head_mint, #foot_mint, .mint_mean { border-left: 2px solid #000000; }
#head_pcpn, #foot_pcpn, .pcpn_sum { border-left: 2px solid #000000; }
#head_snow, #foot_snow, .snow_sum { border-left: 2px solid #000000; }
table { border: 2px solid #000000; }

#head_avgt, #head_maxt, #head_mint, #head_pcpn, #head_snow { text-align:center; }
#foot_avgt, #foot_maxt, #foot_mint, #foot_pcpn, #foot_snow { text-align:center; }
thead td.head_selector { text-align:center; }
tbody td { text-align:center; }
tbody .stn { text-align:left; }

#head_avgt, #head_maxt, #head_mint, #head_pcpn, #head_snow { font-weight:bold; }
#foot_avgt, #foot_maxt, #foot_mint, #foot_pcpn, #foot_snow { font-weight:bold; }
.stn, .avgt_mean, .maxt_mean, .mint_mean, .pcpn_sum, .snow_sum { font-weight:bold; }

tbody td span {
  font-weight: normal;
  font-size: 7pt;
  color: #333333;
}
</style>
</head>
<body>
<table id="myTable">
<thead>
<tr>
<td class="head_selector" colspan="2">
<h1>ONJSC</h1>
<select id="changeMonth" name="changeMonth">
<?
    $months = array( '2011-08' => 'August 2011',
		     '2011-09' => 'September 2011',
		     '2011-10' => 'October 2011',
		     '2011-11' => 'November 2011',
		     '2011-12' => 'December 2011',
		     '2012-01' => 'January 2012',
		     '2012-02' => 'February 2012',
		     '2012-03' => 'March 2012',
		     '2012-04' => 'April 2012',
		     '2012-05' => 'May 2012',
		     '2012-06' => 'June 2012',
		     '2012-07' => 'July 2012' );
foreach (array_keys($months) as $m) {
  echo('<option value="' . $m . '"');
  if ($get_mly==$m) echo(' selected="selected"');
  echo('>' . $months[$m] . '</option>');
}
?>
</select>
<p><a target="_blank" href="https://github.com/dzarrow/onjsc_monthly_almanac">Github</a></p>
<p><a target="_blank" href="http://danzarrow.com/stateclim_v2/test_monthly.php">Monthly Historical</a></p>
<p><a target="_blank" href="http://klimat.rutgers.edu/~zarrow/stateclim_v2/station_history.php">Station History</a></p>
<p><a target="_blank" href="http://precip.net/realtime/">Precip.net Daily</a></p>
</td>
<td class="head_image" colspan="5"><img src="monthly_almanac/mly_<? echo($year . "_" . $month) ?>_avgt.png"/></td>
<td class="head_image" colspan="5"><img src="monthly_almanac/mly_<? echo($year . "_" . $month) ?>_maxt.png"/></td>
<td class="head_image" colspan="5"><img src="monthly_almanac/mly_<? echo($year . "_" . $month) ?>_mint.png"/></td>
<td class="head_image" colspan="5"><img src="monthly_almanac/mly_<? echo($year . "_" . $month) ?>_pcpn.png"/></td>
<td class="head_image"colspan="5"><img src="monthly_almanac/mly_<? echo($year . "_" . $month) ?>_snow.png"/></td>
</tr>

<tr>
<th id="head_stn" class="stn" rowspan="2">Station Name/ID</th>
<th id="head_county" class="county" rowspan="2">County</th>
<td id="head_avgt" colspan="5">Average Temperature</th>
<td id="head_maxt" colspan="5">Maximum Temperature</th>
<td id="head_mint" colspan="5">Minimum Temperature</th>
<td id="head_pcpn" colspan="5">Precipitation</th>
<td id="head_snow" colspan="5">Snowfall</th>
</tr>
<tr>
<th id="head_avgt_mean" class="avgt_mean">Mean</th>
<th id="head_avgt_depart" class="avgt_depart">Depart</th>
<th id="head_avgt_rank" class="avgt_rank">Rank</th>
<th id="head_avgt_max" class="avgt_max">Max</th>
<th id="head_avgt_min" class="avgt_min">Min</th>
<th id="head_maxt_mean" class="maxt_mean">Mean</th>
<th id="head_maxt_depart" class="maxt_depart">Depart</th>
<th id="head_maxt_rank" class="maxt_rank">Rank</th>
<th id="head_maxt_max" class="maxt_max">Max</th>
<th id="head_maxt_count" class="maxt_count">&gt;=90</th>
<th id="head_mint_mean" class="mint_mean">Mean</th>
<th id="head_mint_depart" class="mint_depart">Depart</th>
<th id="head_mint_rank" class="mint_rank">Rank</th>
<th id="head_mint_min" class="mint_min">Min</th>
<th id="head_mint_count" class="mint_count">&lt;=32</th>
<th id="head_pcpn_sum" class="pcpn_sum">Sum</th>
<th id="head_pcpn_depart" class="pcpn_depart">Depart</th>
<th id="head_pcpn_rank" class="pcpn_rank">Rank</th>
<th id="head_pcpn_max" class="pcpn_max">Max</th>
<th id="head_pcpn_count" class="pcpn_count">&gt;=0.01</th>
<th id="head_snow_sum" class="snow_sum">Sum</th>
<th id="head_snow_depart" class="snow_depart">Depart</th>
<th id="head_snow_rank" class="snow_rank">Rank</th>
<th id="head_snow_max" class="snow_max">Max</th>
<th id="head_snow_count" class="snow_count">&gt;=0.1</th>
</tr>
</thead>
<tbody>
<?

$string = file_get_contents('monthly_almanac/mly_'.$year.'_'.$month.'.json');
$data = json_decode($string, true);
foreach ($data as $line) {
  echo('<tr>');
  echo('<td class="stn">' . $line['meta']['name'] . '<br/>(#' . $line['meta']['id'] . ')</td>');
  echo('<td class="county">' . $line['meta']['county'] . '</td>');
  echo('<td class="avgt_mean">');
  if ($line['data'][0][0]=="M") {
    echo('---');
  } else {
    echo(number_format(round($line['data'][0][0],1),1));
    if ($line['data'][0][1] > 0) echo(' <br/><span>(' . $line['data'][0][1] . 'M)</span>');
  }
  echo('</td>');
  echo('<td class="avgt_depart">');
  if ($line['data'][1]=="M") {
    echo('---');
  } else {
    if ($line['data'][1]>=0.0) echo('+');
    echo(number_format(round($line['data'][1],1),1));
  }
  echo('</td>');
  echo('<td class="avgt_rank">');
  if ($line['data'][2][0]=="M") {
    echo('---');
  } else {
    echo($line['data'][2][0]+1);
    if ($line['data'][2][1]>0) echo('*');
    echo(' / ' . $line['data'][2][2]);
  }
  echo('</td>');
  echo('<td class="avgt_max">');
  if ($line['data'][3][0]=="M") {
    echo('---');
  } else {
    echo($line['data'][3][0]);
    echo(' <br/>');
    echo('<span>' . substr($line['data'][3][1],5,5) . '</span>');
  }
  echo('</td>');
  echo('<td class="avgt_min">');
  if ($line['data'][4][0]=="M") {
    echo('---');
  } else {
    echo($line['data'][4][0]);
    echo(' <br/>');
    echo('<span>' . substr($line['data'][4][1],5,5) . '</span>');
  }
  echo('</td>');

  echo('<td class="maxt_mean">');
  if ($line['data'][5][0]=="M") {
    echo('---');
  } else {
    echo(number_format(round($line['data'][5][0],1),1));
    if ($line['data'][5][1] > 0) echo(' <br/><span>(' . $line['data'][5][1] . 'M)</span>');
  }
  echo('</td>');
  echo('<td class="maxt_depart">');
  if ($line['data'][6]=="M") {
    echo('---');
  } else {
    if ($line['data'][6]>=0.0) echo('+');
    echo(number_format(round($line['data'][6],1),1));
  }
  echo('</td>');
  echo('<td class="maxt_rank">');
  if ($line['data'][7][0]=="M") {
    echo('---');
  } else {
    echo($line['data'][7][0]+1);
    if ($line['data'][7][1]>0) echo('*');
    echo(' / ' . $line['data'][7][2]);
  }
  echo('</td>');
  echo('<td class="maxt_max">');
  if ($line['data'][8][0]=="M") {
    echo('---');
  } else {
    echo($line['data'][8][0]);
    echo(' <br/>');
    echo('<span>' . substr($line['data'][8][1],5,5) . '</span>');
  }
  echo('</td>');
  echo('<td class="maxt_count">');
  if ($line['data'][9]=="M") {
    echo('---');
  } else {
    echo($line['data'][9]);
    echo(' <br/>');
    echo('<span>' . substr($line['data'][9],5,5) . '</span>');
  }

  echo('<td class="mint_mean">');
  if ($line['data'][10][0]=="M") {
    echo('---');
  } else {
    echo(number_format(round($line['data'][10][0],1),1));
    if ($line['data'][10][1] > 0) echo(' <br/><span>(' . $line['data'][10][1] . 'M)</span>');
  }
  echo('</td>');
  echo('<td class="mint_depart">');
  if ($line['data'][11]=="M") {
    echo('---');
  } else {
    if ($line['data'][11]>=0.0) echo('+');
    echo(number_format(round($line['data'][11],1),1));
  }
  echo('</td>');
  echo('<td class="mint_rank">');
  if ($line['data'][12][0]=="M") {
    echo('---');
  } else {
    echo($line['data'][12][0]+1);
    if ($line['data'][12][1]>0) echo('*');
    echo(' / ' . $line['data'][12][2]);
  }
  echo('</td>');
  echo('<td class="mint_min">');
  if ($line['data'][13][0]=="M") {
    echo('---');
  } else {
    echo($line['data'][13][0]);
    echo(' <br/>');
    echo('<span>' . substr($line['data'][13][1],5,5) . '</span>');
  }
  echo('</td>');
  echo('<td class="mint_count">');
  if ($line['data'][14][0]=="M") {
    echo('---');
  } else {
    echo($line['data'][14][0]);
    echo(' <br/>');
    echo('<span>' . substr($line['data'][14][1],5,5) . '</span>');
  }

  echo('<td class="pcpn_sum">');
  if ($line['data'][15][0]=="M") {
    echo('---');
  } else {
    echo(number_format(round($line['data'][15][0],2),2));
    if ($line['data'][15][1] > 0) echo(' <br/><span>(' . $line['data'][15][1] . 'M)</span>');
  }
  echo('</td>');
  echo('<td class="pcpn_depart">');
  if ($line['data'][16]=="M") {
    echo('---');
  } else {
    if ($line['data'][16]>=0.0) echo('+');
    echo(number_format(round($line['data'][16],2),2));
  }
  echo('</td>');
  echo('<td class="pcpn_rank">');
  if ($line['data'][17][0]=="M") {
    echo('---');
  } else {
    echo($line['data'][17][0]+1);
    if ($line['data'][17][1]>0) echo('*');
    echo(' / ' . $line['data'][17][2]);
  }
  echo('</td>');
  echo('<td class="pcpn_max">');
  if ($line['data'][18][0]=="M") {
    echo('---');
  } else {
    echo($line['data'][18][0]);
    echo(' <br/>');
    echo('<span>' . substr($line['data'][18][1],5,5) . '</span>');
  }
  echo('</td>');
  echo('<td class="pcpn_count">');
  if ($line['data'][19][0]=="M") {
    echo('---');
  } else {
    echo($line['data'][19][0]);
    echo(' <br/>');
    echo('<span>' . substr($line['data'][19][1],5,5) . '</span>');
  }

  echo('<td class="snow_sum">');
  if ($line['data'][20][0]=="M") {
    echo('---');
  } else {
    echo(number_format(round($line['data'][20][0],1),1));
    if ($line['data'][20][1] > 0) echo(' <br/><span>(' . $line['data'][20][1] . 'M)</span>');
  }
  echo('</td>');
  echo('<td class="snow_depart">');
  if ($line['data'][21]=="M") {
    echo('---');
  } else {
    if ($line['data'][21]>=0.0) echo('+');
    echo(number_format(round($line['data'][21],1),1));
  }
  echo('</td>');
  echo('<td class="snow_rank">');
  if ($line['data'][22][0]=="M") {
    echo('---');
  } else {
    echo($line['data'][22][0]+1);
    if ($line['data'][22][1]>0) echo('*');
    echo(' / ' . $line['data'][22][2]);
  }
  echo('</td>');
  echo('<td class="snow_max">');
  if ($line['data'][23][0]=="M") {
    echo('---');
  } else {
    echo($line['data'][23][0]);
    echo(' <br/>');
    echo('<span>' . substr($line['data'][23][1],5,5) . '</span>');
  }
  echo('</td>');
  echo('<td class="snow_count">');
  if ($line['data'][24][0]=="M") {
    echo('---');
  } else {
    echo($line['data'][24][0]);
    echo(' <br/>');
    echo('<span>' . substr($line['data'][24][1],5,5) . '</span>');
  }

  echo('</tr>');
}
?>
</tbody>
<tfoot>
<tr>
<th id="foot_stn" class="stn" rowspan="2">Station Name/ID</th>
<th id="foot_county" class="county" rowspan="2">County</th>
<th id="foot_avgt_mean" class="avgt_mean">Mean</th>
<th id="foot_avgt_depart" class="avgt_depart">Depart</th>
<th id="foot_avgt_rank" class="avgt_rank">Rank</th>
<th id="foot_avgt_max" class="avgt_max">Max</th>
<th id="foot_avgt_min" class="avgt_min">Min</th>
<th id="foot_maxt_mean" class="maxt_mean">Mean</th>
<th id="foot_maxt_depart" class="maxt_depart">Depart</th>
<th id="foot_maxt_rank" class="maxt_rank">Rank</th>
<th id="foot_maxt_max" class="maxt_max">Max</th>
<th id="foot_maxt_count" class="maxt_count">&gt;=90</th>
<th id="foot_mint_mean" class="mint_mean">Mean</th>
<th id="foot_mint_depart" class="mint_depart">Depart</th>
<th id="foot_mint_rank" class="mint_rank">Rank</th>
<th id="foot_mint_min" class="mint_min">Min</th>
<th id="foot_mint_count" class="mint_count">&lt;=32</th>
<th id="foot_pcpn_sum" class="pcpn_sum">Sum</th>
<th id="foot_pcpn_depart" class="pcpn_depart">Depart</th>
<th id="foot_pcpn_rank" class="pcpn_rank">Rank</th>
<th id="foot_pcpn_max" class="pcpn_max">Max</th>
<th id="foot_pcpn_count" class="pcpn_count">&gt;=0.01</th>
<th id="foot_snow_sum" class="snow_sum">Sum</th>
<th id="foot_snow_depart" class="snow_depart">Depart</th>
<th id="foot_snow_rank" class="snow_rank">Rank</th>
<th id="foot_snow_max" class="snow_max">Max</th>
<th id="foot_snow_count" class="snow_count">&gt;=0.1</th>
</tr>
<tr>
<td id="foot_avgt" colspan="5">Avg Temp</th>
<td id="foot_maxt" colspan="5">Max Temp</th>
<td id="foot_mint" colspan="5">Min Temp</th>
<td id="foot_pcpn" colspan="5">Precipitation</th>
<td id="foot_snow" colspan="5">Snowfall</th>
</tr>
</tfoot>
</table>
</body>
</html>