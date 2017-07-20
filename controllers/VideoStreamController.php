<?php

use \GetId3\GetId3Core as GetId3;

class VideoStreamController extends HomeController{ 
	
	#HomeController dan extend ederek kullanıcı giriş değişkeni kullanılır.
	
	private function tags($ktg=null, $durum=1, $nedir=null, $nerede, $bolum){ /// ilgili aramalarda tagleri toparlar json verir
		
		/*** Bolum Filitreleme*/	
		$ekSql=$bolum==0 ? "bolum<>99" : "bolum={$bolum}";
		/*** Bolum Filitreleme*/

		if ($ktg!=null) {
			$sonuc=DB::table('dokuman')
			->select('tag')		
			->where('ktg','=',$ktg)
			->where ('durum','=',$durum)
			->whereRaw($ekSql) 
			->get();
		}else{ ## kategori seçilmemişse
			$aramaAlani=$nerede==1 ? 'tag' : 'baslik';
			$sonuc=DB::table('dokuman')
			->select('tag')		
			->where ('durum','=',$durum)
			->where($aramaAlani,'LIKE', '%'.$nedir.'%')
			->whereRaw($ekSql) 
			->get();
		}

		/*son 10 tag alanı*/
		$cookieName='tag';
		$value = Cookie::get($cookieName);
		$guncel = $nedir.','.$value;

		$parcala=explode(",",$guncel,12);
		## var_dump($parcala);

		$parcala = array_unique($parcala);
		$parcala = array_values($parcala);
		
		if (count($parcala)>=9){
		$yenisi='';
		for($t=0;$t<=8;$t++){
			$yenisi.=$parcala[$t].',';
		}
		## var_dump($yenisi);
		$guncel=$yenisi;
		}else{
			$guncel = implode (", ", $parcala);
		}

		Cookie::queue(Cookie::forever($cookieName, $guncel));//, 60*24*30
		/*Son 10 tag alanı*/

		$tags=array();
		foreach ($sonuc as $key => $datax) {
			$bol=explode(',', $datax->tag);
			$tags=array_merge($tags, $bol);	
		}
		$finish = array_unique($tags);
		return array_values($finish);
	}

	private function araSorgula($nedir, $nerede, $bolum, $baslangic=0, $bitis=48){ /// arama formu ile gelen aramaları yapar
		/*
		// $ekSql=$bolum==0 ? "bolum<>99" : "bolum={$bolum}"; //->whereRaw($ekSql) 
		// bolum filitrelemesi iptal efektif sonuç vermiyor.
		*/
		

		//$gelen=DB::select("select * from dokuman where tag like '%".$nedir."%'");
		
		$aramaAlani=$nerede==1 ? 'tag' : 'baslik';
		## 1 gelirse taglara göre arama yapılır.
		## 0 gelirse baslikda metinsel arama yapılır.

		$nedir = str_replace(' ', '%', rtrim($nedir)); ## boşluklar % olarak değişir.

		$gelen=DB::table('dokuman')
			->select('baslik','gorsel','id','bolum','count', DB::raw("(select count(yorum.iid)as ycount from yorum where yorum.iid=dokuman.id)as ycount"))
			->where($aramaAlani, 'LIKE', '%'.$nedir.'%')
			->where('durum','=',1)
			->orderBy('count', 'desc')
			->limit($bitis)
			->skip($baslangic)
			->get();
		
		$gelenDataAdet=DB::table('dokuman')
			->where($aramaAlani, 'LIKE', '%'.$nedir.'%')
			->where('durum','=',1)
			->count('id');	

		$asilVideoSure = $this->videoSureHesapla($gelen);		

		#var_dump(DB::getQueryLog());
		
		$tagCloud=$this->tags(null, 1, $nedir, $nerede, 0); // etiketler gelir // bolum alanı için sıfır gider
		$topTag=count($tagCloud);

		$sonTag=explode(',', Cookie::get('tag'));

		$arrayName = array('sonuc' => $gelen, 'sonucTane'=>$gelenDataAdet, 'vsure'=>$asilVideoSure, 'tagTane'=>$topTag, 'tag' =>$tagCloud, 'sonTags'=>$sonTag);
		return  Response::Json($arrayName);
	}

