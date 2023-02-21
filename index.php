<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="style.css"/>
    <title>App ตั้งกระทู้ของฉัน</title>
</head>
<body>
    <?php
    // assign default error message
    $titleErr = $contentErr = "";

    // assign default valuable
    $title = $content = "";

    // check if server send data in method post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // assign value in model
        
        $content = $_POST['content'];

        // validate topic
        if(strlen($_POST['title']) < 4 or strlen($_POST['title']) > 140) {
            $titleErr = "หัวข้อควรมีข้อความใส่ 4-140 ตัวอักษร";
            $title = input_text($_POST['title']);
        } else if(check($_POST['title'])) {
            $titleErr = "หัวข้อควรไม่มีรูปแบบ html อยู่";
            $title = input_text($_POST['title']);
        } else {
            $title = input_text($_POST['title']);
        }
        
        // validate content
        if(strlen($_POST['content']) < 6 or strlen($_POST['content']) > 2000){
            $contentErr = "หัวข้อควรมีข้อความใส่ 6-2000 ตัวอักษร";
            $content = input_text($_POST['content']);
        } else {
            $content = input_text($_POST['content']);
        }

        if ($contentErr == "" && $titleErr == ""){
            // keep in php object
            $topic = (object)[];
            $topic->title = $title;
            $topic->content = $content;

            // find json list in topics.json
            $myFile = "topics.json";
            $topicList = json_decode(file_get_contents($myFile));

            // add new topic into array
            $result = array_push($topicList,$topic);

            // and write in topics.json
            $fh = fopen($myFile, 'w') or die("can't open file");
            $stringData = json_encode($topicList, JSON_UNESCAPED_UNICODE); // utf-8 format
            fwrite($fh, $stringData);
            fclose($fh);
        }
    }

    function input_text($str) {
        $str = trim($str);
        $str = stripslashes($str);
        return $str;
    }

    function check($str) {
        $start =strpos($str, '<');
        $end  =strrpos($str, '>',$start);
      
        $len=strlen($str);
      
        if ($end !== false) {
          $str = substr($str, $start);
        } else {
          $str = substr($str, $start, $len-$start);
        }
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        $xml = simplexml_load_string($str);
        return count(libxml_get_errors())==0;
      }
    ?>

    <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
        <h1>กระทู้ของฉัน</h1>
        <div>
            <label for="title">หัวข้อกระทู้ : </label>
            <input type="text" placeholder="หัวข้อกระทู้" id="title" name="title" value="<?php echo $title;?>"> (<?php echo strlen($title) == '' ? 0 : strlen($title); ?>/140)
            <span class="error"> <?php echo $titleErr ?> *</span> <br> <br>
            
            <label for="content">เนื้อหากระทู้ : </label> <br> <br>
            <textarea rows="5" cols="40" maxlength="40" placeholder="เนื้อหากระทู้ ..." id="content" name="content"><?php echo $content;?></textarea> (<?php echo strlen($content) == '' ? 0 : strlen($content); ?>/2000)
            <span class="error"><?php echo $contentErr; ?>*</span> <br> <br>
            <input type="submit">
        </div>
    </form>

    <hr>
    <h1>Show in my list have <span id="topicCount"></span> topics</h1> 
    <ul id="list"></ul>
    <script src="output.js"></script>
</body>
</html>