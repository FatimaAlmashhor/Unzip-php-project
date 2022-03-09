<?php 
 if(isset($_FILES['file'])){
     $type = $_FILES['file']['type'] ;
     $name = $_FILES['file']['name'] ;
     $source= $_FILES['file']['tmp_name'] ;

     $images = '/images' ;
     $vedios = '/vedios' ;
     $audios = '/audios' ;

     $upload = 'uploads/' ;
    
    //  splate
     $name_part= explode("." , $_FILES['file']['name']);
     $ext =end( $name_part);
     $accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed' , ' application/octet-stream');
     foreach($accepted_types as $mime ){
         if($mime == $type)
             $check = true ;
         break;
     }

    $new_file_name = time() ;
    $targetzip = $upload. $new_file_name .".".$ext; // target zip file
    // print_r($targetzip);
    if(move_uploaded_file($source, $targetzip)) {
        if($ext == 'zip'){
            $zip = new ZipArchive;
            $x = $zip->open($targetzip);  // open the zip file to extract
            if ($x !== true)
            {
                switch($x) {
                    case ZipArchive::ER_EXISTS: 
                        echo 'File already exists.';
                        break;
                    case ZipArchive::ER_INCONS: 
                        echo 'Zip archive inconsistent.';
                        break;
                    case ZipArchive::ER_INVAL: 
                        echo 'Invalid argument.';
                        break;
                    case ZipArchive::ER_MEMORY: 
                        echo 'Malloc failure.';
                        break;
                    case ZipArchive::ER_NOENT: 
                        echo 'No such file.';
                        break;
                    case ZipArchive::ER_NOZIP: 
                        // $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        echo 'Not a zip archive but a ' ;// . finfo_file($finfo, $zip);
                        break;
                    case ZipArchive::ER_OPEN: 
                        echo 'Can\'t open file.';
                        break;
                    case ZipArchive::ER_READ: 
                        echo 'Read error.';
                        break;
                    case ZipArchive::ER_SEEK: 
                        echo 'Seek error.';
                        break;
                }
            }
            else
            {
                $foldername = $upload . '/'. $new_file_name ;
                mkdir($foldername) ;

                $test = $zip->extractTo($foldername); // place in the directory with same name  
                $zip->close();
                $a = scandir($foldername);
                foreach($a as $file){
                   
                    if($file =='.' || $file =='..'){
                        continue ;
                    }
                    else{
                        $name_part= explode("." ,$file);
                        $ext =end($name_part);
                        if($ext =='mp4'){
                            ?>
                                 <video width="320" height="240" controls>
                                    <source src="
                                        <?php 
                                            echo ($foldername .'/'. $file);
                                        ?>        
                                    "
                            
                                    type="video/mp4">
                                    Your browser does not support the video tag.
                                </video> 
                            <?php
                        }
                        if($ext =='jpg' || $ext =='png' || $ext =='jepg' || $ext =='jfif'){
                    
                            ?>
                            <img width="320" height="240"
                               src="
                                   <?php 
                                       echo ($foldername .'/'. $file);
                                   ?>        
                               "
                           /> 
                       <?php
                        }
                        if($ext =='mp3' || $ext =='m4a'){
                            ?>
                             <audio controls>
                             <source src="
                                   <?php 
                                       echo ($foldername .'/'. $file);
                                   ?>        
                               "
                             type="audio/mp3"></source>
                                </audio> 
                               <?php
                        }
                        if($ext =='pdf' || $ext =='docx' ||$ext =='doc'){
                            ?>
                            <a width="320" height="240"
                               href="
                                   <?php 
                                       echo ($foldername .'/'. $file);
                                   ?>        
                               "
                           >
                        <?php 
                        echo $file;
                        ?>
                        </a> 
                       <?php
                        }
                       

                    }
                }
               
                unlink($targetzip);
            } 
        }
        else {
            
           $message = '';
        }
    }
 }

?>

<form action="index.php" method='post' enctype="multipart/form-data" method="post">
    <input type='file' name='file' />
    <button type='submit'>Upload</button>
</form>