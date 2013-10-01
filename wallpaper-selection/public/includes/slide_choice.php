<?
session_start();

function Runs($sts, $stp) {?><script>location.href = "<?=$sts?>?<?=$stp?>";</script><?}
function pro($o) { ?><pre><?print_r($o)?></pre><?; }

function split_title($title)
  {
    $t = array();
     list($t[0],  $t[2]) = explode('<ul>', $title);
     list($t[0],  $t[1]) = explode('<p><a', $t[0]);
	$t[1] = '<p><a'.$t[1];
	$t[2] = '<ul>'.$t[2];
	return $t;
  }	
$Ic = $_SESSION['Ic'];   

$onChange = 'onChange="this.form.submit();"';

$submit = $_POST['submits'];

if($submit == ' ' ) $_SESSION['ks'] = 0;

$E = false;
foreach(range(1, 100) as $i =>$k)  if(isset($_POST["sav$k"]))  { $submit = ' '; $E = true; $ks = $k; $_SESSION['ks'] = $ks; break; } 

if($submit == ' ' )  { $_POST['ord']  = ' '; $submit = 'Go!';}

$P = trim($_POST['ord']);
if($submit == 'Go!'):
 include 'GUI.php';
// include 'create_titles.php';
  $hat = $_SESSION['hat'];
  $titles = $_SESSION['titles'];
  $icons = $_SESSION['icons'];
  $nt = count($titles);
  
 if($P):  
  $N = $nt+1;
  $tr =array("\r","\n");
  $P = str_replace($tr, ' ', $P); 
  $lst = explode(' ', $P);
  $lst = str_replace('——', (string)$N, $lst);
  foreach($lst as $v) if(trim($v)) $lst_r[] = $v;
  if(count($lst_r) < $N):
    foreach(range(1, $N) as $i):
	  if(count($lst_r)) foreach($lst_r as $j)  if($i == abs($j))  continue 2;	
      $lst_r[] = $i;	
    endforeach;
  endif; 

  foreach($lst_r as $i => $j):
     if($j > 0 and $j < $N) $lst_n[] = $j;
     if($j < 0) $lst_m[] = abs($j);
	 if($j == $N) { $lst_n[] = $N; if(count($lst_m)) foreach($lst_m as $m) $lst_n[] = $m; }
   endforeach;  	 
  
  $c_s = '';
  foreach($lst_n as  $v)  $c_s .= "item-$v,";
  $c_s = substr($c_s, 0, -1);
  SetCookie("list4Order", $c_s, mktime(0, 0 ,0 , date('j')+7), "/"); 
  $_COOKIE['list4Order'] = $c_s;
  file_put_contents('Dlist', $_COOKIE['list4Order']);
 else: file_put_contents('Dlist', $_COOKIE['list4Order']);
 endif; 
 $ks = 0;
 $_SESSION['code'] = false;
endif; 

if($submit == 'Generate the code!' or $E):
   include 'GUI.php';
//   include 'create_titles.php';
    $hat = $_SESSION['hat'];
    $titles = $_SESSION['titles'];
    $icons = $_SESSION['icons'];
    $nt = count($titles);

   foreach($titles as $k => $v):
     if(isset($_POST["tit$k"])):
	   $t = split_title($v);
	   $titles[$k] = $_POST["tit$k"].$t[1].$t[2];
	 endif;  
	endforeach; 
   if(file_exists('Dlist')):
   include 'order.php';
 /*  
   include 'cookie_reset.php';
   copy($file_h, $file_b);
   include 'create_titles.php';
 */  
 @unlink('Dlist');
  endif;
 $_SESSION['code'] = true;
 Runs('index.php', 'submit=Preview');
endif;

$cont_fn = file_get_contents($file_h);
$cont_fn = str_replace($path_img_s, $path_img, $cont_fn);
$cont_fn = "<a href='index.php?submit=Preview'>Back</a>".$cont_fn;
file_put_contents('doc.html', $cont_fn);

