<?php
class curlGroveTicketController extends HomeController{

	private function CurlDataBas($url){
		if ($this->entrance==true){ ### üye oturumu yoksa
			//  Initiate curl
			$ch = curl_init();
			// Disable SSL verification
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			// Will return the response, if false it print the response
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			// Set the url
			curl_setopt($ch, CURLOPT_URL,$url);
			// Execute
			$result=curl_exec($ch);
			// Closing
			curl_close($ch);

			// Will dump a beauty json :3
			//echo json_decode($result, true);

			return json_decode($result, true);
		}	
	}

	public function personmessage(){
		$mailAdres=Input::get('mail');
		
		$url="https://api.groovehq.com/v1/tickets?state=unread,opened,follow_up,pending&customer={$mailAdres}&access_token={$token}";
				
		return $this->CurlDataBas($url);
	}

	public function assigneeuser(){
		$url=Input::get('url');

		$url=$url."?access_token={$token}";

		return $this->CurlDataBas($url);
	}

	public function assigneemessage(){
		$url=Input::get('url');

		$url=$url."?access_token={$token}";

		return $this->CurlDataBas($url);
	}

	public function getticket(){
		if ($this->entrance==true){ ### üye oturumu yoksa işlem yapma

			$getData=Input::all();

			$url="https://api.groovehq.com/v1/tickets";

			/**GOVDE ek Gorsel*/
			$gorsel='';
			$ekler='';
			if (count(@$getData['attach'])>0){
			$ekler='<h4>Tüm Ekler</h4>';	
			foreach ($getData['attach'] as $value) {
				$kabul=array('jpg','png');
				$extension = pathinfo('attachFile/'.$value, PATHINFO_EXTENSION);
					if (in_array(strtolower($extension), $kabul)){
					/** tmp rename*/
					$completeFile=substr($value, 5);
					rename("attachFile/".$value, "attachFile/".$completeFile);
					/** tmp rename*/	
					$gorsel.="<p><img src='http://www.intercityegitim.com/attachFile/{$completeFile}' alt='ek'><p>";
					}
				$ekler.="<p><img src='http://www.intercityegitim.com/img/attach.png'> <a href='http://www.intercityegitim.com/attachFile/{$completeFile}'>{$completeFile}</a></p>";		
				}		
			}
			
			$talep=$getData['talep']."<hr>".$gorsel.$ekler;
			/**Govde ek Gorsel*/

			$fields=array(
				'assigned_group' => $getData['grup'],
				'from' => array(
					'email' => $getData['mail'],
					'name' => $getData['adSoy'],
					'company_name' => 'Intercity'
					),
				'subject' => $getData['baslik'],
				'body' => $talep,
				'to' => $getData['mail'],
				'send_copy_to_customer' => true,
				'access_token' => ''
				);

				# var_dump($fields);
			
				/*
					$postData = '';
					//create name value pairs seperated by &
					foreach($fields as $k => $v) 
					{ 
					  $postData .= $k . '='.$v.'&'; 
					}
					rtrim($postData, '&');

					# echo $postData;
				*/
				$postData=http_build_query($fields);

				$ch = curl_init();  

				curl_setopt($ch,CURLOPT_URL,$url);
				curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
				curl_setopt($ch,CURLOPT_HEADER, false); 
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    

				$output=curl_exec($ch);
				curl_close($ch);

				

				return $output;
		}
	}

	public function uploadattach(){
		$kabul=array('zip','csv','txt','rar','jpg','png','xls','xlsx','doc','docx');
		
		$fileUp=Input::file('file');
		# var_dump($fileUp);

		if(Input::file('file')->getFilename()){

			$extension = pathinfo(Input::file('file')->getClientOriginalName(), PATHINFO_EXTENSION);

			if(!in_array(strtolower($extension), $kabul)){
				return '{"status":"error", "detail":"Not Allowed", "orjinName":"--","name":"--"}';
			}

			$ekString=str_random(5);
			$newName="[tmp]".Date('dhsy').$ekString.'.'.$extension;
			## Input::file('file')->getClientOriginalName()

			Input::file('file')->move('attachFile/', $newName);
			return '{"status":"success", "detail":"Upload Ok", "orjinName":"'.Input::file('file')->getClientOriginalName().'", "name":"'.$newName.'"}';
			
			/*
			if(Image::make(Input::file('file'))->save('attachFile/'.Input::file('file')->getClientOriginalName())){ /// image upload 				
				return '{"status":"success","detail":"Upload Ok"}';
			}
			*/
		}else{
		return '{"status":"error", "detail":"Please Retry", "orjinName":"--","name":"--"}';
		}		
	}

	public function delattach(){
		$getData=Input::all();
		File::delete('attachFile/'.$getData['filex']);
		return '{"status":"ok"}';
	}
}