<?php
//получаем значения всех нужных переменных из $_POST массива
$counterId = $_POST['yaCounterId']; //номер счетчика
$token = $_POST['yaToken'];//Токен Яндекса
$metrics = implode (',', array_unique($_POST['metrics']));//Метрики
$dimensions = !empty($_POST['dimensions'])? '&dimensions='.implode(',',array_unique($_POST['dimensions'])):'';//Группировки (если не заданы, оставляем пустую строку)
$limit = !empty($_POST['limit'])? '&limit='.$_POST['limit']:'';//Лимит (если не задан, оставляем пустую строку)
//$filters = null;
$url = 'https://api-metrika.yandex.ru/stat/v1/data?id='.$counterId.'&metrics='.$metrics.''.$dimensions.''.$limit; //собираем URL по которому будем обращаться в Яндекс
$headers = array( //массив заголовков передаваемых в CURL
    'Host: api-metrika.yandex.ru',
    'Authorization: OAuth '.$token,
    'Content-Type: application/x-yametrika+json');

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
if (!$result = curl_exec ($ch)){ //если ошибка
    curl_errno($ch); //выводим код ошибки
};
curl_close($ch);
?>