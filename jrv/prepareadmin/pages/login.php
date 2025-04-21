<form enctype="multipart/form-data" name="frm" method="post" action="">
    <table align="center" class="login" onkeypress="key(event);">
      <tr> 
        <td rowspan="4"><img src="image/login.png"></img></td>
        <td style="padding-left: 20px; height: 32px; font-weight: bold;">přihlašovací jméno :</td>
      </tr>
      <tr>    
        <td style="padding-left: 20px; height: 32px;"><input id="username" type="text" style="width: auto;" name="username" value=""></td>
      </tr>
      <tr>    
        <td style="padding-left: 20px; height: 32px; font-weight: bold;">heslo :</td>
      </tr>
      <tr>    
        <td style="padding-left: 20px; height: 32px;"><input type="password" style="width: auto;" name="pass" value=""></td>
      </tr> 
      <tr>
        <td colspan="2" style="padding-top: 10px; width: 100%; text-align: center;">
<!--          <input style="width: 100%; height: 25px;" type="submit" name="post" value="pďż˝ihlďż˝sit">-->
          <div class="button" style="height: 35px; width: 300px;" onclick="document.frm['typeaction'].value = 'login'; document.frm.submit();">   
            <span></span><img src="image/key.png">
            přihlásit
          </div>
<!--<div id="add_packet" style="visibility: <?php echo (!isset($_GET[epack])) ? 'visible': 'hidden'; ?>; cursor: pointer;" class="wraptocenter" onClick="addPacket();"><span></span><img src="image/addplus.png">&nbsp;-&nbsp;pďż˝idat balďż˝ďż˝ek</div>-->          
        </td>        
      </tr>
    </table>
  <input id="typeaction" name="typeaction" type="text" value="" style="visibility: hidden; width: 0px; margin: 0px; padding: 0px; overflow: hidden;">
</form>

<script type="text/javascript">  
  
function key(event) {
  if (event.which == 13 || event.keyCode == 13) {
    document.frm['typeaction'].value = 'login'; document.frm.submit();
    return false;
  }
  return true;
}
</script>