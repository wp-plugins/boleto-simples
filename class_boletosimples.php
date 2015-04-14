<?php 

class boletoSimplesAPI {

	public $environments = array(
		'production' => 'https://boletosimples.com.br/api/v1/',
		'sandbox' => 'https://sandbox.boletosimples.com.br/api/v1/'
		);

	public $environment = NULL;
	public $token = NULL;
	public $key = NULL;

	public function boletoSimplesAPI()
	{
		$this->environment = get_option("boletosimples_environment");
		$this->token = get_option("boletosimples_token");
		$this->key = get_option("boletosimples_key");
		$this->email = get_option("boletosimples_email");
	}

	public function bills()
	{
		$exec = curl_init($this->environments[$this->environment].'bank_billets');		
		curl_setopt( $exec, CURLOPT_USERPWD, $this->token.':'.$this->key ); # -u
		curl_setopt( $exec, CURLOPT_CUSTOMREQUEST, 'GET' ); # -X
		curl_setopt( $exec, CURLOPT_RETURNTRANSFER, true ); #
		curl_setopt( $exec, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'User-Agent: Boleto Simples WP Plugin ('.$this->email.')') ); # -H
		curl_setopt ($exec, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($exec, CURLOPT_SSL_VERIFYPEER, 0); 

		$content = curl_exec($exec);
		curl_close($exec);

		if(!$content)
		{
			$content = json_encode(array());
		}
		

		return json_decode($content, true);

	}

	public function generate()
	{

		if(isset($_POST['boletosimples']['bank_billet']['id']))
		{
			return $this->print_bill($_POST['boletosimples']['bank_billet']['id']);
		}

		$_POST['boletosimples']['bank_billet']['expire_at'] = date('d/m/Y', strtotime("+".get_option("boletosimples_days")." days"));
		$_POST['boletosimples']['bank_billet']['description'] = get_option("boletosimples_description");

		if($_POST['boletosimples']['bank_billet']['amount'] == 'more')
		{
			$_POST['boletosimples']['bank_billet']['amount'] = $_POST['boletosimples']['bank_billet']['custom_value'];
		}

		$exec = curl_init($this->environments[$this->environment].'bank_billets');
		curl_setopt( $exec, CURLOPT_USERPWD, $this->token.':'.$this->key ); # -u
		curl_setopt( $exec, CURLOPT_CUSTOMREQUEST, 'POST' ); # -X
		curl_setopt( $exec, CURLOPT_RETURNTRANSFER, true ); #
		curl_setopt( $exec, CURLOPT_POSTFIELDS, json_encode($_POST['boletosimples'])); # -d
		curl_setopt( $exec, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'User-Agent: Boleto Simples WP Plugin ('.$this->email.')') ); # -H
		curl_setopt ($exec, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($exec, CURLOPT_SSL_VERIFYPEER, 0); 

		$content = curl_exec($exec);
		
		curl_close($exec);
		if(!$content)
		{
			$content = json_encode(array('errors' => array('boleto' => array('não pôde ser gerado, tente novamente mais tarde'))));
		}
		return json_decode($content, true);
	}

	public function print_bill($id)
	{
		$exec = curl_init($this->environments[$this->environment].'bank_billets/'.$id);
		curl_setopt( $exec, CURLOPT_USERPWD, $this->token.':'.$this->key ); # -u
		curl_setopt( $exec, CURLOPT_CUSTOMREQUEST, 'GET' ); # -X
		curl_setopt( $exec, CURLOPT_RETURNTRANSFER, true ); #
		curl_setopt( $exec, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'User-Agent: Boleto Simples WP Plugin ('.$this->email.')') ); # -H
		curl_setopt ($exec, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($exec, CURLOPT_SSL_VERIFYPEER, 0);  

		$content = curl_exec($exec);

		curl_close($exec);

		return json_decode($content, true);
	}
}

?>