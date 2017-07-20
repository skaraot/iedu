<?php
// app/controllers/RecordController.php
// php artisan controller:make RecordController

class RecordController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function updatedoc($gelenId=null){
		//
		if ($gelenId){
		# var_dump($gelenId);
		$dokuman=DB::table('dokuman')
					->where('id','=',$gelenId)
					->get();
		# var_dump($gelenData);			
	
		# $kategori=DB::table('kategori')->get();	

		return View::make("update/update", array('data' => $dokuman));			
		}else{
			return View::make("update/update", array('data' => null));	
		}
	}

	public function updatesave(){
		$postData = Input::all();
		# var_dump($postData);
		$tarih = date('Y-m-d');
		$nrev = $postData['rev']+1;
		DB::table('dokuman')
			->where ('id','=',$postData['did'])
			->update (array('baslik' => $postData['baslik'], 'tag' => $postData['tag'], 'aciklama' => $postData['acikla'], 'durum' => $postData['durum'], 'tguncel' => $tarih, 'revno' => $nrev, 'offer' => $postData['offer']));
		
		return Redirect::route('updatedoc', array('gelenId' => $postData['did']));	
	}

	public function docsearch(){
		$postData = Input::all();
		return Redirect::route('updatedoc', array('gelenId' => $postData['dgid']));
	}

	
}
