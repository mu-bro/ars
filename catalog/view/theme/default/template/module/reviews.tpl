
<div class="box">
	<div class="box-heading">Отзывы</div>
	<div class="box-content">

		<?php foreach ($reviews as $k => $review) { ?>
			<div class="manufacturer-list <?php if ($k == (count($reviews)-1)) echo 'last'; ?>" >
				<div class="manufacturer-heading">
					<span><?php echo $review['author']; ?>:</span>
					<span class="date"><?php echo $review['date_added']; ?></span>
				</div>
				<div class="manufacturer-content">
					<div><?php echo $review['text']; ?></div>
				</div>
			</div>
		<?php } ?>

		<a class="review-title small_button" onclick="$('#review_form_block').slideToggle();">Оставить отзыв</a>
		<a id="review-title"  class="review-title small_button" href="<?php echo $this->url->link('module/reviews/getList'); ?>">Все отзывы</a>
		<div class="clearing" style="height:0px;"></div>
		<div id="review_form_block" style="display:none;">
			<form action="index.php?route=common/home" method="POST" id="review_form">
			Ваше имя:<br />
			<input type="text" name="name" value="" />
			<br />
			Ваш отзыв:<br />
			<textarea name="text" cols="40" rows="8" style="width: 220px;"></textarea><br />
			</form>
			<a id="button-review" class="button"><span>Отправить</span></a>
			<div class="clearing" style="height:10px;"></div>
		</div>
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