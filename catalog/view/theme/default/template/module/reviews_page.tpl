<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>


<div class="review_block whole_page" >
	<div>
	<?php foreach ($reviews as $k => $review) { ?>
		<div class="manufacturer-list">
			<div class="review_one <?php if ($k == (count($reviews)-1)) echo 'last'; ?>" >
				<div class="manufacturer-heading">
					<span class="author" ><?php echo $review['author']; ?></span>
					<span class="date" style="float:right;"><?php echo $review['date_added']; ?></span>
				</div>
				<div class="manufacturer-content">
					<div><?php echo $review['text']; ?></div>
					<?php if (!empty($review['answer'])) { ?>
						<div class="review_one answer_block">
							<table width="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td style="width:60px;vertical-align:top;">
										<div class="img"><img src="<?php echo $small_logo; ?>" /></div>
									</td>
									<td align="left" valign="top">
										<div class="author"><b><?php echo $this->config->get('config_name'); ?>:</b></div>
										<div class="answer"><?php echo $review['answer']; ?></div>
									</td>
								</tr>
							</table>
						</div>
					<?php } ?>
				</div>
			</div>
	
		</div>
	<?php } ?>
	</div>
	<br/><br/>
	<h2 id="review-title" style="clear:both;">Оставить отзыв</h2>
	<div id="review_form_block">
		<form action="index.php?route=common/home" method="POST" id="review_form">
		<div>Ваше имя:</div>
		<input type="text" name="name" value="" size="40" />
		<div>Ваш отзыв:</div>
		<textarea name="text" cols="40" rows="8" style="width: 420px;"></textarea>
		</form>
		</br>
		<a id="button-review" class="review-title button">Отправить</a>
		<div class="clearing" style="height:10px;"></div>
	</div>
</div>

<script type="text/javascript"><!--
$('#button-review').bind('click', function() {
	$.ajax({
		url: 'index.php?route=module/reviews/add',
		type: 'post',
		data: $('#review_form').serialize(),
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, information, .error').remove();
			
			if (json['error']) {
				$('#review-title').after('<div class="warning">' + json['error'] + '</div>');
			}
			
			if (json['success']) {
				$('#review-title').after('<div class="success">' + json['success'] + '</div>');
				$('#review_form_block').slideToggle();
				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
			}	
		}
	});
});
//--></script>
  
<?php echo $content_bottom; ?></div>

  
<?php echo $footer; ?>

