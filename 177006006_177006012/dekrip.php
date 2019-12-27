<?php
  	include 'header.php';
?>
<div class="py-5 bg-primary" style="">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2 class="">Decrypt Advanced Encryption Standard (AES)</h2>
        </div>
      </div>
      <div class="row">
      <form action="proses_dekrip.php" method="POST" enctype="multipart/form-data">
        <div class="col-md-12">
          <h5 class="">Masukkan File</h5>
          <form id="c_form-h" class="">
            <div class="form-group my-3"> <input type="file" name="file" class="form-control-file" id="exampleFormControlFile1"> </div>
            <h5 class="" contenteditable="true">Masukkan Kunci</h5>
            <div class="form-group row"> <label for="inputpasswordh" class=" col-form-label"></label>
              <div class="col-10">
                <input name="kunci" type="password" class="form-control bg-primary" id="inputpasswordh" placeholder="Masukkan kunci AES" maxlength="20"> </div>
            </div>
            <div class="form-group row"> <label for="inputpasswordh" class="col-form-label"></label>
              <div class="col-10">
                <input name="katakunci" type="password" class="form-control bg-primary" id="inputpasswordh" placeholder="Konfirmasi kunci AES" maxlength="20"> </div>
            </div>
            <button type="submit" class="btn btn-light">DECRYPT</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php
	include 'footer.php';
?>