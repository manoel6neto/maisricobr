<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />

<title><?php echo $title;?></title>
</head>
<body>

<div class="navigation">
<?php 
  // nav bar
  echo anchor('student/index', 'Home');
  echo (' | ');
  echo anchor('student/add', 'Add a New Student');
  echo (' | ');
  echo anchor('student/listing', 'List All Students');
?>
</div>

<h1><?php echo $headline;?></h1>

<?php $this->load->view($include);?>

</body>
</html>
