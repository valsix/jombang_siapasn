<?
/* *******************************************************************************************************
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: Class that responsible to handle file
***************************************************************************************************** */

class FileHandler {
	/* PROPERTIES */
	/** 
	* Full path item yang akan ditangani
	* @var string
	**/
	var $source; 
	
	/** 
	* Lokasi upload bila akan disimpan dalam direktori tertentu 
	* @var string
	**/
	var $dirLocation;
	
	/** 
	* Full path file setelah diupload
	* @var string
	**/
	var $uploadedFile;
	
	/** 
	* Nama file setelah diupload
	* @var string
	**/
	var $uploadedFileName;
	var $uploadedExtension;
	var $uploadedSize;
	
	/* METHODS */
        
        function FileHandler()
        {
            
        }

	  function split_pdf($filename, $end_directory = false)
	  {
		  require_once('fpdf/fpdf.php');
		  require_once('fpdi/fpdi.php');
		  
		  $end_directory = $end_directory ? $end_directory : './';
		  $new_path = preg_replace('/[\/]+/', '/', $end_directory.'/'.substr($filename, 0, strrpos($filename, '/')));
		  
		  if (!is_dir($new_path))
		  {
			  // Will make directories under end directory that don't exist
			  // Provided that end directory exists and has the right permissions
			  mkdir($new_path, 0777, true);
		  }
		  
		  $pdf = new FPDI();
		  $pagecount = $pdf->setSourceFile($filename); // How many pages?
		  
		  // Split each page into a new PDF
		  for ($i = 1; $i <= $pagecount; $i++) {
			  $new_pdf = new FPDI();
			  $new_pdf->AddPage();
			  $new_pdf->setSourceFile($filename);
			  $new_pdf->useTemplate($new_pdf->importPage($i));
			  
			  try {
				  $new_filename = $end_directory.str_replace('.pdf', '', $filename).'_'.$i.".pdf";
				  $new_pdf->Output($new_filename, "F");
			  } catch (Exception $e) {
				  echo 'Caught exception: ',  $e->getMessage(), "\n";
			  }
		  }
		  return $pagecount;
	  }

	  function zip_flatten( $zipfile, $dest='.' )
		{
			$zip = new ZipArchive;
			if ( $zip->open( $zipfile ) )
			{
				for ( $i=0; $i < $zip->numFiles; $i++ )
				{
					$entry = $zip->getNameIndex($i);
					if ( substr( $entry, -1 ) == '/' ) continue; // skip directories
				   
					$fp = $zip->getStream( $entry );
					$ofp = fopen( $dest.'/'.basename($entry), 'w' );
				   
					if ( ! $fp )
						throw new Exception('Unable to extract the file.');
				   
					while ( ! feof( $fp ) )
						fwrite( $ofp, fread($fp, 8192) );
				   
					fclose($fp);
					fclose($ofp);
				}
				$jumlah = $zip->numFiles;
				$zip->close();
			}
			else
				return 0;
		   
			return $jumlah;
		}

	function uploadToDir($varSource = "", $varDirLocation = "", $varRenameFile = "") 
	{
		ini_set('upload_max_filesize', '10M');
		ini_set('post_max_size', '10M');
		ini_set('max_input_time', 520);
		ini_set('max_execution_time', 300);
		set_time_limit(0);

		if($this->getFileExtension($varRenameFile) == "php" || $this->getFileExtension($varRenameFile) == "PHP")
			return false;
		
		if ($varSource !== "")
			$this->source = $varSource;

		if ($varDirLocation !== "") 
			$this->dirLocation = $varDirLocation;
		
		// if file renamed
		if ($varRenameFile !== "")
			$this->uploadedFileName = $varRenameFile;
		else
			$this->uploadedFileName = $_FILES[$varSource]['name'];

		$this->uploadedFile = $this->dirLocation.$this->uploadedFileName;
		$this->uploadedSize = $_FILES[$varSource]['size'];
		$this->uploadedExtension = $this->getFileExtension($this->uploadedFileName);
   
    	if(move_uploaded_file($_FILES[$this->source]['tmp_name'], $this->uploadedFile))
			return true;
		else
			return false;
	}
	
        
	function uploadToDirValidatePDF($varSource = "", $varDirLocation = "", $varRenameFile = "") 
	{
		ini_set('upload_max_filesize', '10M');
		ini_set('post_max_size', '10M');
		ini_set('max_input_time', 520);
		ini_set('max_execution_time', 300);
		set_time_limit(0);
		
		if($this->getFileExtension($varRenameFile) == "pdf")
		{}
		else
			return false;
		
		if ($varSource !== "")
			$this->source = $varSource;

		if ($varDirLocation !== "") 
			$this->dirLocation = $varDirLocation;
		
		// if file renamed
		if ($varRenameFile !== "")
			$this->uploadedFileName = $varRenameFile;
		else
			$this->uploadedFileName = $_FILES[$varSource]['name'];

		$this->uploadedFile = $this->dirLocation.$this->uploadedFileName;
		$this->uploadedSize = $_FILES[$varSource]['size'];
		$this->uploadedExtension = $this->getFileExtension($this->uploadedFileName);
   
    	if(move_uploaded_file($_FILES[$this->source]['tmp_name'], $this->uploadedFile))
			return true;
		else
			return false;
	}
	
