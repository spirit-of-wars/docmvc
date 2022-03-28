Библиотека предназначена для работы с файлами PDF, Excel, Word.
Представляет собой микро-фреймворк в mvc-модели

**Общие положения:**
- Работа осуществляется путем вызова DocumentManager и загрузкой в него класса-картриджа.
- Работу с данными, настройку всех путей и обязательных параметров необходимо производить только в классе-картридже
- Для генерации документа есть два пути: либо рендерить документ с нуля (используется view), либо рендерим через уже 
существующий документ (template)
- Логику рендера самого документа необходимо прописывать только в файле представления (view)
- Для генерации view логики используются сторонние библиотеки. Объект процессоров этих библиотек доступен во 
 view-файле через переменную $driver  
       - документы Doc/Docx - phpoffice/phpword  
       - документы Excel - phpoffice/phpspreadsheet  
       - документы Pdf - dompdf/dompdf
- Шаблон (template) - это документ-заготовка, в которых еще нет нужных данных но они подставляются в процессе генерации. Такие 
данные определяются только в модели. (Пример: Для генерации excel документа мы создали заготовку template.xlsx и внутри 
в фигурных скобках указываем переменные от куда брать значения)


**Для начала работы необходимо:**
1) Создать общую директорию в которой все будет хранится. Я использую директорию с именем document
2) В ней создать директорию с произвольным именем. Тут каждая директория отвечает за свой документ.
3) В этой директории создаем классы-картриджи. 
Например мне нужно сделать документ финансового отчета в форматах doc и pdf.
В директории Document/ я создам папку Report/
В ней создам файлы Doc.php (наследуемся от SpiritOfWars\DocMVC\Cartridge\DocCartridge) и Pdf.php (SpiritOfWars\DocMVC\Cartridge\PdfCartridge)
4) В каждом таком картридже есть 1 абстрактный метод, который обязательно нужно определить:
    - setupView - должен вернуть строку, содержащую путь и имя вью-файла. _НЕ МОЖЕТ БЫТЬ ПУСТЫМ!_ Вью-файл обязательно 
должен быть создан и указан путь к нему.
   Так же каждый картридж имеет три опциональных метода:
    - setupModel - должен вернуть массив данных которые будут использоваться во вью файле либо при подстановке данных в шаблон
    - setupTemplate - должен вернуть строку, содержащую путь и имя шаблона. (Не доступен для Pdf-картриджей)
    - setupRequiredParams - должен вернуть массив обязательных параметров, которые должны быть переданы в конструктор класса.
     Указываем только те параметры, без которых генерация документа невозможна. Если таких нет, не трогаем этот метод
5) Создаем директорию view (в нашем примере Document/Report/view/)
6) Создаем view-файл с любым именем (в нашем примере для pdf документа можно создать такой файл Document/Report/view/view-pdf.php)
7) Указываем классе-картридже путь до вью-файла в методе setupView (в нашем случае: return 'view-pdf.php';).
ВАЖНО! Указываем не полный путь, а относительный от папки view.
8) Если есть шаблон, повторяем пункты 6,7,8 для шаблона. Создаваемая папка обязательна должна называться template. 
Путь до шаблона возвращаем методом setupTemplate. ВАЖНО! Указываем не полный путь, а относительный от папки template.
9) Во вью файле мы либо прописываем html верстку, либо выполняем php код по генерации контента. По-умолчанию доступны 
две переменные $driver - объект для работы с генерацией контента документа, $model - массив с данными, которые вы указали 
в методе setupModel
10) В нужном месте вашего кода создаем экземпляр нашего созданного картридже, в конструктор передаем массив параметров,
который будет доступен через свойство $this->params (в нашем примере $docObj = new(\Document\Report\Doc(['testKey' => 'testValue'])))
11) Создаем документ менеджер: new SpiritOfWars\DocMVC\DocumentManager($docObj) . Вторым аргументом можно передать объект логгера
12) Собираем документ $docObj->build();
13) Теперь документ можно сохранить в какую-то конкретную папку, либо скачать: методы saveAs(), download()


**Несколько общих замечаний:**

 - У pdf-документов не поддерживаются шаблоны
 - Во время скачивания документа следите чтобы ничего лишнего не выводилось на страницу. Т.к. все это  тоже окажется в 
 нашем документе. Что может даже привести к его нечитаемости
 - По умолчанию имя скачиваемого документа будет вида timestamp().{extension}. Чтобы поменять его, 
 переопределите метод setupDocName(). Имя не должно содержать расширения файла.
 - Каждый класс имеет свое расширение документа по-умолчанию. Вы можете его изменить, переопределив метод setupFileExt.
 Однако расширение которое вы в нем укажете должно попадать в список разрешенных (метод allowedExt)
 - В папке /sample/ лежат примеры работы с документами
 - Если нужно пробрасывать данные из метода в метод, можно использовать свойство $this->commonData
 
 
 **Пример кода:**
 Генерируем docx документ для скачивания.
 
 ```php
 <?php
 
 // класс-картридж Doc.php, лежит в директории document/test/Doc.php
 
 use SpiritOfWars\DocMVC\Cartridge\DocCartridge;
 
 class Doc extends DocCartridge
 {
 
     public function setupView()
     {
         return 'doc/view.php';
     }
 
     public function setupModel()
     {
         $test = $this->params['test']; // required param
         $randParam = $this->params['test2'];
         return [
             'test' => $test,
             'randParam' => $randParam
         ];
     }
 
     public function setupRequiredParams()
     {
         return ['test'];
     }
 
     public function setupDocName()
     {
         return 'test-name';
     }
 }
 // конец файла
 
 
 // вью-файл view.php лежит в директории document/test/view/view.php
 
 $PHPWord = $driver;
 $data = $model;
 
 $PHPWord->addFontStyle('nStyle', array('size'=>10,'name'=>'Arial CYR'));
 $PHPWord->addParagraphStyle('pRight', array('align'=>'right','spaceAfter'=>0));
 $PHPWord->addParagraphStyle('pRightT', array('align'=>'right','spaceBefore'=>400,'spaceAfter'=>0));
 $PHPWord->addParagraphStyle('pRightB', array('align'=>'right','spaceAfter'=>400,'spaceAfter'=>0));
 $PHPWord->addParagraphStyle('pCenter', array('align'=>'center','spaceAfter'=>0));
 $PHPWord->addParagraphStyle('pLeft', array('align'=>'left','spaceAfter'=>0));
 $PHPWord->addParagraphStyle('pLeftT', array('align'=>'left','spaceBefore'=>400,'spaceAfter'=>0));
 
 $PHPWord->setDefaultFontName('Arial CYR');
 $PHPWord->setDefaultFontSize(10);
 
 
 $section = $PHPWord->createSection(array('marginLeft'=>1100, 'marginRight'=>1100, 'marginTop'=>1100, 'marginBottom'=>1100));
 $section->addText('Test text', 'nStyle', 'pRight');
 $section->addText('Test text №2', 'nStyle', 'pRight');
 
 $textrun = $section->createTextRun('pRight');
 $textrun->addText($data['test']);
 $textrun->addText($data['randParam']);
 
 //конец файла
 
 
 // создаем экземпляр класса, передаем параметры и скачиваем файл
 
 $testDoc = new Doc([
    'test' => 'test content',
    'randParam' => 'random param'
 ]);

 $documentManager = new DocumentManager($testDoc);

 $documentManager->build()->download();