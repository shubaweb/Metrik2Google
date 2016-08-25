<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Metrika2Google</title>
    <!--Scripts-->
    <script src="https://apis.google.com/js/client.js"></script> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://malsup.github.com/jquery.form.js"></script>
    <script src="js/auth.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<div class="container-fluid">
    <h1 class="text-center">Metrika2Google</h1>
    <div class="row text-center">
        <h2>Настройка отчета</h2>
        <div class="col-xs-10 col-xs-offset-1">
        <ul class="nav nav-pills nav-justified">
            <li class="active"><a data-toggle="pill" href="#auth">Авторизация</a></li>
            <li><a data-toggle="pill" href="#metrics">Метрики</a></li>
            <li><a data-toggle="pill" href="#dimensions">Группировки</a></li>
            <li><a data-toggle="pill" href="#filters">Фильтры</a></li>
            <li><a data-toggle="pill" href="#other">Другие настройки</a></li>
        </ul>
        </div>
        <form id="request-form" action="getDataFromYandex.php"></form>
        <div class="well col-xs-10 col-xs-offset-1 tab-content" style="margin-top: 10px">
            <div id="auth" class="tab-pane fade in active">
                <div class="form-group col-xs-3 col-xs-offset-2">
                    <input type="button" class="btn btn-default" style="width: 100%;" value="Получить Token Яндекса" id="yaAuthButton" onclick="yaAuth()">
                </div>
                <div class="form-group col-xs-3 col-xs-offset-2">
                    <input type="button" class="btn btn-default" style="width: 100%;" value="Открыть доступ Google Sheets" id="googleAuthButton" onclick="googleAuth()">
                </div>
                <div class="form-group col-xs-5 col-xs-offset-1">
                    <label for="yaToken">Yandex token</label>
                    <input class="form-control" form="request-form" id="yaToken" name="yaToken" required>
                </div>
                <div class="form-group col-xs-5">
                    <label for="gDocId">Введите ID документа Google</label>
                    <input class="form-control" id="gDocId" name="gDocId">
                </div>
                <div class="form-group col-xs-5 col-xs-offset-1">
                    <label for="yaCounterId">Введите ID счетчика Yandex метрики</label>
                    <input form="request-form" name="yaCounterId" class="form-control" id="yaCounterId" required>
                </div>
            </div>
            <div id="metrics" class="tab-pane fade">
                <label class="checkbox-inline">
                    <input type="checkbox" name="metrics[]" form="request-form" value="ym:s:visits"> Визиты
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox" name="metrics[]" form="request-form" value="ym:s:pageviews"> Просмотры
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox" name="metrics[]" form="request-form" value="ym:s:users"> Посетители
                </label>
            </div>
        <div  id="dimensions" class="tab-pane fade">
            <label class="checkbox-inline">
                <input type="checkbox" name="dimensions[]" form="request-form" value="ym:s:deviceCategory">Тип устройства
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" name="dimensions[]" form="request-form" value="ym:s:browser">Браузер
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" name="dimensions[]" form="request-form" value="ym:s:operatingSystem">Операционная система
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" name="dimensions[]" form="request-form" value="ym:s:regionCountry">Страна
            </label>
        </div>
        <div id="filters" class="tab-pane fade">
            <div class="row">
                <div class="col-xs-4 form-group">
                <label for="filter" >Группировка для фильтрации</label>
                <select form="request-form" name="filter" class="form-control" id="filter" >
                    <option value="">Группировка для фильтра</option>
                    <option value="ym:s:deviceCategory">Тип устройства</option>
                    <option value="ym:s:browser">Браузер</option>
                    <option value="ym:s:operatingSystem">ОС</option>
                    <option value="ym:s:regionCountry">Страна</option>
                </select>
                </div>
                <div class="col-xs-4 form-group">
                    <label for="operator">оператор</label>
                    <select form="request-form" name="operator" class="form-control" id="operator">
                        <option value="">выберите оператор</option>
                        <option value="==">==</option>
                        <option value="!=">!=</option>
                        <option value=">=">>=</option>
                        <option value="<="><=</option>
                        <option value=">">></option>
                        <option value="<"><</option>
                        <option value="=@">=@</option>
                        <option value="!@">!@</option>
                        <option value="=~">=~</option>
                        <option value="!~">!~</option>
                        <option value="=*">=*</option>
                        <option value="!*">!*</option>
                        <option value="=.">=.</option>
                        <option value="!.">!.</option>


                    </select>
                </div>
                <div class="col-xs-4 form-group">
                    <label for="match">значение для фильтрации</label>
                    <input form="request-form" name="match" id="match" class="form-control">
                </div>
            </div>
        </div>
        <div id="other" class="tab-pane fade form-group">
            <div class="form-group col-xs-3">
                <label for="limit">Количество элементов на странице выдачи</label>
                <input form="request-form" name="limit" id="limit" class="form-control" placeholder="Значение по умолчанию: 100 (Лимит: 100000)">
            </div>
            <div class="form-group col-xs-3">
                <label for="begin-date">Начало отчетного периода</label>
                <input form="request-form" name="start-date" type="date" class="col-xs-3 form-control" id="begin-date">
            </div>
            <div class="form-group col-xs-3">
                <label for="end-date">Конец отчетного периода</label>
                <input form="request-form" name="end-date" type="date" class="col-xs-3 form-control" id="end-date">
            </div>
            <div class="form-group col-xs-3">
                <label for="lang">Язык</label>
                <select form="request-form" name="lang" class="form-control" id="lang">
                    <option value="ru">русский</option>
                    <option value="en">английский</option>

                </select>
            </div>
        </div>
        </div>
            <input form="request-form" type="submit" value="Перенести данные в Google Docs" class="btn btn-default">
    </div>
</div>
</body>
</html>