	function uploadToDirValidatePDFArray($varSource = "", $varDirLocation = "", $varRenameFile = "", $varArray = "") 
	{

		if($this->getFileExtension($varRenameFile) == "pdf")
		{}
		else
			return false;

		if ($varSource !== "")
			$this->source = $varSource;

		if ($varDirLocation !== "") 
			$this->dirLocation = $varDirLocation;
		
		// if file renamed
		if ($varRenameFile !== "")
			$this->uploadedFileName = $varRenameFile;
		else
			$this->uploadedFileName = $_FILES[$varSource]['name'][$varArray];
			
		if($this->getFileExtension($varRenameFile) == "pdf")
		{}
		else
			return false;			

		$this->uploadedFile = $this->dirLocation.$this->uploadedFileName;
		$this->uploadedSize = $_FILES[$varSource]['size'][$varArray];
		$this->uploadedExtension = $this->getFileExtension($this->uploadedFileName);
   
    	if(move_uploaded_file($_FILES[$this->source]['tmp_name'][$varArray], $this->uploadedFile))
			return true;
		else
			return false;
	}
	
	function uploadToDirArray($varSource = "", $varDirLocation = "", $varRenameFile = "", $varArray = "") 
	{
		if ($varSource !== "")
			$this->source = $varSource;
		if ($varDirLocation !== "") 
			$this->dirLocation = $varDirLocation;
		
		// if file renamed
		if ($varRenameFile !== "")
			$this->uploadedFileName = $varRenameFile;
		else
			$this->uploadedFileName = $_FILES[$varSource]['name'][$varArray];

		$this->uploadedFile = $this->dirLocation.$this->uploadedFileName;
		$this->uploadedSize = $_FILES[$varSource]['size'][$varArray];
		$this->uploadedExtension = $this->getFileExtension($this->uploadedFileName);
   
    	if(move_uploaded_file($_FILES[$this->source]['tmp_name'][$varArray], $this->uploadedFile))
			return true;
		else
			return false;
	}
	
	function delete($varSource)
	{
		if ($varSource !== "")
			$this->source = $varSource;
		
		if (file_exists($this->source))
			return unlink($this->source);
		else
			return false;
	}
	
	function deleteAllow($varSource, $varDirLocation)
	{
		if ($varSource !== "")
			$this->source = $varSource;

		if ($varDirLocation !== "") 
			$this->dirLocation = $varDirLocation;

		if (is_dir($this->dirLocation )) {
		   if ($dh = opendir($this->dirLocation )) {
		       if (($file = readdir($dh)) !== false) {
		           //if (preg_match('/^D001_image\d\.jpg$/', $file))
		              unlink($this->source);
		       }
		       closedir($dh);
		   }
		   return true;
		}
	}
	
	function getFileName($varSource)
	{
		return $_FILES[$varSource]['name'];
	}
	
	function getFileNameArray($varSource, $i)
	{
		return $_FILES[$varSource]['name'][$i];
	}
	
	function getFileNameWithoutExtension($varSource)
	{
		$dotExt = ".".$this->getFileExtension($varSource);
		return str_replace($dotExt, "", $varSource);
	}
	
	function getFileExtension($varSource)
	{
		$temp = explode(".", $varSource);
		return end($temp);
	}

	function getExt($filedata, $img="jpg", $kondisi= false)
	{
		$ext= $this->getFileExtension($filedata['name']);
		if($kondisi == false)
		{
			if($ext == "png" || $ext == "jpeg" || $ext == "jpg")
			$ext= $img;
		}
		return $ext;
	}

	function formatbytes($bytes, $tipe= "MB") 
	{
		if(strtoupper($tipe) == "MB")
			return round($bytes / 1048576, 2);
		else
			return round($bytes / 1024, 2).' KB';
	}

	function checksizefile($filedata, $batas, $tipe="MB")
	{
		// print_r($filedata);exit();

		$value= "";
		for($i=0; $i < count($filedata); $i++)
		{
			$namafile= $filedata["name"][$i];
			$sizefile= $filedata["size"][$i];
			$sizefile= $this->formatbytes($sizefile);

			if($namafile == ""){}
			else
			{
				if(strtoupper($tipe) == "MB")
				{
					if($sizefile > $batas)
						return "1";
				}
				else
					return "1";
			}
		}
	}

	function convertToReadableSize($size){
		$base = log($size) / log(1024);
		$suffix = array("", "KB", "MB", "GB", "TB");
		$f_base = floor($base);
		return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
	}

