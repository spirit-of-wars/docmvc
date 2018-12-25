<?php

$data = $model['data'];
$period = $model['period'];
$dt1 = $model['dt1'];
$dt2 = $model['dt2'];

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
<html>

<head>
	<meta http-equiv=Content-Type content="text/html; charset=utf-8">
	<title>Отчет</title>
	<style type="text/css">
		* {font-size: 12px; line-height: 1.1;}
		@page {margin: 20px;}
		body {margin: 20px;font-family: DejaVu Sans, sans-serif;}
		.header-big {font-size: 18px; font-weight: bold}
		.header-small {font-weight: bold}
		table {border-collapse: collapse;}
		td {padding: 2px;}
		.w100pc {width: 100%;}
		.border-all, .border-all td {border: 1px solid #000000;}
		.border-bottom {border-bottom: 1px solid #000000;}
		.text-right {text-align: right;}
		.text-center {text-align: center;}
	</style>
</head>

<body>

<div style="text-align: center;">
	<span class="header-small">Акт приема-передачи оказанных услуг по договору оказания услуг</span><br>
	<span class="header-small">№ <?= $data['new_dogovor'] ?> от <?= $data['new_dog_date'] ?> за период с <?= $dt1 ?> по <?= $dt2 ?></span>
</div>
<br />

<table class="border-all w100pc">
	<tbody>
		<tr>
			<td style="vertical-align: top;">
				Исполнитель: <?= $data['name'] ?>
			</td>
			<td style="text-align: right; width: 25%; vertical-align: top;">
				Регистрационный номер:<br />
				<?= $data['contract'] ?>
			</td>
		</tr>
	</tbody>
</table>

<span style="font-weight: bold;">Статус: <?= $data['rang_name'] ?></span><br />
<span style="font-weight: bold;">Ступень: <?= $data['rang_number'] ?></span><br />
<span style="font-weight: bold;">Передал, а :</span> Заказчик <?= $data['short_name'] ?></span><br />

<?
	$monthRus = [
		1 => 'январь',  2 => 'февраль', 3 => 'март', 4 => 'апрель',
		5 => 'май',  6 => 'июнь', 7 => 'июль', 8 => 'август',
		9 => 'сентябрь', 10 => 'октябрь', 11 => 'ноябрь', 12 => 'декабрь',
	];
?>

<div>
В результате проведения Исполнителем консультационных, информационных мероприятий, а также оказания других услуг, предусмотренных договором, у Заказчика было приобретено товаров/продукции:
</div>
<br />




<div style="text-align: center;">
	<span class="header-big">Детализация</span>
</div>

<div style="text-align: center;">
	<span class="header-small">Структура покупок продукции Заказчика, осуществленных с участием исполнителя</span>
</div>


<div style="text-align: center;">
	<span class="header-big">Структура начисления  вознаграждения исполнителя в текущем месяце</span>
</div>


<div style="text-align: center;">
	<span class="header-big">Состояние счетов</span>
</div>

<table>
	<tbody>
		<tr>
			<td>Исполнитель</td>
			<td>______________________</td>
			<td>(_________________________________)</td>
		</tr>
		<tr>
			<td></td>
			<td class="text-center">
				<span style="font-size: 8px; font-style: italic;">Подпись</span>
			</td>
			<td  class="text-center">
				<span style="font-size: 8px; font-style: italic;">ФИО</span>
			</td>
		</tr>
	</tbody>
</table>

<p style="font-size: 8px; font-style: italic;">"Акт приема-передачи оказанных Исполнителем услуг Заказчиком принят. У Заказчика  нет претензий по качеству, объему оказанных Исполнителем услуг и по размеру вознаграждения Исполнителя, подлежащему уплате"</p>

<table>
	<tbody>
	<tr>
		<td>Заказчик <?= $data['short_name'] ?></td>
		<td>______________________</td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td style="text-align: center;">
			<span style="font-size: 8px; font-style: italic;">Подпись</span>
		</td>
		<td></td>
	</tr>
	</tbody>
</table>

<p style="font-size: 9px; font-style: italic;">Адрес для отправки почты (индекс, город, улица, номер дома, номер квартиры - обязателен для заполнения)</p>

<table  class="w100pc">
	<tbody>
		<tr>
			<td class="border-bottom">&nbsp;</td>
		</tr>
		<tr>
			<td class="border-bottom">&nbsp;</td>
		</tr>
	</tbody>
</table>

</body>

</html>