	private function detayVer($id){ /// arama sonucu istenen detayı ekrana basar
		$gelen='';
		$videoSonuGelen='';
		$devamVideo='';
		$secretURL = '';

		if ($this->entrance==true){ ### üye oturumu yoksa başlangıç log atmasın | sayaç artmasın | veri çekmesin
			$gelen=DB::table('dokuman')
				->where('id','=',$id)
				->get();

			$secretURL = "http://www.intercityegitim.com/share/".$id;	

			$SecilenKategori = $gelen[0]->bolum;
			$secilenVideoDevam = $gelen[0]->devam;	

			DB::table('dokuman') # izleme sayacı bir artar
				->where('id','=',$id)
				->increment('count');

			if ($secilenVideoDevam>0){ # devam videosu varsa bunu bir öneri olarak sunarız
				$devamVideo=DB::table('dokuman')
					->select ('id','baslik','gorsel')
					->where ('devam','=',$secilenVideoDevam)
					->where ('durum','=',1)
					->take(6)
					->get();
			}

			### select id,baslik,gorsel from dokuman where dokuman.bolum=2 and durum=1 order by RAND() limit 6;	
			$videoSonuGelen=DB::table('dokuman') ##benzer kategoriden 6 tane rastgele video getir
				->select ('id','baslik','gorsel')
				->where ('bolum','=',$SecilenKategori)
				->where ('durum','=',1)
				->where ('devam','==',0)
				->orderBy(DB::raw('RAND()'))
				->take(6)
				->get();

			
			$ip=$_SERVER['REMOTE_ADDR']; # izleme süreleri takip edilir.
			$date=date('Y-m-d H:i:s');			
			DB::table('viewlog')->insert(
			    array('iid' => $id, 'itime' => $date, 'ip' => $ip, 'user' => $this->user, 'bolum' => $this->bolum)
			);
		}

		return Response::Json(array('data' => $gelen, 'devammi' => $devamVideo, 'sonraGoster' => $videoSonuGelen, 'login' => $this->entrance, 'share' => $secretURL));
	}

	private function videoSureHesapla($kayitDizi){
		$kaynak = array();
		
		foreach ($kayitDizi as $video) ### video süreleri alınır.
		{
		    $source = $video->gorsel;
		    $source = str_replace('.jpg', '.mp4', $source);
				
			$getID3 = new getID3;
			$fileinfo = $getID3->analyze("video/".$source);
			# var_dump($fileinfo);
	        $second = round($fileinfo['playtime_seconds']);

	        $m = floor($second / 60) < 10 ? '0' . floor($second / 60) : floor($second / 60);
			$s = floor($second - ($m * 60)) < 10 ? '0' . floor($second - ($m * 60)) : floor($second - ($m * 60));
			
			$kaynak[] = $m.":".$s;
		}
		return $kaynak;
	}

	private function ilkEkran($ktg=2, $bolum, $baslangic=0, $bitis=48){ /// ilk açılış ekranını doldurur.
		$ekSql=$bolum==0 ? "bolum<>99" : "bolum={$bolum} order by count desc";
		
		$gelen=DB::table('dokuman')
			->select('baslik','gorsel','id','bolum','count', DB::raw("(select count(yorum.iid)as ycount from yorum where yorum.iid=dokuman.id)as ycount"))
			->where('ktg','=',$ktg)
			->where('durum','=',1)
			->whereRaw($ekSql)
			->limit($bitis)
			->skip($baslangic)
			->get();

		$asilVideoSure = $this->videoSureHesapla($gelen);	
		
		$gelenDataAdet=DB::table('dokuman')
			->where('ktg','=',$ktg)
			->where('durum','=',1)
			->whereRaw($ekSql)
			->count('id');	
			
		#var_dump(DB::getQueryLog()); 

		$oneri=DB::table('dokuman')
				->select('baslik','gorsel','id','bolum','count', DB::raw("(select count(yorum.iid)as ycount from yorum where yorum.iid=dokuman.id)as ycount"))
				->where('offer','=','1')
				->where('durum','=',1)
				->whereRaw($ekSql)
				->get();

		$oneriDataAdet=count($oneri);		

		$oneriVideoSure=array();

		if ($oneriDataAdet>=1) $oneriVideoSure = $this->videoSureHesapla($oneri);	
		
		$tagCloud=$this->tags($ktg, 1, null, null, $bolum); // etiketler gelir // bolum detayı devreye girer
		$topTag=count($tagCloud);

		$sonTag=explode(',', Cookie::get('tag'));
		
		$arrayName = array('sonuc' => $gelen, 'sonucTane'=>$gelenDataAdet, 'vsure'=>$asilVideoSure, 'oneri' => $oneri, 'oneriTane' => $oneriDataAdet, 'vsureOneri' => $oneriVideoSure, 'tagTane'=>$topTag, 'tag' =>$tagCloud, 'sonTags'=>$sonTag);	
		return Response::Json($arrayName);	
		
	}

	private function bolumVideoAdet(){
		$gelen = DB::table('dokuman')
			->select ('bolum', DB::raw('count(id) as tane'))
			->where ('durum','=',1)
			->groupBy ('bolum')
			->orderBy ('bolum','asc')
			->get();
		
		$arrayName = array('sonuc'=>$gelen);
		return 	Response::Json($arrayName);
	}

