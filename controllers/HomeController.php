<?php
use UsersTablosuOlustur as tablocu;
## app\start\global.php altına class dizini tanımlandı

## migration ile komut satırında oluşturulan otomatik tablo class ı 
#### vendor\composer\autoload_classmap.php altına ekleniyor. 1938. satır

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/
	public $user='';
	public $bolum='';
	public $mail='';
	public $entrance=false;
	private $ldaphost = "2.2.2.2";
	private $ldapport = 389;
	
	function __construct() {
		$value = Cookie::get('member');
		
		if (strlen($value)>3){ ## cookie içi doluysa
			$bol=explode('|',$value);
			$this->user=$bol[0];
			$this->bolum=$bol[1];
			@$this->mail=$bol[2];
			$this->entrance=true;
		}
	}

	public function index(){ /// anasayfaya gidelim
		## $value = Cookie::get('member');
		return View::make('anasayfa', array('user' => $this->user, 'bolum' => $this->bolum, 'mail' =>$this->mail));
		## return View::make('anasayfa')->with('user', $value);
	}
	
	private function ldapCheck($user, $pass){ /// ldap üzerinde gelen kullanıcının kullanıcı adı şifresine göre oturum kontrolü yapar
		
		$ldap = ldap_connect($this->ldaphost, $this->ldapport);

		ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    	ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
		
		$ldapbind = @ldap_bind($ldap, $user, $pass); ##ldap_bind($ldap, $user, $pass);

		return $ldapbind;
	}

	public function ldap(){ /// gelen mail adresinin asıl bilgilerine ldap server üzerinden erişir
		
		$postData=Input::all();
		$user=$postData['email'];
		$pass=$postData['pass'];

		$hata='';

		$ldap = ldap_connect($this->ldaphost, $this->ldapport);
		#or die("Could not connect to $this->ldaphost");

		ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    	ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

		if ($ldap){
		    $username = "user";
		    $upasswd = "pass";

		    $ldapbind = @ldap_bind($ldap, $username, $upasswd);
		                               
		    if ($ldapbind) 
		        {$hata='Bağlantı Başarılı';}
		    else 
		        {$hata='Bağlantı Başarısız';}
		}

		$attributes = array('mail','name','memberof');

		## $filter="(|(sn=$person*)(givenname=$person*))";
		$filter="(|(mail=$user*))";
		$search = ldap_search($ldap, 'DC=XXX,DC=corp', $filter, $attributes);
		//var_dump($search);
		$data = ldap_get_entries($ldap, $search);
		//var_dump($data);
		
		$topAdet = $data['count'];

		if ($topAdet>0){ ## kayıt var bulundu
			ldap_close($ldap); ## ilk bağlantı kapatılır.

			$memberof = $data[0]['memberof'][0];
			$memberof = explode(",",$memberof);
			$memberof = substr($memberof[0], 3);

			$userMailAdress=$user;
			$user = $data[0]['name'][0];

			if ($this->ldapCheck($user,$pass)){ ## şifre kontrolü sağlanır.
			
			$hata='999';
			Cookie::queue(Cookie::forever('member', $user.'|'.$memberof.'|'.$userMailAdress));
			return Response::json(array('kim'=>$user, 'member'=>$memberof, 'adet'=>$topAdet, 'hata'=>$hata));
					
			}else{
				$hata='Şifre Hatalı';
			}
			
		}else{
			$hata='Kullanıcı Adı Hatalı';
		}
		
		return Response::json(array('kim'=>'noname', 'member'=>'999', 'adet'=>$topAdet, 'hata'=>$hata));
					
	}

	public function unsetCookie(){ /// cookie yi unutur.
		
		$cookie = Cookie::forget('member');

		return Redirect::route('index')->withCookie($cookie);
	}


	/*
	private function calculate_time_span($date){ /// video izleme süresince geçen zaman farkını hesaplar /// kullanılmıyor.
    	$seconds  = strtotime(date('Y-m-d H:i:s')) - strtotime($date);

        $months = floor($seconds / (3600*24*30));
        $day = floor($seconds / (3600*24));
        $hours = floor($seconds / 3600);
        $mins = floor(($seconds - ($hours*3600)) / 60);
        $secs = floor($seconds % 60);

        if($seconds < 60)
            $time = $secs." sn";
        else if($seconds < 60*60 )
            $time = $mins." dk";
        else if($seconds < 24*60*60)
            $time = $hours." sa";
        else if($seconds < 24*60*60)
            $time = $day." gun";
        else
            $time = $months." month ago";

        return $time;
	}
	*/


	/*

	public function loginForm(){
		# var_dump(Hash::make('123456'));
		return View::make('login');
	}

	public function login(){
		$postData=Input::all();
		# BÖYLE BİR ÜYE OLUP OLMADIĞINI KONTROL EDELİM
        if (Auth::attempt(array('email' => $postData['username'], 'password' => $postData['password']))) {
            
            // OTURUM AÇILDIĞINA GÖRE KULLANICIYI YÖNLENDİRELİM
            return Redirect::route('deneme');
            
        } else {
            
            # GİRİLEN BİLGİLER HATALI MESAJINI VERELİM
            return Redirect::route('loginForm')
                ->withInput()
                ->withErrors(array('Girdiğiniz mail adresi veya şifre hatalı!'));
            
        }
	}

	public function cikis(){
		Auth::logout();
		return Redirect::Route('loginForm');
	}

	public function deneme(){
		
		#$arrayName = array('uye' => 'osman', 'sifre'=> '12345676');
		#var_dump($arrayName);
		#dd($arrayName);
		
		
		
		$sonuc=DB::select('select * from users');
		# var_dump($sonuc);
		
		$users = DB::table('users')->get();

		foreach ($users as $user)
		{
		    $ad[]=$user->fullname;
		}

		return View::make('home', array('name'=>$ad));
		
		
		####
		## $tablo = new tablocu(); ## yukarıda class  tanımı yapıldı
		## $tablo->up(); ## tablo oluşturma function ı
		####
	}

	*/

}
