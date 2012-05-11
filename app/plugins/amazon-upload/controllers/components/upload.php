<?php

App::import('Vendor', 'Amazon-Upload.S3', array('file' => 'S3/S3.php'));
//Configure::load('Upload.config');

class UploadComponent extends Object {
  
  // set name of the component
  public $name      = 'Upload';
  
  // set keys for S3
  public $AccessKey = '';
  public $SecretKey = '';
  
  // set address you are going to utilize
  public $Address   = 'https://s3.amazonaws.com';
  
  // set preliminary object for S3
  public $s3        = '';
  
  public function startup () {
		$this->AccessKey = Configure::read('S3.AccessKey');
		$this->SecretKey = Configure::read('S3.SecretKey');
		$this->Address   = Configure::read('S3.Address');
		
		$this->s3 = new S3($this->AccessKey, $this->SecretKey);
  }
  
  public function put ($file, $bucket, $prefix = '') {
					
		// set the temp filename
		$fileTempName = $file['tmp_name'];
		
		// create the filename
		$fn = explode('.', $file['name']);
		$ext = $fn[count($fn) - 1];
		$fileName = $prefix . uniqid() . '.'. $ext;
			
		//move the file
		$this->s3->putObjectFile($fileTempName, $bucket, $fileName, S3::ACL_PUBLIC_READ);
		
		// return url for link
		return $this->Address .'/'. $bucket .'/'. $fileName;
		
  }
  public function newBucket($name) {
    return $this->s3->putBucket($name, S3::ACL_PUBLIC_READ);
  }
  
  public function getBuckets () {
    return $this->s3->listBuckets();
  }
  
  public function getList ($bucket = 'STAV') {
    return $this->s3->getBucket($bucket);
  }  
  
}
?>
