@extends('layout')
@section('content')
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    	<div class='yorumAraKat' id='trans'></div>
    	<div class='col-md-6 col-md-offset-3 commentForm well' id='yorum'>
    		<h4><i class='glyphicon glyphicon-pencil'></i> Video Hakkında Görüşleriniz? 
    			<a href='javascript:yorumFormAcKapa(0,1);' id='frmKapat' class='pull-right'><i class='glyphicon glyphicon-remove'></i></a></h4>
    		<div class='form-group'>
    			<input type='text' id='sahip' class='form-control' value='{{$user}}' disabled>
    		</div>
    		<div class='form-group'>
    			<textarea class='form-control' id='yorum' placeholder='Video Hakkında Görüşleriniz.' maxlength='225' style='height:150px'></textarea>
    			<small>(225 Karakter)</small>
    		</div>
    		<label id='sonucMesaj' class='text-info'></label><button id='frmKaydet' class='btn btn-success pull-right'>Kaydet</button>	
    	</div>
    	<div class='col-md-10 col-md-offset-1 commentView well' id='yorumLar'>
    	</div>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"></h4>
		</div>
		<div class="modal-body" id="myModalBody"></div>
		<div class='modal-body' id='videoComment' style='padding-top:0px;'></div>
		<div class="modal-footer">
			<!--<button type='button' id='pastComment' class='btn btn-primary pull-left'>Mevcut değerlendirmeler</button>-->
			<button type='button' id='frmOpen' class='btn btn-primary pull-left'>Videoyu değerlendirin</button>
			<button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
		</div>
    </div>
  </div>
</div>
<!-- Modal -->
<div id='navige'></div>
<div id='kayarSabitMenu' class='row'>
<div class='container ortala'>
	<div id='imnLg' class='intercityLogoSmall' style='display:none;'>
		<a href='{{URL::to('/');}}'>{{HTML::image('img/intercity_mini.png', 'intercity', array('class'=>'intercityLogo'))}}</a>
	</div>
	<ul class='nav list-unstyled' id='menu'>
		<!--<a href='javascript:;' onclick='kategoriSuz(1,1);'>
			<li data-toggle="tooltip" data-placement="bottom" title="Makale">
			<i class='icon-help-circled fontelloSize anaMenu'></i>
			</li>
		</a>-->
		
		<li data-toggle="tooltip" data-placement="bottom" title="Video Eğitim" onclick="kategoriSuz(2, 1, 0);">
			<i class='icon-videocam fontelloSize anaMenu'></i>
		</li>
	
		<!--<li data-toggle="tooltip" data-placement="bottom" title="Groove Ticket" onclick="groveList();">
			<i class='icon-ticket fontelloSize anaMenu'></i>
		</li>-->
		
	</ul>

	<div id='altAramaFormu' class="input-group pull-right kucukAramaFormu">
	  <span class="input-group-addon" id="sizing-addon1"><i class='glyphicon glyphicon-search'></i></span>
	  <input type="text" id="araText2" class="form-control ozelForm" placeholder="Arayacağınız Anahtar Kelimeleri Giriniz">
	  <span class="input-group-btn" style='display:none;'>
	    <button class="btn btn-default ozelForm" type="button">Ara</button>
	  </span>
	</div>

</div>
</div>

<div class='container' id='olayYeri'>

	<div id='sonucTag'></div>
	<div id='sonucBolum'></div>
	<hr>
	<div id='sonucBaslik'></div>

	<div class="panel panel-default" id="oneriPanel">
	<div class="panel-body">
  		<div id='oneri'></div>
  	</div>	
	</div>

	<div id='sonucDokum'></div>
	
</div>

<div class='container' id='groove'>
	
	<div id='navigeTicket' style='height:60px;'><button class='btn btn-success pull-right' type='button' id='newTicketForm'><i class='icon-ticket'></i> Yeni Ticket Aç</button></div>
	
	<div id='oldTicket'>
	{{HTML::image('img/loading.gif', 'Lütfen Bekleyiniz', array('class'=>'text-center'))}}
	</div>
	
	<div id='newTicket' style='display:none;'>
		
		{{Form::open(array('class' => 'form-horizontal'))}}

		<div class="form-group">
	    	<label class="col-sm-2 col-sm-offset-1 control-label"></label>
	    	<div class="col-sm-8 text-danger" id='frmHata'>	    	
			</div>
		</div>

		<div class="form-group">
	    	<label class="col-sm-2 col-sm-offset-1 control-label">Talebin Atanacağı Grup</label>
	    	<div class="col-sm-8">
			{{Form::select('agrup', array('' => 'Seçiniz', 'Yazılım' => 'Yazılım', 'Sistem' => 'Sistem'), null, array('class' => 'form-control', 'id' => 'agrup'))}}
			</div>
		</div>
		
		<div class="form-group">
	    	<label class="col-sm-2 col-sm-offset-1 control-label">Talep Sahibi</label>
	    	<div class="col-sm-4">
			{{Form::text('rname', $user, array('class' => 'form-control', 'id' => 'rname', 'readonly'))}}
			</div>
	    	<div class="col-sm-4">
			{{Form::text('mail', $mail, array('class' => 'form-control', 'id' => 'mail', 'readonly'))}}
			</div>
		</div>

		<div class="form-group">
	    	<label class="col-sm-2 col-sm-offset-1 control-label">Başlık</label>
	    	<div class="col-sm-8">
		{{Form::text('baslik', '', array('class' => 'form-control', 'id' => 'baslik'))}}
			</div>
		</div>
				
		<div class="form-group">
	    	<label class="col-sm-2 col-sm-offset-1 control-label">Talebiniz</label>
	    	<div class="col-sm-8">
		{{Form::textarea('sorun', '', array('class' => 'form-control', 'id' => 'mytext', 'style' => 'height:320px;'))}}
			</div>
		</div>
		
		{{Form::close()}}

		{{ Form::open( array('url' => 'uploadattach', 'id' => 'filex', 'files' => true, 'class' => 'form-horizontal', 'method'=>'POST')) }}	 
		<div class="form-group">
			<label class="col-sm-2 col-sm-offset-1 control-label">Dosya Ekle</label>
		    	<div class="col-sm-8">
					<div class="input-group">
				      {{ Form::file('file', array('class' => 'form-control', 'id' => 'selectFile')) }}
				      <span class="input-group-btn">
				      	{{ Form::submit('Yukle', array('class' => 'btn btn-default', 'id' => 'tsub')) }}
					  </span>
				    </div><!-- /input-group -->	
				    <small class='text-success'>Kabul edilen uzantılar; .zip, .csv, .txt, .rar, .jpg, .png, .xls, .xlsx, .doc, .docx</small>
				<div id='upList'></div>
			</div>	    	
		</div>
		{{ Form::close() }}	

		<div class="form-group">
			<label class="col-sm-2 col-sm-offset-1 control-label"></label>
			<div class='col-sm-8'  style='padding:0px;'>
			{{HTML::image('img/loading.gif', '', array('id' => 'tiktik', 'style' => 'width:25px;'))}} {{Form::button("<i class='icon-ticket'></i> Kaydet", array('class' => 'btn btn-success pull-right', 'id'=> 'newTicketSubmit'))}}
			</div>
		</div>

	</div>

</div>
@stop