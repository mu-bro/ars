<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
<h1><?php echo $heading_title; ?></h1>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>

<div class="filters">
	<div class="main_filter" id="filterpro">
		<div style="display:block;">
			1) <select rel="1" id="transport" onchange="nextStepSelect(this,'brand','Выберите марку');" ></select><br/>
		</div>
		<div id="filter2">
			2) <select rel="2" id="brand" onchange="nextStepSelect(this,'model','Выберите модель');" ></select><br/>
		</div>
		<div id="filter3">
			3) <select rel="3" id="model" onchange="nextStepSelect(this,'modification','Выберите модификацию');" ></select><br/>
		</div>
		<div id="filter4">
			4) <select rel="4" id="modification" onchange="showResults();" ></select><br/>
		</div>
	</div>

	<div class="additional_filter">
		<h2>Дополнительный фильтр</h2>
		<div>
			<select id="dimentions" onchange="showResults();" class="custome">
				<option value="">Габариты аккумулятора (мм)</option>
				<option value="102x48x96">102x48x96</option><option value="105x77x140">105x77x140</option>
				<option value="110x68x110">110x68x110</option>
				<option value="114x39x87">114x39x87</option>
				<option value="114x70x105">114x70x105</option>
				<option value="114x70x131">114x70x131</option>
				<option value="114x70x86">114x70x86</option>
				<option value="114x71x106">114x71x106</option>
				<option value="114x71x86">114x71x86</option>
				<option value="120x60x130">120x60x130</option>
				<option value="122x58x132">122x58x132</option>
				<option value="126x48x126">126x48x126</option>
				<option value="130x90x114">130x90x114</option>
				<option value="134x80x160">134x80x160</option>
				<option value="134x89x166">134x89x166</option>
				<option value="135x75x133">135x75x133</option><option value="135x75x139">135x75x139</option>
				<option value="135x90x155">135x90x155</option><option value="138x61x131">138x61x131</option>
				<option value="150x60x130">150x60x130</option><option value="150x65x93">150x65x93</option>
				<option value="150x69x130">150x69x130</option><option value="150x70x105">150x70x105</option>
				<option value="150x70x145">150x70x145</option><option value="150x87x105">150x87x105</option>
				<option value="150x87x110">150x87x110</option><option value="150x87x130">150x87x130</option>
				<option value="150x87x145">150x87x145</option><option value="150x87x161">150x87x161</option>
				<option value="150x87x93">150x87x93</option><option value="150x87x95">150x87x95</option>
				<option value="156x57x116">156x57x116</option><option value="160x90x130">160x90x130</option>
				<option value="160x90x161">160x90x161</option><option value="168x132x176">168x132x176</option>
				<option value="175x100x155">175x100x155</option><option value="175x100x175">175x100x175</option>
				<option value="175x87x155">175x87x155</option><option value="180x90x162">180x90x162</option>
				<option value="184x124x175">184x124x175</option><option value="186x130x171">186x130x171</option>
				<option value="186x82x171">186x82x171</option><option value="187х127х225">187х127х225</option>
				<option value="205x87x162">205x87x162</option><option value="205x90x162">205x90x162</option>
				<option value="207x72x164">207x72x164</option><option value="207х175х175">207х175х175</option>
				<option value="207х175х190">207х175х190</option><option value="232х173х225">232х173х225</option>
				<option value="238х129х225">238х129х225</option><option value="242х175х175">242х175х175</option>
				<option value="242х175х190">242х175х190</option><option value="260х179х205">260х179х205</option>
				<option value="261х175х220">261х175х220</option><option value="278х175х175">278х175х175</option>
				<option value="278х175х190">278х175х190</option><option value="306х173х225">306х173х225</option>
				<option value="315х175х175">315х175х175</option><option value="315х175х190">315х175х190</option>
				<option value="330х173х232">330х173х232</option><option value="330х173х240">330х173х240</option>
				<option value="347х173х234">347х173х234</option><option value="349х175х290">349х175х290</option>
				<option value="353х175х190">353х175х190</option><option value="393х175х190">393х175х190</option>
				<option value="510х218х230">510х218х230</option><option value="513х186х223">513х186х223</option>
				<option value="513х189х223">513х189х223</option><option value="513х223х223">513х223х223</option>
				<option value="514х175х210">514х175х210</option><option value="514х218х210">514х218х210</option>
				<option value="518х276х242">518х276х242</option><option value="58x62x131">58x62x131</option>
				<option value="70x47x96">70x47x96</option><option value="71x71x96">71x71x96</option>
				<option value="80x70x105">80x70x105</option><option value="91x83x160">91x83x160</option>
				<option value="97x43x52">97x43x52</option><option value="99x57x109">99x57x109</option>
				<option value="99x57x111">99x57x111</option>
			</select>
		</div>
		<div>

			<select id="capacities" onchange="showResults();"  class="custome">
				<option value="">Емкость аккумулятора (Ач)</option>
				<option value="1,2">1,2</option>
				<option value="10">10</option><option value="100">100</option><option value="105">105</option>
				<option value="11">11</option><option value="11,2">11,2</option><option value="110">110</option>
				<option value="12">12</option><option value="120">120</option><option value="135">135</option>
				<option value="14">14</option><option value="150">150</option><option value="16">16</option>
				<option value="18">18</option><option value="19">19</option><option value="190">190</option>
				<option value="2">2</option><option value="2,5">2,5</option><option value="20">20</option>
				<option value="225">225</option><option value="24">24</option><option value="25">25</option>
				<option value="3">3</option><option value="30">30</option><option value="35">35</option>
				<option value="4">4</option><option value="45">45</option><option value="5">5</option>
				<option value="5,5">5,5</option><option value="50">50</option><option value="55">55</option>
				<option value="6">6</option><option value="6,5">6,5</option><option value="60">60</option>
				<option value="62">62</option><option value="63">63</option><option value="65">65</option>
				<option value="66">66</option><option value="7">7</option><option value="70">70</option>
				<option value="71">71</option><option value="72">72</option><option value="73">73</option>
				<option value="74">74</option><option value="75">75</option><option value="8">8</option>
				<option value="8,6">8,6</option><option value="80">80</option><option value="85">85</option>
				<option value="9">9</option><option value="90">90</option><option value="95">95</option>
			</select>
		</div>
		<div>
			<select id="polyarities" onchange="showResults();"  class="custome">
				<option value="">Полярность аккумулятора</option>
				<option value="0 / 1 / STD">0 / 1 / STD</option><option value="0 / 1 / STUD">0 / 1 / STUD</option>
				<option value="0 / small">0 / small</option><option value="0 / STD">0 / STD</option>
				<option value="1">1</option><option value="1 / small">1 / small</option>
				<option value="1 / STD">1 / STD</option><option value="1 / STD, side">1 / STD, side</option>
				<option value="10">10</option><option value="11">11</option><option value="13">13</option>
				<option value="2">2</option><option value="3">3</option><option value="3 / STD">3 / STD</option>
				<option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option>
				<option value="8">8</option><option value="фишка">фишка</option>
			</select>
		</div>
	</div>
