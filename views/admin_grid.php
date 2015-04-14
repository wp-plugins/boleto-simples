<?php

$boletosimples = new boletoSimplesAPI();

?>
<style>
	.paid{
		background-color: #F1F0FF ;
	}
	.legend{
		height: 30px;
		padding-top: 13px;
		text-align: center;
	}
</style>


<div class="wrap">	
	<div id="icon-options-general" class="icon32"></div>
	<h2>Últimos 50 Boletos Gerados</h2>
	
	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">
				
				<table class="widefat">
					<thead>
						<tr>
							<th>#</th>
							<th>Nome</th>
							<th>CPF</th>
							<th>CEP</th>
							<th>Vencimento</th>
							<th>Valor</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$bills = $boletosimples->bills();
						foreach ($bills as $bill) {
							?>
							<tr class="<?php echo (!empty($bill['paid_at']))?'paid':''; ?>">
								<td><?php echo $bill['id'] ?></td>
								<td><?php echo $bill['customer_person_name'] ?></td>
								<td><?php echo $bill['customer_cnpj_cpf'] ?></td>
								<td><?php echo $bill['customer_zipcode'] ?></td>
								<td><?php echo date('d/m/Y',strtotime($bill['expire_at'])) ?></td>
								<td><?php echo number_format($bill['amount'],2, ',', '.') ?></td>
								<td><a href="<?php echo $bill['shorten_url'] ?>" target="_blank">Ver</a></td>
							</tr>
							<?php
						}

						if(empty($bills))
						{
							?>
							<tr>
								<td colspan="5"><center>Nenhum boleto encontrado</center></td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
				
			</div> <!-- post-body-content -->
			
			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">
				
				<div class="meta-box-sortables">
					
					<div class="postbox">

						<h3><span>Legenda</span></h3>
						<div class="inside">
							<div>
								<div class="paid legend">Pago</div>
							</div>
						</div> <!-- .inside -->
						
					</div> <!-- .postbox -->
					
				</div> <!-- .meta-box-sortables -->
				
			</div> <!-- #postbox-container-1 .postbox-container -->
			
		</div> <!-- #post-body .metabox-holder .columns-2 -->
		
		<br class="clear">
	</div> <!-- #poststuff -->
	
</div> <!-- .wrap -->