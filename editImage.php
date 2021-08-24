<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="stylesheet" href="/libs/jsCrop/js-crop.css" />
<script src="/libs/jsCrop/js-crop.js"></script>

<!-- or even simpler -->
<img class="my-image" src="<?php echo $_GET["url"]; ?>" />
<script>
var c = new Croppie(document.getElementById('my-image'), 
	{
    enableExif: true,
    viewport: {
        width: 200,
        height: 200,
        type: 'circle'
    },
    boundary: {
        width: 300,
        height: 300
    }
});

$uploadCrop = $('#my-image').croppie();
</script>