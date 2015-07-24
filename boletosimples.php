<?php 
/**
 * @package BoletoSimples
 */
/*
/*
Plugin Name: Boleto Simples
Plugin URI: http://www.mktvirtual.com.br/
Description: Rotina de Integração com API do Boleto Simples, para geração rápida de boletos, antes de configurar o boleto, faça seu cadastro no site www.boletosimples.com.br
Version: 0.5
Author: ooprogramador
Author URI: http://www.mktvirtual.com.br
*/

ini_set('allow_url_fopen', 1);

require('class_boletosimples.php');

if(!class_exists('boletoSimples')) {

	class boletoSimples {

		public $labels = array(
			'amount' => 'Valor',
			'expire_at' => 'Vencimento',
			'customer_person_name' => 'Nome',
			'customer_cnpj_cpf' => 'CPF',
			'customer_zipcode' => 'CEP',
			'customer_email' => 'Email',
			'boleto' => 'Boleto'
			);

		public function __construct()
		{
			add_action('admin_menu', array(&$this,'settings'));
			add_action( 'wp_enqueue_scripts', array(&$this,'scripts' ));

			add_shortcode('boletosimples', array(&$this,'generateBill'));
		}

		/** This method render a submenu on configuration */
		public function settings() {

			add_options_page( '', 'Boleto', 'manage_options', 'boleto-settings', array( &$this, 'admin_updateSettings' ) );
			add_menu_page('Boletos', 'Boletos', 'administrator', 'boleto-grid', array(&$this,'admin_gridBills'), 'dashicons-cart');
		}

		/** Enqueue Scripts on site */

		public function scripts() {
			wp_enqueue_script( 'script-name', plugins_url().'/boletosimples/js/boletosimples.js', array('jquery'));
			//wp_enqueue_script(  );
		}

		/** Generate the donation form */
		public function generateBill( $attr )
		{
			$generated['errors'] = array();

			$attr = shortcode_atts(
				array(
					'values' => 'Nenhum Valor',
					), $attr, 'boletosimples'
				);

			if(isset($_POST['boletosimples']) )
			{
				$bill = new boletoSimplesAPI();
				$generated = $bill->generate();
			}
			
			if(isset($generated['errors']) || empty($_POST['boletosimples']))
			{
				foreach($generated['errors'] as $field => $errors)
				{
					foreach ($errors as $error) {
						echo "<p class='error'>O {$this->labels[$field]} $error</p>";
					}					
				}
				$this->render_form($attr);
			}else{
				$this->render_wait($generated);
			}
		}

		public function render_form($attr)
		{
			?>
			<form method="POST">
				<div class="dados">
					<fieldset>
						<label for="boletosimples[bank_billet][amount]" class="valor">Valor</label>
						<select name="boletosimples[bank_billet][amount]" class="boleto_select" id="donation_value">
							<?php
							if(isset($_POST['boletosimples']['bank_billet']['amount']))
							{
								$donation_value = $_POST['boletosimples']['bank_billet']['amount'];
							}else{
								$donation_value = (isset($_GET['boletosimples']['bank_billet']['amount']))?$_GET['boletosimples']['bank_billet']['amount']:0;
							}

							foreach (explode(',', $attr['values']) as $value)
							{
								$value = trim($value);
								
								$selected = selected( $donation_value, $value , false);

								if( $value === "more")
								{
									echo "<option value='more' {$selected}>Outro Valor</option>";
									continue;
								}

								echo "<option value='{$value}' {$selected}>R$ ".number_format($value,2,',','.')."</option>";
							}

							?>
						</select>
					</fieldset>

					<fieldset <?php echo ($donation_value != 'more')?"style='display:none !important;'":""; ?> class='custom_value'>
						<label for="boletosimples[bank_billet][custom_value]">Valor</label>
						<input type="text" name="boletosimples[bank_billet][custom_value]" class="boleto_text outrovalor" value="<?php echo isset($_POST['boletosimples']['bank_billet']['custom_value'])?$_POST['boletosimples']['bank_billet']['custom_value']:"" ?>"/>
					</fieldset>

					<fieldset>
						<label for="boletosimples[bank_billet][customer_person_name]">Nome</label>
						<input type="text" name="boletosimples[bank_billet][customer_person_name]" class="boleto_text" value="<?php echo isset($_POST['boletosimples']['bank_billet']['customer_person_name'])?$_POST['boletosimples']['bank_billet']['customer_person_name']:"" ?>"/>
					</fieldset>

					<fieldset>
						<label for="boletosimples[bank_billet][customer_email]">Email</label>
						<input type="text" name="boletosimples[bank_billet][customer_email]" class="boleto_text" value="<?php echo isset($_POST['boletosimples']['bank_billet']['customer_email'])?$_POST['boletosimples']['bank_billet']['customer_email']:"" ?>"/>	
					</fieldset>
					
					<div class="bs_customer_cnpj_cpf">
						<fieldset>
							<label for="boletosimples[bank_billet][customer_cnpj_cpf]">CPF</label>
							<input type="text" name="boletosimples[bank_billet][customer_cnpj_cpf]" class="boleto_text bs_customer_cnpj_cpf" value="<?php echo isset($_POST['boletosimples']['bank_billet']['customer_cnpj_cpf'])?$_POST['boletosimples']['bank_billet']['customer_cnpj_cpf']:"" ?>"/>	
						</fieldset>
					</div>

					<div class="bs_customer_zipcode">
						<fieldset>
							<label for="boletosimples[bank_billet][customer_zipcode]">CEP</label>
							<input type="text" name="boletosimples[bank_billet][customer_zipcode]" class="boleto_text bs_customer_zipcode" value="<?php echo isset($_POST['boletosimples']['bank_billet']['customer_zipcode'])?$_POST['boletosimples']['bank_billet']['customer_zipcode']:"" ?>"/>	
						</fieldset>
					</div>
					<input type="submit" value="Doar" />
				</div>
			</form>
			<?php 
		}

		public function render_wait($bill)
		{
			if(empty($bill['shorten_url']))
			{
				?>
				<div id="wait-bill" data-id="<?php echo $bill['id'] ?>">
					<p><center><img src="<?php echo plugins_url( '/boletosimples/images/loading_spinner.gif')?>"></center></p>
					<center>Aguarde, o boleto está sendo gerado.</center>
				</div>
				<?php
			}else{
				?>
				<div id="wait-bill" data-link="<?php echo $bill['shorten_url'] ?>">
					<center><?php echo get_option("boletosimples_success"); ?></center>
					<p><a href="<?php echo $bill['shorten_url'] ?>" target="_blank" class='btn btn-view-bill'>Visualizar</a></p>
				</div>
				<?php
			}
		}

		/** Set the token and API to generate the bill bank */
		public function admin_updateSettings()
		{
			if(!empty($_POST)){
				foreach($_POST as $name=>$value){
					update_option($name, $value);
				}

			}

			include('views/admin_form.php');
		}

		public function admin_gridBills()
		{
			include ('views/admin_grid.php');
		}

		/** * Activate the plugin */
		public static function activate() {
			add_option("boletosimples_environment", "production", null, "no");
			add_option("boletosimples_email", "EMAIL", null, "no");
			add_option("boletosimples_token", "TOKEN", null, "no");
			add_option("boletosimples_key", "CHAVE DE ACESSO", null, "no");
			add_option("boletosimples_description", "DESCRIÇÃO DA COBRANCA", null, "no");
			add_option("boletosimples_success", "Boleto gerado com sucesso! Obrigado pela sua doação.", null, "no");
			add_option("boletosimples_days", "10", null, "no");
		}

		/** * Deactivate the plugin */ 
		public static function deactivate() {
			delete_option("boletosimples_environment");
			delete_option("boletosimples_email");
			delete_option("boletosimples_token");
			delete_option("boletosimples_key");
			delete_option("boletosimples_description");
			delete_option("boletosimples_success");
			delete_option("boletosimples_days");
		}

	}
}

if(class_exists("boletoSimples")){
	register_activation_hook(__FILE__, array('boletosimples', 'activate'));
	register_deactivation_hook(__FILE__, array('boletosimples', 'deactivate'));
	$boletosimples = new boletosimples;
}

?>