	function checkmodifsizefile($filedata)
	{
		// print_r($filedata);exit();
		$value= "";
		for($i=0; $i < count($filedata); $i++)
		{
			$namafile= $filedata["name"][$i];
			$typefile= $filedata["type"][$i];
			$sizefile= $filedata["size"][$i];
			$sizefile= $this->convertToReadableSize($sizefile);

			if($typefile == "image/jpeg" || $typefile == "image/png" || $typefile == "application/pdf")
			{
				if(stripos($sizefile, "MB"))
				{
					$sizefile= str_replace("MB", "", $sizefile);

					if($sizefile > 1)
						return $namafile." lebih dari 1 MB";
				}

				if(stripos($sizefile, "KB"))
				{
					$sizefile= str_replace("KB", "", $sizefile);
					if($sizefile > 750)
						return $namafile." lebih dari 750 KB";
				}
			}
		}
	}

	function checkfile($filedata, $format, $multi=true)
	{
		$jumlahdata= count($filedata);
		if($multi == false)
			$jumlahdata= 1;

		// print_r($filedata);exit();

		$value= "";
		for($i=0; $i < $jumlahdata; $i++)
		{
			if($multi == false)
			{
				$namafile= $filedata["name"];
				$fileType= $filedata["type"];
			}
			else
			{
				$namafile= $filedata["name"][$i];
				$fileType= $filedata["type"][$i];
			}
			// echo $fileType;exit();

			if($namafile == ""){}
			else
			{
				if($format == "1")
				{
					if($fileType == "image/png" || $fileType == "image/jpeg" || $fileType == "image/jpg") {}
					else
					{
						return "1";
						break;
					}
				}
				elseif($format == "2")
				{
					if($fileType == "application/pdf") {}
					else
					{
						return "1";
						break;
					}
				}
				elseif($format == "3")
				{
					if($fileType == "image/png" || $fileType == "image/jpeg" || $fileType == "image/jpg" || $fileType == "application/pdf" || $fileType == "application/zip" || $fileType == "application/msword" || $fileType == "application/vnd.ms-excel" || $fileType == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" | $fileType == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {}
					else
					{
						return "1";
						break;
					}
				}

				elseif($format == "4")
				{
					if($fileType == "application/pdf" || $fileType == "application/zip" || $fileType == "application/msword" || $fileType == "application/vnd.ms-excel") {}
					else
					{
						return "1";
						break;
					}
				}

				elseif($format == "5")
				{
					if($fileType == "application/pdf" || $fileType == "application/zip" || $fileType == "application/msword" || $fileType == "application/vnd.ms-excel" || $fileType == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" | $fileType == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {}
					else
					{
						return "1";
						break;
					}
				}

				elseif($format == "6")
				{
					if($fileType == "image/png" || $fileType == "image/jpeg" || $fileType == "image/jpg" || $fileType == "application/pdf") {}
					else
					{
						return "1";
						break;
					}
				}
				
			}
		}
	}

	function getExtension($varSource)
	{
		$temp = explode(".", $varSource);
		return end($temp);
	}

	function setlinkfile($filedata, $delimeter=",", $multi=true)
	{
		$jumlahdata= count($filedata);
		if($multi == false)
			$jumlahdata= 1;

		// print_r($filedata);exit();

		$value= "";
		for($i=0; $i < $jumlahdata; $i++)
		{
			if($multi == false)
			{
				$namafile= $filedata["name"];
				$fileType= $filedata["type"];
			}
			else
			{
				$namafile= $filedata["name"][$i];
				$fileType= $filedata["type"][$i];
			}

			$filepath= $this->getExtension($namafile);

			if($namafile == ""){}
			else
			{
				if($value == "")
					$separator= "";
				else
					$separator= $delimeter;

				$renameFile = $i.".".$filepath;
				$value .= $separator.$renameFile;
			}
		}
		return $value;
	}

	function setuploadlinkfile($filedata, $target_dir, $multi=true)
	{
		$jumlahdata= count($filedata);
		if($multi == false)
			$jumlahdata= 1;

		// print_r($filedata);exit();

		$value= "";
		for($i=0; $i < $jumlahdata; $i++)
		{
			if($multi == false)
			{
				$namafile= $filedata["name"];
				$fileType= $filedata["type"];
			}
			else
			{
				$namafile= $filedata["name"][$i];
				$fileType= $filedata["type"][$i];
			}

			$filepath= $this->getExtension($namafile);

			if($namafile == ""){}
			else
			{
				$renameFile = $i.".".$filepath;
				
				if (move_uploaded_file($filedata['tmp_name'][$i], $target_dir.$renameFile))
				{
				}

			}
		}
		// exit();

	}

	function xrmdir($dir) {
	    $items = scandir($dir);
	    foreach ($items as $item) {
	        if ($item === '.' || $item === '..') {
	            continue;
	        }

	        $path = $dir.'/'.$item;

	        if (is_dir($path)) {
	            xrmdir($path);
	        } else {
	            unlink($path);
	        }
	    }
	    // rmdir($dir);
	}
	
}
?>