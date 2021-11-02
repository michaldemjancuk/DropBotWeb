<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<ol class="list-group list-group-numbered">
<?php

function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');   

    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}

$files = glob("*.mp4");
for ($i=0; $i < count($files); $i++) { 
	$fileName = $files[$i];
	$filmName = basename($fileName, '.mp4');
?>

	<li class="list-group-item d-flex justify-content-between align-items-start">
		<div class="ms-2 me-auto">
			<div><b><?php echo $filmName ?></b> - <i><?php echo formatBytes(filesize($fileName)) ?></i></div>
		</div>
		<span class="badge bg-dark rounded-pill"><a class="text-light" href="<?php echo $fileName ?>">Shlédni film</a></span>
		<span class="badge bg-dark rounded-pill"><a class="text-light" href="<?php echo $fileName ?>" download>Stáhni film</a></span>
	</li>
<?php } ?>
</ol><a href="https://www.toplist.cz/stat/1809509/"><script language="JavaScript" type="text/javascript" charset="utf-8">
<!--
document.write('<img src="https://toplist.cz/count.asp?id=1809509&logo=bc&http='+
encodeURIComponent(document.referrer)+'&t='+encodeURIComponent(document.title)+'&l='+encodeURIComponent(document.URL)+
'&wi='+encodeURIComponent(window.screen.width)+'&he='+encodeURIComponent(window.screen.height)+'&cd='+
encodeURIComponent(window.screen.colorDepth)+'" width="88" height="120" border=0 alt="TOPlist" />');
//--></script><noscript><img src="https://toplist.cz/count.asp?id=1809509&logo=bc&njs=1" border="0"
alt="TOPlist" width="88" height="120" /></noscript></a>