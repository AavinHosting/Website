<?php echo $_SERVER['REQUEST_URI']; ?> does not exist, sorry.<br>
<?php
if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
$refuri = parse_url($_SERVER['HTTP_REFERER']); // use the parse_url() function to create an array containing information about the domain
if($refuri['host'] == "localhost"){
echo "....";
}
else{
echo "...";
}
}
else{
echo "...";
}
?>
<script>
    window.location.href = 'https://www.aavin.com/';
</script>