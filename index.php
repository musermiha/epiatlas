<?php
session_start();
require_once("../atlas002bd.php");
require_once 'VisitCounter.php';
try {
  $counter = new VisitCounter($mpdo);
  $counter->registerVisit($_SERVER['REQUEST_URI']);
} catch (Exception $e) {
  error_log("Visit counter error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Территориальный анализ инфекционной заболеваемости" />
  <meta name="keywords" content="эпидемиология, инфекционные заболевания, геоинформационные системы, здоровье населения, форма 2, 0609336">


  <!-- для карты -->
  <link rel="stylesheet" type="text/css" href="aleaflet180/leaflet.css">
  <link rel="stylesheet" type="text/css" href="aleaflet180/easy-button.css">
  <link rel="stylesheet" type="text/css" href="aleaflet180/leaflet.fullscreen.css">
  <link rel="stylesheet" type="text/css" href="aleaflet180/leaflet.legend.css">

  <link rel="stylesheet" type="text/css" href="fontawes470/css/font-awesome.min.css">
  <!-- <link rel="stylesheet" type="text/css" href="fontawes470/css/solid.min.css"> -->
  <!-- <link rel="stylesheet" href="ipaneli.css" /> -->
  <!-- <link rel="stylesheet" href="jmspliter.css" /> -->
  <link rel="stylesheet" href="istyle.css" />


  <!--подключаем библиотеку JQuery-->
  <!-- <script src="ajs/jquery-3.7.1.min.js"></script> -->
  <script src="ajs/jquery-4.0.0.min.js"></script>
  <script src="ajs/jQuery.print.min.js"></script>
  <script src="ajs/FileSaver.min.js"></script>
  <script src="ajs/dom-to-image.min.js"></script>

  <!-- для создания графиков из API Highcharts -->
  <script src="ahc100/code/highcharts.js"></script>
  <script src="ahc100/code/highcharts-more.js"></script>
  <script src="ahc100/code/modules/solid-gauge.js"></script>
  <script src="ahc100/code/modules/exporting.js"></script>
  <script src="ahc100/code/modules/data.js"></script>
  <script src="ahc100/code/modules/drilldown.js"></script>
  <script src="ahc100/code/modules/export-data.js"></script>
  <script src="ahc100/code/modules/accessibility.js"></script>
  <script src="ahc100/code/modules/series-label.js"></script>
  <script src="ahc100/code/modules/no-data-to-display.js"></script>

  <!-- для карты -->
  <script src="aleaflet180/leaflet.js"></script>
  <script src="aleaflet180/easy-button.js"></script>
  <script src="aleaflet180/Leaflet.fullscreen.min.js"></script>
  <script src="aleaflet180/leaflet.browser.print.min.js"></script>
  <script src="aleaflet180/leaflet-providers.js"></script>
  <script src="aleaflet180/leaflet.legend.js"></script>
  <script src="aleaflet180/leaflet.browser.print.min.js"></script>

  <script src="ajs/mscripts.js"></script>
  <!-- наборы цветов -->
  <script src="ajs/mcveti.js"></script>
  <!-- <script src="jmspliter.js"></script> -->

  <title>Эпидатлас РФ ПФО. Карта</title>

  <style>
    .leaflet-control-layers-toggle:after {
      content: "Слои";
      color: #000;
    }

    .leaflet-control-layers-toggle {
      width: auto;
      background-position: 3px 50%;
      padding: 3px;
      padding-left: 36px;
      text-decoration: none;
      line-height: 36px;
    }
  </style>
</head>

<body>


  <!-- Блок заголовка -->
  <?php
  include_once('header.html'); //включаем заголовок flex 
  include_once('../atlas002bd.php'); //подключаемся к БД
  // include_once('../viziteri.php'); //визиты


  // foreach ($GLOBALS as $key => $val) {
  //   unset(${$key}, $key, $val);
  // }
  // die("Сработала защита");


  $ocrug = 105; //код для PHP
  ?>
  <!-- заглушка -->
  <!-- <div id="loading" class="hidden"> -->
  <div id="loading" class="loaded">
    <p>Формирование отчёта...</p>
    <img id="loading-image" src="loader.gif" alt="Loading..." />
  </div>

  <span style="color:#b31212; font-size: 16px;"><img style='border:1px solid #b31212;' src="aimg/icon7662.png" width="30" height="20" title=" Стартовая страница сайта">
    Интерактивная карта. Следующая строка - Выбор параметров запроса: Годы, Месяцы, Уровень территории, Субъект, Контингент, Заболевание. Поля активируются при выборе первого поля (Года).</span>
  <!-- <div> <span>Интерактивная карта. Параметры</span></div> -->

  <div class="atlaswrapper">
    <!-- <header>Header1</header> -->
    <form id="pervaj" method="post" action="f22ashoukarta.php">
      <div class="parametr">
        <input type="hidden" name="kform" value='2'>
        <fieldset id="par01" class="element">
          <legend><strong>Года</strong></legend>
          <select id="gselgod1" name="gselgod1" style="width:60px;" onchange="mgetgodin(this.value)" title="Выбрать год">
            <option value="0">С</option>
            <?php
            // $sql = "SELECT DISTINCT dmpgod FROM dvvodata WHERE dvdzns = 1 order by 1";
            $sql = "SELECT DISTINCT dmpgod FROM dvvodata WHERE sforid = 2 AND stabid = 1 AND dvdzns = 1 ORDER BY 1";
            $stmt = $mpdo->query($sql);
            $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($arr as $row) {
              print '<option value="' . $row['dmpgod'] . '">' . $row['dmpgod'] . '</option>';
            }
            ?>
          </select>
          <select id="gselgod2" name="gselgod2" style="width:60px;" disabled onchange="mgetgodin(this.value)" title="Выбрать год">
            <option value="0">По</option>
            <?php
            // $sql = "SELECT DISTINCT dmpgod FROM dvvodata WHERE dvdzns = 1 order by 1 desc";
            $sql = "SELECT DISTINCT dmpgod FROM dvvodata WHERE sforid = 2 AND stabid = 1 AND dvdzns = 1 ORDER BY 1 DESC";
            $stmt = $mpdo->query($sql);
            $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($arr as $row) {
              print '<option value="' . $row['dmpgod'] . '">' . $row['dmpgod'] . '</option>';
            }
            ?>
          </select>
          <label>
            <span class="tooltip">
              <button class="tooltip-toggle" type="button">
                <span class="fa  fa-question-circle fa-lg" style="color:#ffffff;background:#668fb1;"></span>
              </button>
              <span class="tooltip-text" style="left: 20%;">При выборе первого года остальные поля параметров будут активированы.
                <br> Второй год должен быть равен или больше первого <br> <br> <img src="aimg/hgoda.png">
                <br>Выбранные года и месяца суммируются</span>
            </span>
          </label>
        </fieldset>
        <fieldset id="par02" class="element" disabled onchange="minvertcolor()">
          <legend><strong>Месяцы</strong></legend>
          <label>С &nbsp;<input type="number" name="mes1" id="mes1" onchange="mvibormes()" step="1" min="1" max="12" value="1" required></label>
          <label> по <input type="number" name="mes2" id="mes2" onchange="mvibormes()" step="1" min="1" max="12" value="12" required></label>
          <label>
            <span class="tooltip">
              <button class="tooltip-toggle" type="button">
                <span class="fa  fa-question-circle fa-lg" style="color:#ffffff;background:#668fb1;"></span>
              </button>
              <span class="tooltip-text" style="left: 20%;">При выборе, второй месяц должен быть равен или больше первого <br>
                <br> <img src="aimg/mesaca033.png">
                <br>Выбранные месяца и года суммируются</span>
            </span>
          </label>
        </fieldset>
        <fieldset id="par04" class="element" disabled onchange="mpradio03()" title="Выбрать уровень территории">
          <legend><strong>Уровень территории</strong></legend>
          <select id="gselter" name="gselter">
            <option value='1'>По округу</option>
            <!-- <option value='3'>По субъектам</option> -->
            <option value='4'>По субъектам округа</option>
            <option value='5'>По районам округа</option>
            <option value='6'>По районам субъекта</option>
            <option value='7'>По группам нас.тер.округа</option>
            <option value='8'>По группам нас.тер.субъекта</option>
            <option value='9'>По центрам субъектов</option>
            <option value='10'>По группам плотн.тер.округа</option>
            <option value='11'>По группам плотн.тер.субъекта</option>
          </select>
          <label>
            <span class="tooltip">
              <button class="tooltip-toggle" type="button">
                <span class="fa  fa-question-circle fa-lg" style="color:#ffffff;background:#668fb1;"></span>
              </button>
              <span class="tooltip-text" style="left: 45%;top:200px;">Выбрать какие территории будут показаны на карте
                <br>Округ
                <br>Все субъекты
                <br>Субъекты выбранного округа
                <br>Районы выбранного округа
                <br>Районы выбранного субъекта
                <br>По группам численности населения территорий округа
                <br>По группам численности населения территорий субъекта
                <br>По административным центрам субъектов округа
                <br>По группам плотности населения территорий округа
                <br>По группам плотности населения территорий субъекта
              </span>
            </span>
          </label>
        </fieldset>
        <fieldset id="par06" class="element" disabled onchange="minvertcolor()" title="Выбрать субъект округа">
          <legend><strong>Субъект</strong></legend>
          <select id="gselsubj" name="gselsubj">
            <option value='0'>Субъект</option>
          </select>
          <label>
            <span class="tooltip">
              <button class="tooltip-toggle" type="button">
                <span class="fa  fa-question-circle fa-lg" style="color:#ffffff;background:#668fb1;"></span>
              </button>
              <span class="tooltip-text" style="left: 55%;top:200px;">Выбирается субъект, районы которого будут показаны на карте
                <br>Позиция будет активна при выборе уровня территории
                <br>- По районам субъекта
                <br>- или По группам тер.субъекта
              </span>
            </span>
          </label>
        </fieldset>
        <fieldset id="par07" class="element" disabled onchange="minvertcolor()" title="Выбрать графу(контингент) таблицы">
          <legend><strong>Графа(контингент)</strong></legend>
          <select id="gselgrafa" name="gselgrafa">
            <option value='0'>Всего</option>
          </select>
          <label>
            <span class="tooltip">
              <button class="tooltip-toggle" type="button">
                <span class="fa  fa-question-circle fa-lg" style="color:#ffffff;background:#668fb1;"></span>
              </button>
              <span class="tooltip-text" style="left: 70%;top:200px;">Выбрать контингент
                <br>Всего
                <br>0-17 лет (включительно)
                <br>0-14 лет (включительно)
                <br>до 1 года
                <br>1-2 года (включительно)
                <br>3-6 лет (включительно)
                <br>3-6 посещающих ДДУ
                <br>Село всего
                <br>село 0-17 лет (вкл-но)
                <br>Умерло всего
                <br>умерло 0-17 лет (вкл-но)
              </span>
            </span>
          </label>
        </fieldset>
        <fieldset id="par08" class="element" disabled onchange="minvertcolor()" title="Выбрать заболевание или группу">
          <legend><strong>Заболевание</strong></legend>
          <select id="gselzabol" name="gselzabol">
            <option value='0'>Заболевание</option>
          </select>
          <label>
            <span class="tooltip">
              <button class="tooltip-toggle" type="button">
                <span class="fa  fa-question-circle fa-lg" style="color:#ffffff;background:#668fb1;"></span>
              </button>
              <span class="tooltip-text" style="left: 85%;top:200px;">Выбрать группу или заболевание
                <br>Выбирается группа инфекций
                <br>или одно заболевание.
                <br>При выборе "Все инфекционные"
                <br>или группы по МП(Механизму Передачи)
                <br>суммируются только основные позиции,
                <br>строки "из них" в сумму НЕ ВХОДЯТ,
                <br>суммируются заболевания первой таблицы.
                <br>При выборе "Все паразитарные",
                <br>суммируются заболевания второй таблицы.
              </span>
            </span>
          </label>
        </fieldset>
        <fieldset id="par09" class="element" disabled onchange="minvertcolor()">
          <legend><strong>Настройка</strong></legend>
          <label id='slide_boot' onclick="mzakrit2menu()" title="Дополнительные параметры">
            <span class="fa fa-gear fa-2x" style="color:#3AE2CE;"></span>
            <!-- <span> <img src="aimg/mapcvet04.png" width="15"></span> -->
          </label>
          <ul id='slide_nast' class="pastmenu">
            <button class="butmenu" onclick="mzakrit2menu()" title="Закрыть">X</button>
            <p style="font-size: 15px; color: blue; font-family: Helvetica, Arial, sans-serif; font-weight: bold;text-align:center;">Показатели</p>
            <li><label title="Рассчитывается на 100 000 населения"><input type="radio" value='1' name="vsdata" checked require>Относительные(0/0000)</label></li>
            <li><label><input type="radio" value='2' name="vsdata" style="background: #FF4040;">Абсолютные</label></li>
            <p style="font-size: 15px; color: blue; font-family: Helvetica, Arial, sans-serif; font-weight: bold;text-align:center;">Кол-во групп</p>
            <li><label><input type="number" name="grup" id="grup" class="blpar" onchange="mviborgrup()" step="1" min="2" max="8" value="2" required></label></li>
          </ul>
          <!-- легенда -->
          <!-- <button type="button" onclick="toggleLegend();">My</button> -->
          <label>
            <span class="tooltip">
              <button class="tooltip-toggle" type="button">
                <span class="fa  fa-question-circle fa-lg" style="color:#ffffff;background:#668fb1;"></span>
              </button>
              <span class="tooltip-text" style="left: 70%;"> - Первая позиция - показатели Абсолютные или Относительные
                <br> - Вторая позиция - количество групп
              </span>
          </label>
        </fieldset>
        <!-- <input type="hidden" id="ocrug" name="ocrug" value='105'> -->
        <fieldset id="par10" class="element" disabled onchange="minvertcolor()">
          <legend><strong>Выполнение</strong></legend>
          <button input type="submit" id="btin0" title="Выполнить ранжирование">
            <i class="fa fa-sort-amount-desc fa-2x"></i>
          </button>
          <button id="btin1" onclick="mplohad()" title="Выполнить заливку">
            <i class="fa fa-soundcloud fa-2x"></i>
          </button>
          <button id="btin2" onclick="mstolbiki()" title="Выполнить столбики">
            <i class="fa fa-signal fa-2x"></i>
          </button>
          <button id="btin3" onclick="mkrujki()" title="Выполнить кружочки">
            <i class="fa fa-circle fa-2x"></i>
          </button>
          <!-- <button id="sbtin" onclick="msbrosparam()" title="Сброс параметров">
            <span class="fa fa-retweet" style="font-size: 14px;"></span>
          </button> -->

          <label>
            <span class="tooltip">
              <i class="tooltip-toggle" type="button">
                <span class="fa  fa-question-circle fa-lg" style="color:#ffffff;background:#668fb1;"></span>
              </i>
              <span class="tooltip-text" style="left: 80%;">

                <i class="fa fa-sort-amount-desc" style="font-size: 14px;color:#F8F4F4;background:#2EA23A;"></i> - Первая кнопка - выполнение
                <br> Уровень заболеваемости на карте отражается в виде горизонтальных полосок<br> <br> <img src="aimg/mesaca040.png" style="width:80px; height:50px;">

                <br><i class="fa fa-soundcloud" style="font-size: 14px;color:#F8F4F4;background:#2EA23A;"></i>
                - Вторая кнопка - выполнение
                <br> Уровень заболеваемости на карте отражается в виде заливки <br> <br> <img src="aimg/mesaca060.png" style="width:80px; height:50px;">

                <br> <i class="fa fa-signal" style="font-size: 14px;color:#F8F4F4;background:#2EA23A;"></i>
                - Третья кнопка - выполнение
                <br> Уровень заболеваемости на карте отражается в виде столбиков <br> <br> <img src="aimg/mesaca050.png" style="width:80px; height:50px;">
                <br> Высота столбика зависит от показателя заболеваемости

                <br> <i class="fa fa-circle" style="font-size: 14px;color:#F8F4F4;background:#2EA23A;"></i>
                - Четвертая кнопка - выполнение
                <br> Уровень заболеваемости на карте отражается в виде кружочков <br> <br> <img src="aimg/mesaca070.png" style="width:80px; height:50px;">
                <br> Радиус круга зависит от показателя заболеваемости.
                <br> Все кнопки краснеют при изменении параметров.
              </span>
            </span>
          </label>
        </fieldset>
      </div>
    </form>
    <div id="mkarta" class="vizualmap"></div>

    <!-- <div id="infoPanel" style="display: none;">
        <h3>Информация об объекте</h3>
        <div id="featureInfo"></div>
    </div> -->

    <div id="infoPanel">
      <div class="panel-header">
        <h3 class="panel-title">Заболеваемость</h3>
        <button class="close-btn" onclick="closeInfoPanel()">×</button>
      </div>
      <div id="featureInfo"></div>
    </div>

    <div id="smodal" class="smodal">
      <p style="text-align:center;font-weight:bold;">ТЕРРИТОРИАЛЬНО РАСПРЕДЕЛЕННЫЙ ГЕОИНФОРМАЦИОННЫЙ ПРОГРАММНЫЙ КОМПЛЕКС
        «ЭЛЕКТРОННЫЙ ЭПИДЕМИОЛОГИЧЕСКИЙ АТЛАС РОССИЙСКОЙ ФЕДЕРАЦИИ. ТЕРРИТОРИЯ ФЕДЕРАЛЬНОГО ОКРУГА»
        <br>(ГИС «ЭПИДЕМИОЛОГИЧЕСКИЙ АТЛАС РОССИИ. ТЕРРИТОРИЯ ФЕДЕРАЛЬНОГО ОКРУГА»)
      </p>
      <p>
        предназначен для применения в региональных органах исполнительной власти, в надзорных органах и учреждениях Роспотребнадзора, в медицинских организациях Министерства здравоохранения субъектов.
      </p>
      <p style="font-size: 15px;">
        Базы данных сформированы из форм Федерального статистического наблюдения: <br>
        Форма № 2 «Сведения об инфекционных и паразитарных заболеваниях», предоставленных учреждениями РПН субъектов Приволжского Федерального округа.
      </p>
      <!-- <a href="slaidifo.html" target="_blank"> <img src="aimg/maderu01.jpg" style="width: 85px ; height: 85px; transform: translate(290px, 10px);" title="Сделано в России"></a> -->
    </div>
    <!-- модальное окно -->
    <!-- <div id="customModal" class="custom-modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Детализация</h2>
        <table id="customTable">
        </table>
    </div>
    <button class="close">Закрыть</button>
</div> -->

  </div>
  <!-- Блок подвала 
<a href="slaidifo.html" target="_blank" rel="noreferrer" style=font-size:16px;><span class="fa fa-book" -->
  <?php
  include_once('footer.html');
  ?>
  <script>
    $(window).on('load', function() {
      $('#loading').hide();
    })

    let ocrug = 105; //код для js
    let nniiem = "Федеральная служба по надзору в сфере защиты прав потребителей и благополучия человека ННИИЭМ им. академика И. Н. Блохиной"; //запас
    //Блок фомирования имя экспортного файла для Excel,PDF,CSV,HTML
    let now = new Date();
    //имя файла экспорта
    let fnam = 'ASF2' + now.yyyymmdd().slice(2, 12);
    let draw = false;
    let newtb; // общая таблица
    let csort; //стлб сортировки
    let nmgod = now.yyyymmdd().slice(0, 4); //надо брать из базы(последний год с данными) 
    let startgod = 2010;
    let mes1 = 1;
    let mes2 = 12;
    // массив для карты
    let gset, mset, mmap, map1;
    //панель № 1 параметры по умолчанию
    let r03 = '1'; //что слева таблицы - заболевания
    let r04 = '1'; //уровень территории - округ 
    let r10 = '1'; //по годам/по месяцам
    let r11 = '1'; //относит/абсолю/стандарт
    let i = 0;
    // const htmlTable = 'table id = "dt-table" class="cell-border compact nowrap order-column"></table>';
    // const domTable = document.getElementById('mdtable');
    // let olddt = document.getElementById('dt-table');
    // массивы для графиков
    let mgoda = []; //года
    let masgrf = []; //составной

    let rmas = [];
    let tgraf = 1;
    const mexa = '#dt-table'; //заболевания

    // Блок карты
    let cvetm = 1; //цвет заливки карты
    // let incvet = 0; //интенсивность цвет заливки карты
    let opa = 0.5; //прозрачность
    let tinv; //интервал
    let prfs = 0; //средний показатель
    let ngrup = 3; //кол-во групп
    let kgrup = 2; //кол-во групп HTML
    let logrup = false;
    let grades = []; //показатели
    let msred = ["РФ", "ФО", "СУ"]; //для легенды средние
    let legend; //легенда
    let titylm; //заголовок карты
    let osm, obozn, masc;
    let logm = false;
    let oblog = false;
    let mmval = 0;
    let viborcolor;
    let showLegend = true;
    let msiz = 6;

    //выбор первого года
    function mpradio00() {
      minvertcolor();
      $("#gselgod2").prop("disabled", false);
      $("#par02").prop("disabled", false);
      $("#par04").prop("disabled", false);
      // $("#par06").prop("disabled", false);
      // $("#par07").prop("disabled", false);
      $("#par08").prop("disabled", false);
      $("#par09").prop("disabled", false);
      $("#par10").prop("disabled", false);
    }

    //изменяет цвет кнопки
    function minvertcolor() {
      $("#btin0").css({
        backgroundColor: 'red'
      });
      $("#btin1").css({
        backgroundColor: 'red'
      });
      $("#btin2").css({
        backgroundColor: 'red'
      });
      $("#btin3").css({
        backgroundColor: 'red'
      });
    }

    //сброс параметров и очистка таблицы
    // function msbrosparam() {
    //   $("#pervaj")[0].reset();
    //   // newtb.clear().draw();
    //   $("btin").css('background-color', '#3CB371');
    //   // $("#gselsravn").prop("disabled", true);
    // }
    //заполнение выпадающих списков годы,территории,заболевания,графы
    function mgetgodin(mval) {
      mviborgod();
      mpradio00();
      // minvertcolor();
      //графа
      var par07 = $.post('f22mselgrafa.php', {
        kod: 2,
        ktb: 1
      });
      par07.done(function(md07) {
        $('#gselgrafa').html(md07);
      });
      // //максимальный месяц
      var par02 = $.post('f22mselmaxmes.php', {
        kod: mval
      });
      par02.done(function(md02) {
        $('#mes2').html(md02);
      });
      //заболевания
      var par08 = $.post('f22mselgzabol.php', {
        kod: 2, //код отчетной формы
        kot: 0, //код для отображения
        ktb: 1 //код таблицы
      });
      par08.done(function(md08) {
        $('#gselzabol').html(md08);

      })
    }

    //Субъекты
    function mgetsubj(mval) {
      var part = $.post('f22mselsubjekt.php', {
        kod: mval
      });
      part.done(function(md06) {
        $('#gselsubj').html(md06);
      })
    }
    //выбор уровня территории
    function mpradio03() {
      minvertcolor();
      r04 = $("#gselter").val(); //Россия, округ,субъекты, районы
      logrup = false;
      switch (r04) { // '1'>По России,'2'>По округам,'3'>По субъектам,'4'>По субъектам округа,'5'>По районам округа,'6'>По районам субъекта
        case '1': // по 
          $('#par05').prop('disabled', true);
          $('#par06').prop('disabled', true);
          $('#par08').prop('disabled', true);
          msiz = 8;
          break;
        case '4': // по субъектам округа
          $("#par05").prop("disabled", false);
          $("#par06").prop("disabled", true);
          $('#par08').prop('disabled', false);
          msiz = 7;
          break;
        case '5': // По районам округа
          $("#par05").prop("disabled", false);
          $("#par06").prop("disabled", true);
          $('#par08').prop('disabled', false);
          msiz = 10;
          break;
        case '6': // По районам субъекта
          $("#par05").prop("disabled", false);
          $("#par06").prop("disabled", false);
          $('#par08').prop('disabled', false);
          mgetsubj(ocrug);
          msiz = 10;
          break;
        case '7': // По груп.терр.округа
          $("#par05").prop("disabled", false);
          $("#par06").prop("disabled", true);
          $('#par08').prop('disabled', false);
          msiz = 10;
          break;
        case '8': // По груп.терр.субъекта
          $("#par05").prop('disabled', false);
          setTimeout(function() {
            $('#par06').prop('disabled', false);
          }, 10);
          $('#par08').prop('disabled', false);
          mgetsubj(ocrug);
          msiz = 10;
        case '9': // По центрам субъектов
          $("#par05").prop("disabled", false);
          $('#par06').prop("disabled", true);
          $('#par08').prop('disabled', false);
          msiz = 10;
          break;
        case '10': // По груп.терр.округа
          $("#par05").prop("disabled", false);
          $("#par06").prop("disabled", true);
          $('#par08').prop('disabled', false);
          msiz = 10;
          break;
        case '11': // По груп.терр.субъекта
          $("#par05").prop('disabled', false);
          setTimeout(function() {
            $('#par06').prop('disabled', false);
          }, 10);
          $('#par08').prop('disabled', false);
          mgetsubj(ocrug);
          msiz = 10;
      }
    }


    // стартовая функция принимает код таблицы,код округа,год 1,год 2,код графы
    function mstart(ocrug, gg1, gr) {
      // console.log(ocrug, gg1, gr,"start");

      $.ajax({
        url: 'f22startovaj.php', //Куда пойдет запрос
        method: 'post',
        // dataType: 'json',
        // dataType: 'HTML',
        data: {
          kodok: ocrug,
          gselgod1: gg1,
          gselgrafa: gr
        },
        /* Параметры передаваемые в запросе. */
        success: function(result) {
          //  console.log(result);
          rmas = eval(result);
          // console.log("index 508 - " + rmas);
          // //массив для карты
          titylm = "Заболевания с наибольшим показателем за " + gg1 + " год. Контингент - " + mvozvrattextid('gselgrafa');
          f221kartaznaki('mkarta', true, 56, 63, 5, 5, titylm, rmas, 15); //работает
        },
      });
    }

    //крайний год в базе с данными - только так!!! - текущий
    let data = function() {
      return $.ajax({
        type: 'POST',
        url: 'f22mselmaxgod.php',
        dataType: 'json',
        data: {
          kod: '2',
        },
      });
    }

    function mplohad() {
      mmval = 1;
    }

    function mstolbiki() {
      mmval = 2;
    }

    function mkrujki() {
      mmval = 3;
    }

    function init() {
      //крайний год в базе с данными
      data().then(function(response) {
        nmgod = response;
        // console.log("nmgod - "+ nmgod );
        mstart(ocrug, nmgod, 1);
      });
      // mstart(ocrug, nmgod, 1, 12, 1, 0);
    }
    // Первая начало
    $(document).ready(function() {

      // if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {

      //   alert("Система настроена для работы с ПК или ноутбуком");
      //   // } else {
      //   //   // код для обычных устройств
      //   //   alert("ПК");
      // }
      init();
      // дополнительные настройки
      mpokazparametr();
      mpokazparametr2();
    });

    function mtosavestorag(mkey, val) {
      sessionStorage.setItem(mkey, val)
    }
    //отправить форму(кнопка Применить)f1addinzab.php/f1adinater.php/f1adinakon.php

    $('#pervaj').on("submit", function(e) {

      $("#smodal").hide('fast') //инструкция
      //уровень тер.
      $("#btin0").css({
        backgroundColor: '#14a307e0'
      });
      $("#btin1").css({
        backgroundColor: '#14a307e0'
      });
      $("#btin2").css({
        backgroundColor: '#14a307e0'
      });
      $("#btin3").css({
        backgroundColor: '#14a307e0'
      });

      e.preventDefault(); //отменяем стандартное действие при отправке формы
      // $("#buttonmap").show();
      // e.preventDefault(); //отменяем стандартное действие при отправке формы
      let m_action = $(this).prop('action'); //получаем из формы адрес скрипта на сервере, куда нужно отправить форму action="f22ashoukarta.php"
      let m_method = $(this).prop('method'); //берем из формы метод передачи данных

      // let m_data = $(this).serialize() + '&ocrug=' + ocrug; //получаем данные, введенные пользователем в формате input1=value1&input2=value2...,то есть в стандартном формате 
      // let m_data = $(this).serialize(); //получаем данные, введенные пользователем в формате input1=value1&input2=value2...,то есть в стандартном формате 
      titylm = mvozvrattextid("gselzabol");
      let m_data = $(this).serializeArray();
      m_data.push({
          name: "ocrug",
          value: ocrug
        }, {
          name: "zabol",
          value: titylm
        }, {
          name: "mmval",
          value: mmval
        }

      );
      //вставляем в массив после gselter
      // if ($("#gselsubj").val() == '0') {
      //   m_data.splice(6, 0, {
      //     name: "gselsubj",
      //     value: "0"
      //   });
      // }
      // localStorage.clear();
      sessionStorage.clear();
      // let myarray = m_data.serializeArray();
      const f1parametr = JSON.stringify(m_data);
      sessionStorage.setItem("fpervaj", f1parametr);
      // console.info(m_data);

      // f22ashoukarta.php
      $.ajax({
        url: m_action,
        type: m_method,
        data: m_data,
        // cache: false,
        // async: false,
        success: function(result) {
          console.info(result);
          // throw new Error('Ошибка');
          if (result.length == 1) {
            alert("Ошибка в параметрах или данные отсутствуют");
          } else {
            rmas = eval(result);
            // console.log("index 688 rmas");
            // console.info(rmas);
            titylm = mvozvrattextid("gselzabol") + ". Контингенты -  " + mvozvrattextid("gselgrafa") +
              ". Год: " + $("#gselgod1").val() + " - " + $("#gselgod2").val() +
              ". Месяц: " + $("#mes1").val() + " - " + $("#mes2").val();
            razm = rmas.length;
            let maskart = Array.from(Array(razm), () => new Array(7));
            for (let i = 0; i < razm; i++) {
              maskart[i][0] = rmas[i][0]; //имя
              maskart[i][1] = rmas[i][1]; //geojson
              maskart[i][2] = rmas[i][2]; //56.3286700"
              maskart[i][3] = rmas[i][3]; //"52.0020500"
              maskart[i][4] = rmas[i][5]; //отн
              maskart[i][5] = rmas[i][6]; //абсолют
            }

            const sessionDataString = sessionStorage.getItem('fpervaj');
            const usersArray = JSON.parse(sessionDataString || '[]');
            const gseltr = usersArray.find(user => user.name === 'gselter')?.value;
            let gstro = gseltr > 9 ? 8 : 7;
            let nstro = razm > gstro ? 15 : gstro;

            $('#mkarta').html = '';
            if (gseltr != 9) {
              if (gseltr < 7) {
                switch (mmval) {
                  case 0: //горизонт
                    f221kartaznaki('mkarta', true, 56, 63, 5, 5, titylm, rmas, nstro); //работает
                    break;
                  case 1: //слой
                    // f22kartasloi('mkarta', true, rmas[0][2], rmas[0][3], rmas[0][4], msiz, titylm, maskart);
                    fkartasloipoleh('mkarta', true, rmas[0][2], rmas[0][3], rmas[0][4], msiz, titylm, maskart);
                    mmval = 0;
                    break;
                  case 2: //столб
                    f22kartaznaki('mkarta', true, rmas[0][2], rmas[0][3], rmas[0][4], msiz, titylm, maskart); //работает
                    mmval = 0;
                    // fkartasloistolb('mkarta', true, rmas[0][2], rmas[0][3], rmas[0][4], msiz, titylm, maskart); //работает
                    // mmval = 0;
                    break;
                  case 3: //круг
                    f22kartakrugi('mkarta', true, rmas[0][2], rmas[0][3], rmas[0][4], msiz, titylm, maskart); //работает
                    mmval = 0;
                    break;
                }
              } else {
                if (mmval == 0) {
                  f221kartaznaki('mkarta', true, 56, 63, 5, 5, titylm, rmas, nstro); //работает
                }else{
                  alert("Карта не предусмотрена");
                }
              }

            } else { //центры субъектов
              if (mmval == 0) {
                f221kartaznaki('mkarta', true, 56, 63, 5, 5, titylm, rmas, nstro); //работает
                mmval = 0;
              } else {
                mkartagorodakrug('mkarta', true, rmas[0][2], rmas[0][3], rmas[0][4], msiz, titylm, maskart);
                // f22kartakrugi('mkarta', true, rmas[0][2], rmas[0][3], rmas[0][4], msiz, titylm, maskart); //работает
                mmval = 0;
              }
            }


            // if (mmval == 1) {
            //   // console.log("pervaj maskart - " + maskart);
            //   // console.log("pervaj ngrup - " + ngrup);
            //   // $('#buttonmap').show();


            // } else {
            //   // console.log(maskart);
            //   // $('#buttonmap').hide();

            // }
          }
        },
        beforeSend: function() {
          // запускаем прелоадер
          $('#loading').show();
        },
        complete: function() {
          // останавливаем прелоадер
          $('#loading').hide();
        }
      });
      // }
      return false;
    });
  </script>
</body>

</html>