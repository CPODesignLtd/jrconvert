<div style="background: #ff00ff;">
<?PHP
$stream = fopen('https://www.mhdspoje.cz/jrw50/hradec.php?' . (isset($_GET[page]) ? 'page=' . $_GET[page] :'') , 'r');
echo stream_get_contents($stream);
?>
</div>