	private function yorumFormKaydet($yorum, $id){ /// gelen yorumları kaydeder
		$ip=$_SERVER['REMOTE_ADDR'];
		$date=date('Y-m-d H:i:s');
		DB::table('yorum')->insert(
		    array('iid' => $id, 'yorum' => $yorum, 'tarih' => $date, 'ip' => $ip, 'user' => $this->user, 'bolum' => $this->bolum)
		);
	//var_dump(DB::getQueryLog()); 		
	//return true;	
	}

	private function yorumEskiler($id){ /// mevcut yorumları listeler
		$gelen=DB::table('yorum')
			->where ('iid','=',$id)
			->where ('durum','=',1)
			->get();
		$adet=count($gelen);	
		return Response::Json(array('data'=>$gelen,'tane'=>$adet, 'kimki' => $this->user));
	}

	private function yorumSil($id){ /// kullanıcı oturum sahibi, kendi yorumlarını siler. 
		DB::table('yorum')
			->where('id','=',$id)
			->where('user','=',$this->user)
			->delete();
	}
	
	private function viewLogUpdate($iid,$vname){ /// modal kapanırken buna gelir geçen süre hesaplanıp yazılır
		$ip=$_SERVER['REMOTE_ADDR']; # izleme süreleri takip edilir // kapatma satıra eklenir.
		$bitis=date('Y-m-d H:i:s');

		$getID3 = new getID3;
		$fileinfo = $getID3->analyze("video/".$vname);
		# var_dump($fileinfo);
        $second = round($fileinfo['playtime_seconds']);

        $m = floor($second / 60) < 10 ? '0' . floor($second / 60) : floor($second / 60);
		$s = floor($second - ($m * 60)) < 10 ? '0' . floor($second - ($m * 60)) : floor($second - ($m * 60));
		
		$dura = $m.":".$s;


		$gelen=DB::table('viewlog')
			->select('itime','id')
			->where ('diff','=',0)
			->where ('ip','=',$ip)
			->where ('iid','=',$iid)
			->where ('user','=',$this->user)
			->orderBy ('id','desc')
			->get();
		
		$baslangic=$gelen[0]->itime;	
		$satirID=$gelen[0]->id;


		#$fark=$this->calculate_time_span($baslangic);
		#var_dump($fark);

		$datetime1 = new DateTime($baslangic);
		$datetime2 = new DateTime($bitis);
		$interval = $datetime1->diff($datetime2);
		
		$aradakiFark=0;
		$aradakiFarkDk='00';
		$aradakiFarkSn='00';

		/*if ($interval->h >= 1 ){
			$aradakiFark=$interval->h.' sa';
		} else 
		*/
		
		$aradakiFarkDk = $interval->i<10 ? '0'.$interval->i : $interval->i;
		$aradakiFarkSn = $interval->s<10 ? '0'.$interval->s : $interval->s;
		

		$aradakiFark=$aradakiFarkDk.':'.$aradakiFarkSn;
		
		DB::table('viewlog')
            ->where ('id','=', $satirID)
            ->where ('ip','=', $ip)
            ->where ('diff','=',0)
            ->where ('user','=',$this->user)
            ->update(array('stime' => $bitis, 'diff' => $aradakiFark, 'duration' => $dura));
        
        ## var_dump(DB::getQueryLog());         
	}

	public function build(){ /// genel yönlendirici gelen talepleri ilgili fonksiyonlara atar
		$postData=Input::all();
		$islem=$postData['islem'];
		switch($islem){
			case 'detay':
				return $this->detayVer($postData['id']);
			break;
			case 'search':
				return $this->araSorgula($postData['kelime'], $postData['nerede'], $postData['bolum']);
			break;
			case 'ilkEkran':
				return $this->ilkEkran(2, 0);
			break;
			case 'pageing':
				if (strlen($postData['kelime'])>=2){ ## arama sorgulama için sayfala
					return $this->araSorgula($postData['kelime'], $postData['nerede'], $postData['bolum'], $postData['baslangic']);
				}else{ ## kategori ve ilk açılış için sayfala
					return $this->ilkEkran(2, $postData['bolum'], $postData['baslangic']);	
				}				
			break;
			case 'kategori':
				return $this->ilkEkran($postData['yon'], $postData['bolum']);
			break;
			case 'adetSay':
				return $this->bolumVideoAdet();
			break;
			case 'yorum':
				return $this->yorumFormKaydet($postData['yorum'], $postData['id']);
			break;
			case 'yorumGetir':
				return $this->yorumEskiler($postData['id']);
			break;
			case 'yorumSil':
				return $this->yorumSil($postData['id']);
			break;
			case 'log':
				return $this->viewLogUpdate($postData['iid'], $postData['vname']);
			break;
		}		
	}
}