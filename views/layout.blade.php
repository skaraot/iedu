<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<base href='http://www.intercityegitim.com/'>
<title>intercity eğitim</title>
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
{{HTML::style('cssfw/css/bootstrap.min.css')}}
{{HTML::style('cssfw/css/manual.css')}}
{{HTML::script('cssfw/js/tinymce/tinymce.min.js')}}
<script>
tinymce.init({
  selector:'#mytext',
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
<div id='tepe'></div>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <!--{{ HTML::image('img/intercity_208.png', 'Intercity Rent a Car' , array('class'=>'img-responsive')) }}-->
      <a class="navbar-brand" href="{{URL::to('/')}}"><img src={{asset('img/intercity_208.png')}} alt="Intercity Rent a Car" class='img-responsive'></a>
    </div>
    
    @if (strlen($user)>3)
    <span class='well well-sm pull-right' style='margin-top:20px;'><a href='{{URL::to('exit')}}'><i class='glyphicon glyphicon-off'></i></a></span>
    <span class='well well-sm pull-right' id='userData' style='margin-top:20px;'><i class='glyphicon glyphicon-user'></i>&nbsp;{{$user}}</span>
    <script type="text/javascript">
    var userRealName='{{$user}}';
    var userMailAddress='{{$mail}}';
    </script>
    @else
    <button type='button' class='btn btn-success pull-right' style='margin-top:24px;' id='userEntranceForm' data-toggle="modal" data-target="#myModalUser">Login</button>
    @endif
    
  </div>
</nav>
<div id='bigBanner' class='row banner fullscreen-bg'>

  <div id='ustAramaFormu' class='arama'>
  <div class="input-group">
    <span class="input-group-addon" id="sizing-addon1"><i class='glyphicon glyphicon-search'></i></span>
    <input type="text" id="araText" class="form-control ozelForm" placeholder="Arayacağınız Anahtar Kelimeleri Giriniz">
    <span class="input-group-btn" style='display:none;'>
      <button class="btn btn-default ozelForm" type="button">Ara</button>
    </span>
  </div>
  </div>

  <div class="modal fade" id="myModalUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Giriş</h4>
        </div>
        <div class="modal-body">
            <p id='durum'></p>
              {{ Form::open(array('class'=>'form-horizantal')) }}
            <div class='form-group'>  
              {{ Form::text('username', Input::old('username'), array('class'=>'form-control', 'placeholder'=>'Email adresiniz', 'required', 'id'=>'email')) }}
            </div>  
            <div class='form-group'>             
              {{ Form::password('password', array('class'=>'form-control', 'placeholder'=>'Şifreniz', 'required', 'id'=>'pass')) }}
            </div> 
              {{ Form::close() }}
            <p><small class='text-success'>Office 365 bilgileriniz ile giriş yapabilirsiniz.</small></p>  

        </div>
        <div class="modal-footer">
          <div class='col-sm-8 text-left' style='margin-top:8px;'>
            <div class="text-danger hide"><i class='glyphicon glyphicon-remove'></i>Giriş Bilgilerini Boş Geçemezsiniz.</div>
          </div>
          <div class='col-sm-4'>
            <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
            <button type="button" class="btn btn-success" id='userEntrance'>Giriş</button>
          </div>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <div class='egitimLogo'></div>

  <div class='usTabaka'></div>

  <video id="video" controls autoplay muted loop class='fullscreen-bg__video'>
    <source src="bannerVideo/karmaEgitim.mp4" type="video/mp4">
    <source src="bannerVideo/karmaEgitim.webm" type="video/webm">
  </video>

</div>   
<div class="row">
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