</div>
<div id="filter" class="product-grid podbor"></div>


<script>
$.getJSON( "api.php", { url: "http://podborakb.ru/api/transport" } )
  .done(function( transports ) {
	selectPickerOptions("transport",transports, "Выберите тип транспорта...");
});

function getJSONObject(url) {
	var respons;
	$.getJSON( "api.php", { url: url } ).done(function( json ) {
		respons = json;
	});
	return respons;
}

function selectPickerOptions(currItem,json, defaultValue) {	
	htmlText = "";
	if (defaultValue != "") {
		htmlText += "<option value=\"\">"+ defaultValue +"</option>"
	}
	for(var key in json){
		//alert(json[key]);
		console.log(json[key]);
		code = (currItem != "modification") ? json[key]["Id"] : json[key]["Akb"];
		htmlText += "<option value=\""+ code +"\">"+ json[key]["Name"] +"</option>"
	}
	$("#" + currItem).html(htmlText);	
	$("#" + currItem).parent().show();
	$(".additional_filter").css("visibility", "hidden");
}

function nextStepSelect(curItem, apiRequest, defaultValue) {
	$("#filter").html("");
	$(curItem).parent().addClass("loading");
	step = parseInt($(curItem).attr("rel")) + 1;
	for (i = step;i <= 4;i++) {
		$("#filter" + i).hide();
	}	
	curName = $(curItem).attr("id");
	curValue = $(curItem).val();
	url = "http://podborakb.ru/api/" + curName + "/" + curValue + "/" + apiRequest + "s";
	$.getJSON( "api.php", { url: url } ).done(function( json ) {
		selectPickerOptions(apiRequest,json, defaultValue);
		$(curItem).parent().removeClass("loading");
	});
}
function showResults() {
	$("#filter").html("<img style='margin-left:30px;width:40px;' src='catalog/view/theme/ars/image/loader-big.gif' />");
	code = $("#modification").val();
	dimention = $("#dimentions").val();
	capacity = $("#capacities").val();
	polyarity = $("#polyarities").val().replace(/\s/g,"%20");

	url = "http://podborakb.ru/api/filter?code=" + code + "&dimention=" + dimention + "&capacity=" + capacity + "&polyarity=" + polyarity;
	$.getJSON( "index.php?route=information/podbor/results", { url: url } ).done(function( json ) {
	//	$.getJSON( "api.php", { url: url } ).done(function( json ) {
		htmlText = "";
		if (json == "") {
			htmlText = "<span style='margin-left:14px;' >Подходящих аккумуляторов не найдено</span>";
		} else {
			$("#filter").html(json['results']);
		}
//		for(var key in json){
//			htmlText += '<div class="akbBox" >' +
//				'<div class="manufacturer" ><span>' +
//					json[key]["Brand"] +
//				'</span></div>' +
//				'<div class="name" >' +
//					json[key]["Number"] +
//				'</div>' +
//				'<div class="image">' +
//					'<image src="http://podborakb.ru/'+ json[key]["Image"] +'" />' +
//				'</div>' +
//				'<div class="add_info showen" >' +
//					'<div class="parametrs">' +
//						'Ёмкость:' + json[key]["Capacity"] +
//					'</div>' +
//					'<div class="parametrs">' +
//						'Размер:' + json[key]["Dimensions"] +
//					'</div>' +
//
//					'<div class="parametrs">' +
//						'Полярность:' + json[key]["Polyarity"] +
//					'</div>' +
//				'</div>' +
//			'</div>';
//		}

		//$(".additional_filter").css("visibility", "visible");
	});
}
</script>

  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>