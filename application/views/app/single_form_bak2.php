<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="<?=base_url();?>" />

<link rel="stylesheet" href="css/gaya.css" type="text/css">
<link rel="stylesheet" href="css/admin.css" type="text/css">
<!--
<link rel="stylesheet" href="css/gaya-bootstrap.css" type="text/css">
-->
<!-- BOOTSTRAP -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<!--<script src="js/jquery-1.10.2.min.js"></script>-->
<script src="lib/bootstrap/js/jquery.min.js"></script>
<script src="lib/bootstrap/js/bootstrap.js"></script>
<link href="lib/bootstrap/css/bootstrap.css" rel="stylesheet">

<!-- FONT AWESOME -->
<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

<style>
html, body{
	height:100%;
}
@media screen and (max-width:767px) {
	html, body{
		height: auto;
	}
}
</style>

</head>

<body>
    <div class="container-fluid full-height">
   
        <div class="row full-height">
	        <div class="col-md-12 area-form full-height">
        
	        	<div id="judul-popup">Contoh single form</div>
            	
                <div class="area-form-inner">
                	<form >
                    
                        <div class="form-group" >
                            <label for="" class="col-md-2" >Pendidikan</label>
                            <div class="col-md-1">
                                <select name="reqPendidikan" id="reqPendidikan"  style="width:60px">
                                    <option value="1" >SD</option>
                                    <option value="2" >SLTP</option>
                                    <option value="3" >SLTA</option>
                                    <option value="4" >D.I</option>
                                    <option value="5" >D.II</option>
                                    <option value="6" >D.III</option>
                                    <option value="7" >D.IV</option>
                                    <option value="8" >S.1</option>
                                    <option value="9" >S.2</option>
                                    <option value="10" >S.3</option>
                                </select>
                            </div>
                            
                            <label for="" class="col-md-2" >Status Pendidikan</label>
                            <div class="col-md-2">
                                <select name="reqStatusPendidikan" id="reqStatusPendidikan"  >
                                    <option value="1" >Pendidikan CPNS</option>
                                    <option value="2" >Diakui</option>
                                    <option value="3" >Belum Diakui</option>
                                    <option value="4" >Riwayat</option>
                                </select>
                            </div>
                            
                            <label for="" class="col-md-1" >Jurusan</label>
                            <div class="col-md-2">
                                <input type="hidden" name="reqJurusanId" id="reqJurusanId" value="" /> 
		                        <input type="text"   name="reqJurusan" id="reqJurusan" value="" title="Jurusan harus diisi" class="autocompletevalidator form-control" />
                            </div>
                            
                            
                            
                        </div>
                        
                        <div class="form-group" >
                            <label for="" class="col-md-2" >Tipe Gelar</label>
                            <div class="col-md-5">
                                <select name="reqGelarTipe" id="reqGelarTipe" class="" >
                                    <option value="" selected>Tanpa gelar</option>
                                    <option value="1" >Depan</option>
                                    <option value="2" >Belakang</option>
                                </select>
                            </div>
                            
                            <label for="" class="col-md-1" >Gelar</label>
                            <div class="col-md-3">
                            	<input type="text" name="reqGelarNama" id="reqGelarNama"  value="" class="form-control" />
                            </div>
                        </div>
                        
                        <div class="form-group" >
                            <label for="" class="col-md-2" >Nama Sekolah</label>
                            <div class="col-md-4">
                                <input type="text" name="reqNamaSekolah"  value="" title="Nama sekolah harus diisi" class="required form-control" />
                            </div>
                            <label for="" class="col-md-2" >Kepala Sekolah</label>
                            <div class="col-md-4">
                                <input type="text"   name="reqKepalaSekolah"  value="" class="required form-control" />
                            </div>
                        </div>
                        
                        <div class="form-group" >
                            <label for="" class="col-md-2" >No. STTB</label>
                            <div class="col-md-4">
                                <input type="text"   name="reqNoSTTB"  value="" class="required form-control" />
                            </div>
                            <label for="" class="col-md-2" >Tgl. STTB</label>
                            <div class="col-md-4">
                                <input type="text"   name="reqTglSTTB" id="reqTglSTTB" required maxlength="10" class="dateIna form-control" onkeydown="return format_date(event,'reqTglSTTB');"  value="" />
                            </div>
                            
                        </div>
                        
                        <div class="form-group" >
                            <label for="" class="col-md-2" >No. Surat Ijin / Tugas Belajar</label>
                            <div class="col-md-4">
                                <input type="text"   name="reqNoSuratIjin"  value="" class="form-control" />
                            </div>
                            <label for="" class="col-md-2" >Tgl. Surat Ijin / Tugas Belajar</label>
                            <div class="col-md-4">
                                <input type="text"   name="reqTglSuratIjin" id="reqTglSuratIjin" maxlength="10" class="dateIna form-control" onkeydown="return format_date(event,'reqTglSuratIjin');"  value="" />
                            </div>
                        </div>
                        
                        <div class="form-group" >
                            <label for="" class="col-md-2" >Tempat Sekolah</label>
                            <div class="col-md-4">
                                <textarea name="reqAlamatSekolah"  class="required form-control"></textarea>
                            </div>
                        </div>
                        
                        
                        <button type="submit" class="btn btn-primary">Submit</button>
                        
                    </form>
                </div>
                
                
            </div>
        </div>        
    </div>
    
</body>
</html>
