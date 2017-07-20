<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>intercity eğitim</title>
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
{{HTML::style('cssfw/css/bootstrap.min.css')}}
{{HTML::style('cssfw/css/manual.css')}}
{{HTML::script('cssfw/js/tinymce/tinymce.min.js')}}
<script>
tinymce.init({
  selector:'textarea',
    //plugins: "textcolor",
    fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
    toolbar: [
        //"undo redo | sizeselect | bold italic | fontsizeselect | styleselect | bold italic | link image | alignleft aligncenter alignright | forecolor backcolor"
        "bold italic | fontsizeselect | alignleft aligncenter alignright"
    ]
});
</script>
</head>
<body>
<div class="container">
  @yield('content')
</div>
<footer id="footer" class="hidden-print">
  <div class='row pull-left enalt' id='kBase'><small>Intercity © 2015 | Knowledge Base</small></div>
  <span class='pull-right asagiOkGovde'  id='gotoUP'>
      <i class='icon-up-open-mini' style='font-size:35px;'></i>
  </span>
</footer>
{{HTML::script('https://code.jquery.com/jquery-1.10.2.min.js')}}
<!--{{HTML::script('cssfw/js/jquery-1.11.3.min.js')}}-->
{{HTML::script('cssfw/js/bootstrap.min.js')}}
{{HTML::script('cssfw/js/dataConn.js')}}
{{HTML::script('cssfw/js/manual.js')}}
{{HTML::script('cssfw/js/groove.js')}}
{{HTML::script('cssfw/js/jquery.form.js')}} <!-- http://malsup.com/jquery/form/ -->
</body>
</html>