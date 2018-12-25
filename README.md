Библиотека предназначена для работы с файлами PDF, Excel, Word.
Представляет собой микро-фреймворк в mvc-модели

Для начала работы необходимо:
1) Создать общую директорию в которой все будет хранится. Я использую директорию с именем document
2) В ней создать директорию с произвольным именем. Тут каждая директория отвечает за свой документ.
3) В этой директории создать php файл с именем класса, по типу формата документа. 
Например мне нужно сделать документ финансового отчета в форматах doc и pdf.
В директории document/ я создам папку report/
В ней создам файлы doc.php и pdf.php
4) В каждом файле создаем класс который будет отвечать за генерацию документа и наследуем его от своего родителя,
т.е. doc от \DocMVC\src\Doc, pdf от \DocMVC\src\Pdf, excel от \DocMVC\src\Excel
5) В каждом файле есть 4 абстрактных метода которые обязательно нужно определить:
    - setupModel - должен вернуть массив данных которые будут использоваться при генерации документа и во вью файле
    - setupView - должен вернуть строку, содержащую путь и имя вью-файла. НЕ МОЖЕТ БЫТЬ ПУСТЫМ! Вью-файл обязательно 
    должен быть создан и указан путь к нему.
    - setupTemplate - должен вернуть строку, содержащую путь и имя шаблона. Если шаблона не будет, возвращаем null
    - setupRequiredParams - должен вернуть массив обязательных параметров, которые должны быть переданы в конструктор класса.
     Указываем только те параметры, без которых генерация документа невозможна. Если таких нет, возвращаем пустой массив.
6) Создаем директорию view (в нашем примере document/report/view/)
7) Создаем view-файл с любым именем (в нашем примере для pdf документа можно создать такой файл document/report/view/view-pdf.php)
8) Указываем в файле класса путь до вью-файла в методе setupView (в нашем случае: return 'view-pdf.php';).
ВАЖНО! Указываем не полный путь, а относительный от папки view.
9) Если есть шаблон, повторяем пункты 6,7,8 для шаблона. Создаваемая папка обязательна должна называться template. 
Путь до шаблона возвращаем методом setupTemplate. ВАЖНО! Указываем не полный путь, а относительный от папки template.
10) Во вью файле мы либо прописываем html верстку, либо выполняем php код по генерации контента. По-умолчанию доступны 
две переменные $driver - объект для работы с генерацией контента документа, $model - массив с данными, которые вы указали 
в методе setupModel
11) В нужном месте вашего кода создаем экземпляр нашего созданного класса, в конструктор передаем массив параметров,
который будет доступен через свойство $this->params (в нашем примере $docObj = new(\document\report\doc(['testKey' => 'testValue'])))
12) Чтобы скачать документ вызовите свойство download() (в нашем примере $docObj->download())



Несколько общих замечаний:

 - У pdf-документов не поддерживаются шаблоны, поэтому для pdf-классов метод setupTemplate всегда должен возвращать null
 - Во время скачивания документа следите чтобы ничего не выводилось на страницу, в т.ч. ошибки. Т.к. все что выводится 
 на странице будет поймано буфером и отправлено в документ. А это неизбежно приведет к его нечитаемости.
 - По умолчанию имя скачиваемого документа будет вида timestamp().{extension}. Чтобы поменять его, используйте 
 переопределите метод setupDocName(). Имя не должно содержать расширения файла.
 - Каждый класс имеет свое расширение документа по-умолчанию. Вы можете его изменить, переопределив метод setupFileExt.
 Однако расширение которое вы в нем укажете должно попадать в список разрешенных (метод allowedExt)
 - После того как работа скрипта завершена, сгенерированный на сервере документ будет удален через __destruct,
 если не указано иначе. Для сохранения документа на сервере вызовите метод saveOn()
 - В папке /sample/ лежат примеры работы с документами
 - Если нужно пробрасывать данные из метода в метод, можно использовать свойство $this->chosenParams
 
 
 Пример кода:
 Генерируем docx документ для скачивания.
 
 ```php
 <?php
 
 // файл класса doc.php, лежит в директории document/test/doc.php
 
 use \DocMVC\src\Doc as PDoc;
 
 class Doc extends PDoc
 {
 
     public function setupTemplate()
     {
         return null;
     }
 
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
 
 $testDoc->download();