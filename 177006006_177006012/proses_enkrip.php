<?php
  	include 'header.php';

  	//Memanggil class aes
	include 'class/aes.class.php';
	include 'class/aesctr.class.php';

	//Memanggil class huffman
	// include 'class/huffmancoding.php';

  //Fungsi Proses Enkripsi RC4
  function setupkey(){  //proses KSA key scheduling algoritm
    error_reporting(E_ALL ^ (E_NOTICE));

    $pass = $_POST["katakunci"];
    $key=array();
    for($i=0;$i<256;$i++){
      $key[$i]=ord($pass[$i % strlen($pass)]);
    }//ambil nilai ASCII dari tiap karakter password
     //masukan password ke array key secara berulang sampai penuh

     //isi array s
    global $s;
    $s=array();
    for($i=0;$i<256;$i++){
      $s[$i] = $i;//isi array s 0 s/d 255
    }

     //permutasi/pengacakan isi array s
    $j = 0;
    $i = 0;
    for($i=0;$i<256;$i++){
      $a = $s[$i];
      $j = ($j + $s[$i] + $key[$i]) % 256;
      $s[$i] = $s[$j]; //swap
      $s[$j] = $a;
    }
  }

  //proses PRGA
  function enkrip($plainttext){
    global $s;
    $x=0;$y=0;
    $ciper='';
    for($n=1;$n<= strlen($plainttext);$n++){
      $x = ($x+1) % 256;
      $a = $s[$x];
      $y = ($y+$a) % 256;
      $s[$x] = $b = $s[$y];//swap
      $s[$y] = $a;
      /*proses XOR antara plaintext dengan kunci
      dengan $plainttext sebagai plaintext
      dan $s sebagai kunci*/
      $ciper = ($plainttext^$s[($a+$b) % 256]) % 256;
      return $ciper;
    }
  }

	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', '-1');

	$timer = microtime(true);

  $panjangpass = $_POST['katakunci']; //key untuk rc4
	$pw = $_POST['kunci']; //key untuk aes
	$pt = $_FILES['file']['name'];
  $uploaded_ext = substr($pt, strrpos($pt, '.') + 1);
	$uploaded_size = $_FILES["file"]["size"];
	$cipher = empty($_POST['cipher']) ? '' : $_POST['cipher'];

	$encr = empty($_POST['encr']) ? $cipher : AesCtr::encrypt($pt, $pw, 256);

	function microtime_float(){
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	$time_start = microtime_float();

	if ($_FILES['file']['error'] == UPLOAD_ERR_OK
		&& is_uploaded_file($_FILES['file']['tmp_name'])){
			$pt = file_get_contents($_FILES['file']['tmp_name']);
			$cipher = AesCtr::encrypt($pt, $pw, 256);

		//Memulai proses kompresi
		// $komp = $cipher;

		// $encoding = HuffmanCoding::createCodeTree($komp);
		// $encoded = HuffmanCoding::encode ($komp, $encoding);

    // move_uploaded_file($_FILES["file"]["tmp_name"],"hasil/temp");
    // $isifile = file_get_contents("hasil/temp");

    // Algoritma Enkripsi RC4
    setupkey();
    for($i=0;$i<strlen($cipher);$i++){
     $kode[$i]=ord($cipher[$i]); /*rubah ASCII ke desimal*/
     $b[$i]=enkrip($kode[$i]); /*proses enkripsi RC4*/
     $c[$i]=chr($b[$i]);
    }
    $hasil = '';
    for($i=0;$i<strlen($cipher);$i++){
      $hasil = $hasil . $c[$i];
    }


		if(strlen($pw)<8){
			echo "<script>alert('Password Kurang dari 8 Karakter');window.location='enkrip.php';</script>";
     			return;
		}
 		if($uploaded_ext != "txt" && $uploaded_ext != "xls" && $uploaded_ext != "xlsx" && $uploaded_ext != "pdf" && $uploaded_ext != "docx" && $uploaded_ext != "doc"){
				echo "<script>alert('File Harus .doc, .docx, .xls, .xlsx, .pdf, atau .txt');window.location='enkrip.php';</script>";
				return;
		}

    if($_FILES["file"]["error"] != 0){
  		echo "<script>alert('Tidak ada file yang diupload!')</script>";
  		echo "<a href=?hal=enkrip> <button class='tombol' name ='Kembali'>Kembali</button> </a>";
  		return;
  	}
  	if(strlen($panjangpass)<8){
  		echo "<script>alert('Password kurang dari 8 atau Password Kosong!')</script>";
  		echo "<a href=?hal=enkrip> <button class='tombol' name ='Kembali'>Kembali</button> </a>";
  		return;
  	}
  	if($uploaded_ext != "txt" && $uploaded_ext != "xls" && $uploaded_ext != "xlsx" && $uploaded_ext != "pdf" && $uploaded_ext != "docx" && $uploaded_ext != "doc"){
  		echo "<script>alert('File yang dipilih tidak valid')</script>";
  		echo "<a href=?hal=enkrip> <button class='tombol' name ='Kembali'>Kembali</button> </a>";
  		return;
  	}
  	if($uploaded_size > 2097152){
  		echo "<script>alert('File yang dimasukan lebih besar dari 2MB')</script>";
  		echo "<a href=?hal=enkrip> <button class='tombol' name ='Kembali'>Kembali</button> </a>";
  		return;
  	}


  	//Menyimpan File Hasil
    move_uploaded_file($_FILES["file"]["tmp_name"],$_FILES["file"]["name"]);
	  $namafile = $_FILES['file']['name'];
    $nm_file= preg_replace("/\s+/", "_", $namafile);
    // $nm_file= str_replace(" ","_", $rep);


		$fp = fopen("hasil/Enkrip_".$nm_file,"w");
		fwrite($fp, $hasil);
		fclose($fp);
		$time_end = microtime_float();
		$time = $time_end - $time_start;
    $nama_file = "Enkrip_".$nm_file;
?>

<div class="py-5" style="">
    <div class="container">
      <div class="row" style="">
        <div class="col-md-12">
          <form id="c_form-h" class="">
            <h3 class="">Hasil Encrypt</h3>
            <div class="form-group row"> <label class="col-2 col-form-label">Nama File</label>
              <div class="col-10">
                : <?php echo $_FILES["file"]["name"];?> </div>
            </div>
            <div class="form-group row"> <label class="col-2 col-form-label">Type File</label>
              <div class="col-10">
                : <?php echo $_FILES["file"]["type"];?> </div>
            </div>
          </form>
          <form id="c_form-h" class="">
            <div class="form-group row"> <label class="col-2 col-form-label">Ukuran File</label>
              <div class="col-10">
                : <?php echo ($_FILES["file"]["size"] / 1024);?> Kb </div>
            </div>
            <div class="form-group row"> <label class="col-2 col-form-label">File Encrypt</label>
              <div class="col-10">
                : <?php echo $nama_file;?> </div>
            </div>
          </form>
          <form id="c_form-h" class="">
            <div class="form-group row"> <label class="col-2 col-form-label">Waktu Proses</label>
              <div class="col-10">
                : <?php echo "$time seconds\n";?> </div>
            </div>
          </form><a href="enkrip.php"><button type="submit" class="btn btn-primary">BACK</button></a> <a href="<?php echo 'download.php?download_file='.$nama_file ?>"><button type="submit" class="btn btn-primary mx-5">DOWNLOAD</button></a> <a href="dekrip.php"><button type="submit" class="btn btn-primary">DECRYPT</button></a>
        </div>
      </div>
    </div>
  </div>
					<?php
						}else{
							echo "<script>alert('File Gagal di Enkrip');window.location = 'enkrip.php';</script>";
						}
					?>
				</div>
      </div>
		</div>
  </div>
</div>

<?php
	include 'footer.php';
?>
