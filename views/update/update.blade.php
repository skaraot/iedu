@extends('update/layout')
@section('content')
<div class='well clearfix  mesafe10'>

{{Form::open(array('class' => 'form-horizantal', 'action' => 'RecordController@docsearch'))}}
	<div class="col-md-3" style='padding-top:5px;'>
		<label class='control-label'>Düzenlenecek Dokuman ID si</label>
	</div>
	<div class="col-md-8">
    <div class="input-group">
      {{Form::text('dgid', Input::old('dgid'), array('class' => 'form-control', 'placeholder' => 'ID'))}}
      <span class="input-group-btn">
        {{Form::submit("Ara", array('class' => 'btn btn-success pull-right'))}}
      </span>
    </div><!-- /input-group -->
  </div><!-- /.col-lg-6 -->
{{Form::close()}}
</div>

@if ($data)
<div class='well clearfix  mesafe10'>
{{Form::open(array('class' => 'form-horizontal', 'action' => 'RecordController@updatesave'))}}
	
	<div class="form-group">
    	<label class="col-sm-2 col-sm-offset-1 control-label"></label>
    	<div class="col-sm-8">
		<div class="btn-group btn-group-sm" role="group" aria-label="{{$data[0]->id}}"># <strong>{{$data[0]->id}}</strong></div>
		</div>
	</div>

	<div class="form-group">
    	<label class="col-sm-2 col-sm-offset-1 control-label">Başlık</label>
    	<div class="col-sm-8">
		{{Form::text('baslik', $data[0]->baslik, array('class' => 'form-control', 'id' => 'baslik'))}}
		</div>
	</div>
	
	<div class="form-group">
    	<label class="col-sm-2 col-sm-offset-1 control-label">Etiket</label>
    	<div class="col-sm-8">
		{{Form::text('tag', $data[0]->tag, array('class' => 'form-control', 'id' => 'tag'))}}
		</div>
	</div>

	<div class="form-group">
    	<label class="col-sm-2 col-sm-offset-1 control-label">Açıklama</label>
    	<div class="col-sm-8">
		{{Form::textarea('acikla', $data[0]->aciklama, array('class' => 'form-control', 'id' => 'acikla', 'style' => 'height:320px;'))}}
		</div>
	</div>

	<div class="form-group">
    	<label class="col-sm-2 col-sm-offset-1 control-label">Durum</label>
    	<div class="col-sm-8">
		{{Form::select('durum', array('' => 'Seçiniz', '0' => 'Pasif', '1' => 'Aktif'), $data[0]->durum, array('class' => 'form-control', 'id' => 'durum'))}}
		</div>
	</div>

	<div class="form-group">
    	<label class="col-sm-2 col-sm-offset-1 control-label">Tavsiye Seçimi</label>
    	<div class="col-sm-8">
		{{Form::select('offer', array('' => 'Seçiniz', '0' => 'Tavsiye Bekliyor', '1' => 'Tavsiye Edilen Video'), $data[0]->offer, array('class' => 'form-control', 'id' => 'offer'))}}
		</div>
	</div>

	<div class="form-group">
    	<label class="col-sm-2 col-sm-offset-1 control-label">Oluşma Tarihi</label>
    	<div class="col-sm-8">
		{{$data[0]->tolusma}}
		</div>
	</div>

	<div class="form-group">
    	<label class="col-sm-2 col-sm-offset-1 control-label">Güncelleme Tarihi</label>
    	<div class="col-sm-8">
		{{date('Y-m-d')}}
		</div>
	</div>

	<div class="form-group">
    	<label class="col-sm-2 col-sm-offset-1 control-label">Rev. No</label>
    	<div class="col-sm-8">
		{{$data[0]->revno}} {{Form::hidden('rev',$data[0]->revno)}} {{Form::hidden('did',$data[0]->id)}}
		</div>
	</div>

	<div class="form-group">
    	<label class="col-sm-2 col-sm-offset-1 control-label">İzlenme</label>
    	<div class="col-sm-8">
		{{$data[0]->count}}
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 col-sm-offset-1 control-label"></label>
		<div class='col-sm-8'  style='padding:0px;'>
		{{Form::submit("Güncelle", array('class' => 'btn btn-success pull-right', 'id'=> 'updateDocSubmit'))}}
		</div>
	</div>
{{Form::close()}}
</div>
@endif
@stop

<!--
<div class="form-group">
    	<label class="col-sm-2 col-sm-offset-1 control-label">Talebin Atanacağı Grup</label>
    	<div class="col-sm-8">
		{{Form::select('agrup', array('' => 'Seçiniz', 'Yazılım' => 'Yazılım', 'Sistem' => 'Sistem'), null, array('class' => 'form-control', 'id' => 'agrup'))}}
		</div>
	</div>
	
	<div class="form-group">
    	<label class="col-sm-2 col-sm-offset-1 control-label">Talep Sahibi</label>
    	<div class="col-sm-4">
		{{Form::text('rname', '', array('class' => 'form-control', 'id' => 'rname', 'readonly'))}}
		</div>
    	<div class="col-sm-4">
		{{Form::text('mail', '', array('class' => 'form-control', 'id' => 'mail', 'readonly'))}}
		</div>
	</div>-->