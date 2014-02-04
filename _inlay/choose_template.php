<?php
require('config.inc.php');
if(isset($_POST['path'])){
  require('models/page.php');
  $page = new Page();
  $path = (string) $_POST['path'];
  $signature = md5($_SERVER['SERVER_NAME'] . ROOT_FOLDER . $path . SALT);
  if($p = $page->find_or_build_by_signature($signature)){
    $p->server = $_SERVER['SERVER_NAME'] . ROOT_FOLDER;
    $p->template = $_POST['template_path'];
    $p->path = $path;
    $p->save();
    header('Location: ../' . $path . '?edit=true');
    exit;
  }
}
$directory = new RecursiveDirectoryIterator(ROOT);
$iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);
$templates = array();
foreach ($iterator as $filename => $f) {
  if(preg_match('/\.html$/i', $filename)){
    $path = str_replace(ROOT . '/', '', $filename);
    $t = new Template($path);
    if(count($t->variables) > 0){
      $templates[$path] = $f->getFilename();
    }
  }
}
ksort($templates);
$picker = '<select name="template_path" id="template_path" size="1">';
foreach($templates as $path => $name){
  $picker .= '<option value="' . $path . '">' . $path . '</option>';
}
$picker .= '</select>';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Choose a Template</title>
  <link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/prototype/1.7/prototype.js"></script>
  <!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <style type="text/css" media="screen">
    iframe {
      -webkit-transform: scale(0.5, 0.5);
      -moz-transform:    scale(0.5, 0.5);
      -o-transform:      scale(0.5, 0.5);
      -ms-transform:     scale(0.5, 0.5);
      transform:         scale(0.5, 0.5);
      -webkit-transform-origin: top center;
      -moz-transform-origin: top center;
      -o-transform-origin: top center;
      -ms-transform-origin: top center;
      transform-origin: top center;
      border: 1px solid #ccc;
      display: inline-block;
      box-shadow: 0 2px 8px rgba(0,0,0,0.8);
      width: 1024px;
      height: 768px;
      margin: 0 -25% -25%;
    }
  </style>
</head>
<body>
<div id="PageDiv">
  <form action="choose_template.php" method="post" accept-charset="utf-8">
    <p style="text-align: center">
      <input type="hidden" name="path" value="<?php echo rawurldecode($_GET['path']) ?>" id="path"/>
      <label for="template_path">Choose a template: </label><?php echo $picker; ?>
      <input type="submit" value="Choose..."/>
    </p>
    <p style="text-align: center; position: relative; overflow: hidden; padding: 1em"><iframe id="preview" src="about:blank" frameborder="0" seamless></iframe></p>
  </form>
</div>
<script type="text/javascript">
  var p = $('preview');
  p.src = '<?php echo ROOT_FOLDER; ?>/' + $F('template_path') + '?preview=true';
  $('template_path').observe('change', function(evt){
    p.src = '<?php echo ROOT_FOLDER; ?>/' + $F(this) + '?preview=true';
  });
</script>
</body>
</html>