?>
 <form method=post action='slide_choice.php'>
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?if($P):?>
 <input class="btn btn-success btn-large" style='margin-top: 1em;' type="submit" name="submits" value="Generate the code!">
 <?endif;?> 
<?if($_SESSION['code']):?>
  <a class='btn btn-info btn-large' style='margin-top: 1em;' href="doc.html">Review of the HTML document</a>  
  <a class='btn btn-info btn-large' style='margin-top: 1em;' href='archive-result.zip'>Download the .zip file</a>
<?endif;?> 
  <a class='btn btn-danger btn-large' style='margin-top: 1em;' href='index.php'>Back to the root menu</a>
 
<?	 

$N = $nt+1;
if(isset($_COOKIE['list4Order'])): 
  $C = str_replace('item-','', $_COOKIE['list4Order']);
  $C = str_replace(',',"\n", $C);
else:
   foreach(range(1, $N) as $i) $C .= "$i\n";
   $C = substr($C, 0, -1);
 endif;
   $C = str_replace((string) $N, '——', $C);
 
if(!$P):?>
<input style="position:fixed; left:1px; top:1px;" name="submits" class="btn btn-info btn-mini"  type="submit" value=" ">
<input style="position:fixed; left:19px; top:1px;" name="submits" class="btn btn-success btn-mini"  type="submit" value="Go!">
<?else:?>
<input style="position:fixed; left:8px; top:1px;" name="submits" class="btn btn-success btn-mini"  type="submit" value="Go!">
<?endif;?>
<textarea name="ord"  style="border: 1px solid;  position:fixed; left:2px; top: 23px; bottom:5px; height: 96%; width:45px; font-size: 12pt; font-weight: bold; padding:0 0 0 2px; line-height: 1.15em;" cols='3'><?=$C?></textarea>

 <ul class="modern" id="list4">
<?
 
	 foreach(range(0, $nt-1) as $j => $i):
       $i1 = $i+1;
       $t = split_title($titles[$i]);

	   list(, ,$t2) = explode('<li>', $t[2], 3);
	   $t[2] = '<ul><li>'.$t2;
	   $t[2] = trim(str_replace('without', "\r\nwithout", strip_tags($t[2])));

	   $t[1] = str_replace($path_img_s, $path_img, $t[1]);
	   $t[1] = str_replace('<p>', '<p class="txt-main">', $t[1]);

	   list($t01, ) = explode('</h3>', $t[0]);  
	   $t01r = str_replace('">', '"><b>'."[# $i1]</b> ", $t01);
	   $t0 = str_replace($t01, $t01r, $t[0]);
	   ?>
      <li id="item-<?=$i1?>">
<!--	   <input name="go<?=$i1?>" class="btn btn-success btn-mini"  type="submit" value="Go!">-->
	   <p style="line-height: 1.4em;"><?=$t0?></p>
	   <!--<img src="<?=$icons[$i]?>" align="middle" />-->
		<?=$t[1]?>
		<textarea name="tit<?=$i?>" <?=onChange?> style="border: 0; height: 175px; width: 52%; margin-left: 2px; resize:none;" ><?=$t[0]?></textarea>
      <input name="sav<?=$i1?>" class="btn btn-success btn-mini" style="float:right;"  type="submit" value="Save!">
	   <textarea  style="border: 0; height: 105px; width: 52%; margin-left: 2px;  resize:none;"  disabled="disabled"><?=$t[2]?></textarea>
		</li>
		<?
	  endforeach;
	  
  ?><li class="end-of-selection" id="item-<?=$N?>"><?="——— End of Selection ——— "?></li><?
         
  ?></ul>
 </form> 
 <?
 $ks = $_SESSION['ks'];
 $ns = (string) 18*(12 + 22*($ks-1));
  print  "<script>window.scrollBy(0, $ns);</script>";
 