<?

require('image_resize_func.php');

function show_upload_image_form()
{
	?>
	<h2>Upload Image</h2>
	<form action=<?=$_SERVER['PHP_SELF']?> method="post" enctype="multipart/form-data">
		<p><label for="image">Image: </label>
		<input type="file" name="image" /></p>
		<p><input type="submit" value="Upload Image" /></p>
	</form>
	<?php
}
function upload_image($upload_image_dir,$upload_image_thumb_dir,$field_name)
{
	$filename=$_FILES[$field_name]['name'];
	$tmp_name=$_FILES[$field_name]['tmp_name'];
	$file_type=$_FILES[$field_name]['type'];
	if(is_uploaded_file($tmp_name)) 
		if(strpos($file_type,"jpg")!=false || strpos($file_type,"jpeg")!=false || 
		   strpos($file_type,"png")!=false || strpos($file_type,"gif")!=false)
			if(move_uploaded_file($tmp_name,$upload_image_dir.'/'.$filename))
			{
				$image_resize = new SimpleImage();
				$image_resize->load($upload_image_dir.'/'.$filename);
				$image_resize->resizeToWidth(200);
				$image_resize->save($upload_image_thumb_dir.'/'.$filename);
				return $filename;
			}
	return false;
}

function show_gallery($upload_image_dir,$rowsize)
{
	if($dir=opendir($upload_image_dir))
	{
		echo "<h2>View Gallery</h2>";
		$i=0;
		while(false!==($image=readdir($dir)))
		{
			if($image!='.' && $image!='..')
			{
				if($i>$rowsize)
				{
					echo '<br />';
					$i=0;
				}
				echo '<a href="images/'.$image.'"><img src="images_tn/'.$image.'" /></a>';					$i++;
			}	
		}
		closedir($dir);
	}
}

?>