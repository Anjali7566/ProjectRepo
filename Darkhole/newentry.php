<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div id="wrapper">
        <div id="nav">
            <a href="<?php echo SITE_ADDR;?>/new_entry.php">New Entry</a>
        </div>
        <div id="logo">
            <h1><a href="<?php echo SITE_ADDR;?>">Simple search engine</a></h1>
        </div>
        </div>
        <div id="main" class="shadow-box" id="content">

        <?phpif(isset($_POST['addbtn'])){

            $title = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : "";
            $url = isset($_POST['url']) ? htmlspecialchars($_POST['url']) : "";
            $blurb = isset($_POST['blurb']) ? htmlspecialchars($_POST['blurb']) : "";
            $keywords = isset($_POST['keywords']) ? htmlspecialchars($_POST['keywords']) : "";


            if($title && $url && $blurb &&  $keywords ){
                $conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
                mysqli_query($conn,"INSERT INTO search_engine VALUES('$id', '$title', ' $url', '$blurb', ' $keywords')");

                if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM search_engine WHERE title='($title)' AND url='($url)'"))){
                    echo 'New entry added';

                    $title = '';
                    $url = '';
                    $blurb = '';
                    $keywords = '';
                }
                else 
                echo 'An error occured, No entry was added';
            }
            else{
                echo ' Please provise all data';
            }
        }
?>

                <form action="" method="POST" name="">
                <table>
                    <tr>
                        <td>Title</td>
                        <td><input type="text" name="title" value="<?php echo isset($title) ? $title : "" ; ?>" /></td>
                    </tr>
                    <tr>
                        <td>URL</td>
                        <td><input type="text" name="url" value="<?php echo isset($url) ? $url : "" ; ?>" /></td>
                    </tr>
                    <tr>
                        <td>blurb</td>
                        <td><textarea type="text" name="blurb" value="<?php echo isset($blurb) ? $blurb : "" ; ?>" /></td>
                    </tr>
                    <tr>
                        <td>keywords</td>
                        <td><textarea type="text" name="keywords" value="<?php echo isset($keywords) ? $keywords : "" ; ?>" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" name="addbtn" value="Add Entry"/></td>
                  </tr>
                </table>
                </form>
        </div>
</body>
</html>