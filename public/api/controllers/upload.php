<?php

	/* Getting file name */
	$filename = date('Ymd').' '.$_FILES['file']['name'];

	/* Location */
	$location = '../../uploads/';

	/* Upload file */
	move_uploaded_file($_FILES['file']['tmp_name'],$location.$filename) or die("no copy possible");
