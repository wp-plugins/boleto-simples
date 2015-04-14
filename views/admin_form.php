<div class="wrap">
	<form method="post">
		<div id="icon-options-general" class="icon32"></div>
		<h2>Boleto Simples</h2>

		<div id="poststuff">

			<div id="post-body" class="metabox-holder columns-2">

				<!-- main content -->
				<div id="post-body-content">

					<div class="meta-box-sortables ui-sortable">

						<div class="postbox">
							<h3><span>Configuração</span></h3>
							<div class="inside">
								<p>Para fazer essa configuração, faça o cadastro no site <a href="http://www.boletosimples.com.br" target="_blank">Boleto Simples</a>, e após o cadastro e todas confirmações, será fornecido os dados para os campos abaixo.</p>
								<table class="form-table" id="configure">
									<tbody>
										<tr valign="top">
											<th scope="row">Email</th>
											<td>
												<input name="boletosimples_email" id="" type="text" value="<?php echo get_option("boletosimples_email"); ?>" class="large-text" />
											</td>
										</tr>

										<tr valign="top">
											<th scope="row">Token</th>
											<td>
												<input name="boletosimples_token" id="" type="text" value="<?php echo get_option("boletosimples_token"); ?>" class="large-text" />
											</td>
										</tr>
										<tr valign="top">
											<th scope="row">Chave de Acesso</th>
											<td>
												<input name="boletosimples_key" id="" type="text" value="<?php echo get_option("boletosimples_key"); ?>" class="large-text" />
											</td>
										</tr>

										<tr valign="top">
											<th scope="row">Descrição que irá aparecer</th>
											<td>
												<input name="boletosimples_description" id="" type="text" value="<?php echo get_option("boletosimples_description"); ?>" class="large-text" />
											</td>
										</tr>

										<tr valign="top">
											<th scope="row">Mensagem de Agradecimento pela Doação</th>
											<td>
												<input name="boletosimples_success" id="" type="text" value="<?php echo get_option("boletosimples_success"); ?>" class="large-text" />
											</td>
										</tr>

									</tbody>
								</table>
								<h2 id="wherefind">Não achei esses dados, onde encontro?</h2>
								<h3>É simples, mas caso tenha dificuldades, só seguir o tutorial abaixo.</h3>
								<p>Após fazer login, e ter seus dados validados Boleto Simples, será direcionado para essa página:</p>
								<p><?php echo '<img src="' . plugins_url( 'images/step1.png', dirname(__FILE__) ) . '" width="800px"> '; ?></p>
								<p>No menu superior, clique em "API", você será direcionado para a pagina abaixo</p>
								<p><?php echo '<img src="' . plugins_url( 'images/step2.png', dirname(__FILE__) ) . '" width="800px"> '; ?></p>
								<p>Copie e o conteudo do campo Token e Chave de Acesso da API e cole nos respectivos campos do plugin</p>
								<h2>Pronto ;) - Agora é só colocar o shortcode na página onde quer que seja exibido o formulário de doação</h2>
								<hr/>
								<h2 id="shortcode">Shortcode</h2>
								<p>Abaixo um exemplo do shortcode a ser utilizado para exibição do formulário de doação, o parametro <code>values</code> deve ser passado com todos os valores disponiveis, e caso queira que tenha a possibilidade do doador doar mais, colocar um valor <code>more</code> para que seja incluido um campo texto onde poderá colocar um valor personalizado.</p>
								<p><code>[boletosimples values="25, 50, 70, 100, 1000, more"]</code></p>

							</div> <!-- .inside -->

						</div> <!-- .postbox -->

					</div> <!-- .meta-box-sortables .ui-sortable -->

				</div> <!-- post-body-content -->

				<!-- sidebar -->
				<div id="postbox-container-1" class="postbox-container">

					<div class="meta-box-sortables">

						<div class="postbox">

							<div class="inside">
								<?php submit_button( $text = null, $type = 'primary', $name = 'submit', $wrap = true, $other_attributes = null ) ?>
							</div> <!-- .inside -->

						</div> <!-- .postbox -->

					</div> <!-- .meta-box-sortables -->

				</div> <!-- #postbox-container-1 .postbox-container -->

			</div> <!-- #post-body .metabox-holder .columns-2 -->

			<br class="clear">
		</div> <!-- #poststuff -->
	</form>	
</div> <!-- .wrap -->