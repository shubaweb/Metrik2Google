// ждем пока загрузится DOM
$(document).ready(function() {
    var options = { //опции для ajaxForm http://malsup.com/jquery/form/#getting-started
        success: makeRequest,  // callback при успешном ответе сервера
        dataType: 'JSON', //показываем что назад мы ждем JSON
        type: 'POST' //метод передачи данных на сервер
    };
    $('#request-form').ajaxForm(options); // блокируем нашу форму #request-form и вешаем callback функцию на обработку ответа
    });
$('#request-form').submit(function() {
    $(this).ajaxSubmit(); //по событию submit отправляем данные формы
    return false; // возвращаем false что бы браузер не засабмитил форму
});
function makeRequest(responseText, statusText, xhr, $form)  { //готовим запрос на добавление данных в документы
    var spreadsheetId = document.getElementById('gDocId').value; //ID документа Google
    var alphabet = {1:'A',2:'B',3:'C',4:'D',5:'E',6:'F',7:'G',8:'H',9:'I',10:'J',11:'K',12:'L',13:'M',14:'N',15:'O',16:'P',17:'Q',18:'R',19:'S',20:'T',21:'U',22:'V',23:'W',24:'X',25:'Y',26:'Z'}; //объект соответствия порядкового номера колонки и ее названия
    var countDimen = responseText.query.dimensions.length; //количество группировок
    var countMetr = responseText.query.metrics.length; // количество метрик
    var avgLength = countMetr > countDimen ? countMetr : countDimen; //присваиваем большее из значений кол-во метрик, кол-во группировок
    var colNum = countDimen + countMetr; // номер колонки для range (количество колонок данных равно сумме метрик и группировок)
    var range = 'A1:' + alphabet[colNum] + responseText.total_rows; //range в который мы поместим данные
    var values = []; //массив значений которые нужно передать в Google docs
    for (var i = 0; i < responseText.total_rows; i++) { //проходимся по всем строкам извлеченных данных
        values[i] = []; //каждая строка это массив
        for (var j = 0; j < avgLength; j++){ //проходимся по всем колонкам извлеченных данных, кол-во итераций = количеству метрик или группировок, смотря чего больше
            if (j <= countDimen) { //убедимся что для j-й итерации еще есть свойства в объекте группировок
                values[i][j] = responseText.data[i].dimensions[j].name; //вытягиваем значение для добавления в Google Docs
            }
            if (j <= countMetr) { //убедимся что для j-й итерации еще есть свойства в объекте метрик
                values[i][j + countDimen] = responseText.data[i].metrics[j]; // //вытягиваем значение для добавления в Google Docs
            }
        }
    }
    insertRows(spreadsheetId, range, values); //передаем все переменные в функцию вставки данных в Google Docs
}
function yaAuth() { //получение токена Яндекс
    var YA_CLIENT_ID = '83d1e3b91aee4b1fa8c1a8cb033aa45e'; //ID приложения
    window.open("https://oauth.yandex.ru/authorize?response_type=token&client_id=" + YA_CLIENT_ID +"&display=popup", "Получить токен", "width=500px, height=300px"); //открываем окно авторизации
    //дальше весь код получения токена записан в файле getYaToken.html
}

function googleAuth() { //открыть разрешение для приложения Google
    var CLIENT_ID = '912861931484-47mub2h81ngohbbkdohout4387fm2k4k.apps.googleusercontent.com'; //ID приложения
    var SCOPES = ["https://www.googleapis.com/auth/spreadsheets"]; //Необходимые уровни доступа
    gapi.auth.authorize( //Авторизация
        {
            'client_id': CLIENT_ID,
            'scope': SCOPES.join(' '), //если нужен не один уровень доступа, склеиваем массив в строку
            'immediate': false
        }, handleAuthResult); //передаем полученный объект в обработчик
}

function handleAuthResult(authResult) { //функция обработчик ответа авторизации
    var googleAuthButton = document.getElementById('googleAuthButton'); //находим кнопку #googleAuthButton
    if (authResult && !authResult.error) { //если AuthResult получен и в нем нет ошибок, значит мы авторизированы
        googleAuthButton.className = 'btn btn-success'; //красим кнопку #googleAuthButton в зеленый
        loadSheetsApi(); //загружаем клиентскую библиотеку
    } else { //иначе смотрим ошибку
        googleAuthButton.className = 'btn btn-warning'; //красим кнопку #googleAuthButton в красный
        alert (authResult.error); //выводим ошибку
    }
}
function loadSheetsApi() { //загрузка API для работы с документами
    var discoveryUrl =
        'https://sheets.googleapis.com/$discovery/rest?version=v4';
    gapi.client.load(discoveryUrl);
}
function insertRows(sheetId, range, values) { //вставляем данные в таблицу Google
    gapi.client.sheets.spreadsheets.values.update({
        spreadsheetId: sheetId, //id таблицы
        range: range, //диапазон куда вставляем значения
        majorDimension: "ROWS", //один массив представляет собой ряд
        values: values, //переданный массив данных
        valueInputOption: 'USER_ENTERED' //обрабатываем текст так, как будто пользователь вводит его в таблицах
    }).then(function () {
            alert ('Данные добавлены в документ'); //в случае успеха выводим сообщение
        },
        function (response) {
            appendPre('Error: ' + response.result.error.message); //в случае неудачи выводим ошибку
        })
}