<?php
include ('./includes/functions.inc.php');

$birth = $_POST['birth'];
$selection = $_POST['selection'];
$image = makeChart($selection, $birth);
echo $image;


function createAxisLabels($todate) {
	$months = array("1"=>"Jan", "2"=>"Feb", "3"=>"Mar", "4"=>"Apr",
						"5"=>"May", "6"=>"Jun", "7"=>"Jul", "8"=>"Aug",
						"9"=>"Sep", "10"=>"Oct", "11"=>"Nov", "12"=>"Dec");
	
	$dateArray = explode('-', $todate);
	$to = gregoriantojd((int)$dateArray[0], (int)$dateArray[1], (int)$dateArray[2]);

	$to = $to - 10;
	for ($i = $to; $i <= ($to+10*2); $i= $i+2) {
		$gregorian = jdtogregorian($i);
		$datepieces = explode('/', $gregorian);
		$month = $months[$datepieces[0]];
		$axisLabels[] = $month.' '.$datepieces[1];
	}
	return $axisLabels;

}

function getDateRange($fromdate, $todate) {
	$from = explode('-', $fromdate);
	$to = explode('-', $todate);
	$from = gregoriantojd((int)$from[0], (int)$from[1], (int)$from[2]);
	$to = gregoriantojd((int)$to[0], (int)$to[1], (int)$to[2]);
	return ($to - $from);
}

function makeChart($selection, $birth) {
	$dateRange = getDateRange($birth, $selection);

	$filename = './images/graphs/'.$birth.'-'.$dateRange.'.png';
	if (file_exists($filename))
		return $filename;

	$startx = $dateRange - 10;
	$endx = $dateRange + 10;

	$axisLabels = createAxisLabels($selection);
	$chartLabels = implode("|", $axisLabels);

	$physvalue = round(100*sin(2*pi()*$dateRange/23));
	$emotvalue = round(100*sin(2*pi()*$dateRange/28));
	$intellvalue = round(100*sin(2*pi()*$dateRange/33));

	//Uses curl to get the html from the webpage
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_USERAGENT, "Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$fp = fopen($filename, "w");
	curl_setopt($ch, CURLOPT_FILE, $fp);

	$postfields = array(
		'cht'=>'lc',
		'chco'=>'FF0000,00FF00,0000FF',
		'chd'=>'t:0|1|2',
		'chs'=>'600x450',
		'chdlp'=>'r',
		'chdl'=>'Physical: '.$physvalue.'|Emotional: '.$emotvalue.'|Intellectual: '.$intellvalue,
		'chxt'=>'x,y',
		'chg'=>'5,10',
		'chm'=>'R,FF8C00,0,.498,.502|r,FF8C00,0,.498,.502',
		'chxr'=>'1,-100,100,20',
		'chxtc'=>'1,5',
		'chma'=>'0,0,0,0|1,420',
		'chds'=>'-100,100',
		'chfd'=>'0,x,'.$startx.','.$endx.',.1,sin(6.28318*x/23)*100|1,y,'.$startx.','.$endx.',.1,sin(6.28318*y/28)*100|2,z,'.$startx.','.$endx.',.1,sin(6.28318*z/33)*100',
		'chxl'=>'0:|'.$chartLabels
	);

	$postdata = http_build_query($postfields);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	curl_setopt($ch, CURLOPT_URL, "https://chart.googleapis.com/chart");
	$html = curl_exec($ch);
	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	curl_close($ch);

	if ($status == 200) {
		return $filename;
	} else {
		return "Error generating graph";
	}
}




?>