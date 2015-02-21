</div>

<div id="footer">
	<div class="main_block">
		<?php if ($informations) { ?>
			<div class="column">
				<h3><?php echo $text_information; ?></h3>
				<?php echo $informationMenu; ?>
			</div>
		<?php } ?>
		<div class="column">
			<h3><?php echo $text_service; ?></h3>
			<?php echo $supportMenu; ?>
		</div>
		<div class="column">
			<h3><?php echo $text_contacts; ?></h3>
			<div class="sub_title i_time">
				<?php echo $text_time_schedule; ?>
			</div>
			<div class="text">
				<?php echo str_replace("\r","<br/>",$this->config->get("config_schedule_bottom")); ?>
			</div>
			<div class="sub_title i_email second">
				e-mail
			</div>
			<div class="text">
				<?php echo $this->config->get("config_email"); ?>
			</div>
		</div>
		<div class="column">
			<h3>&nbsp;</h3>
			<div class="sub_title i_phone">
				<?php echo $text_phones; ?>
			</div>
			<div class="phones-wrapp">
				<div class="phones left">
					<p><span><?php echo $this->config->get("config_telephone_pre"); ?></span> <strong><?php echo $this->config->get("config_telephone"); ?></strong></p>
					<p style="float:right;"><span><?php echo $this->config->get("config_telephone2_pre"); ?></span> <strong><?php echo $this->config->get("config_telephone2"); ?></strong></p>
				</div>
			</div>
			<div class="clear"></div>
			<div class="sub_title i_bus second">
				<?php echo $text_delivery; ?>
			</div>
			<div class="phones-wrapp">
				<div class="phones left">
					<p><span><?php echo $this->config->get("config_telephone_delivery_pre"); ?></span> <strong><?php echo
							$this->config->get("config_telephone_delivery"); ?></strong></p>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->
<div id="powered">
	<div class="main_block">
		<div class="logo">
			<a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a>
		</div>
		<div class="powered">
			<?php echo $powered; ?>
		</div>
	</div>
</div>
<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->
</div>
<?php echo $chat_plugin; ?>
</body></html>