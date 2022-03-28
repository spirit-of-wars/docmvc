The library is designed to work with files PDF, Excel, Word.
This one is micro framework about mvc-model

**General provisions:**
- To get started you need create instance DocumentManager then download class-cartridge for him
- Working with data, customizing paths and required parameters must be done only in class-cartridge
- There are two ways to generate a document: render document from scratch (using view), render document from existed document (template)
- Document rendering logic must be written only in the view file (view)
- External dependencies are used to generate view-logic. The processors object of these libraries is available in
view file via a variable $driver  
       - Doc/Docx - phpoffice/phpword  
       - Excel - phpoffice/phpspreadsheet  
       - Pdf - dompdf/dompdf
- Template is a blank document that does not yet contain the necessary data, but they will be substituted by the
generation process. Such data is defined only in the model. (Example: To generate an excel document, we created a 
template.xlsx blank then inside in curly brackets we specify variables from where to take values)


**To get started you need:**
1) Create a shared directory where everything will be stored. I am using a directory called 'document'
2) Inside this one you need create one more directory (arbitrary name). Now each such directory will be responsible for 
its own document.
3) In this directory you need to create classes-cartridges.
For example, I need to make a financial report document in doc and pdf formats.
For this i will create directory Report/ in the directory Document/
In the Document/ directory i will create two files: Doc.php (extended from SpiritOfWars\DocMVC\Cartridge\DocCartridge) 
and Pdf.php (SpiritOfWars\DocMVC\Cartridge\PdfCartridge)
4) Each cartridge have 1 abstract method, that must be defined:
    - setupView - must be return string, that contains name and path from view-file. _CANNOT BE EMPTY!_ View file required
must be created and the path to it must be specified
   Also each cartridge have 3 optional methods:
    - setupModel - must be return array data. This data will be use in view-file or filling template variables
    - setupTemplate - must be return string, that contains name and path from template-file. (To pdf-cartridge is not available)
    - setupRequiredParams - must be return array of required params, that you want to pass to constructor.
     We specify only those parameters, without which the generation of the document is impossible. If there are none, 
do not touch this method
5) Create directory view/ (in my example: Document/Report/view/)
6) Create view-файл with any name (in my example: i created Document/Report/view/view-pdf.php to pdf document)
7) Set path to view-file in the cartridges method setupView (in my example: return 'view-pdf.php';).
Caution! Path must be relative.
8) If you need generating by template, just repeat steps 6,7,8 for template names. Directory for created must be named template/. 
Set path to template-document in the cartridges method setupTemplate. Caution! Path must be relative.
9) The view file is intended for html layout, or its generation by $driver variable. Available by default
two variables $driver - an object to work with the generation of document content, $model - an array with the data you 
specified in method setupModel
10) In the right place in your code, we create an instance of our created cartridge, pass an array of parameters to the constructor,
which will be available through the property $this->params (in my example $docObj = new(\Document\Report\Doc(['testKey' => 'testValue'])))
11) Create instance of DocumentManager: new SpiritOfWars\DocMVC\DocumentManager($docObj) . Optional: logger object can 
be passed as second parameter
12) Build document $docObj->build();
13) Now the generated document available for download or saving to directory. Methods: saveAs(), download()


**A few important notes:**

 - Templates are not available for pdf documents
 - While downloading the document, make sure that nothing extra is displayed on the page. Because all this will also be
generated document. Which can even lead to its unreadability
 - By default, the name of the downloaded document will be of the form timestamp().{extension}. You need override method 
 setupDocName() to change name. The name must not contain a file extension.
 - Each class has its own default document extension. You can change it by overriding method setupFileExt.
 However, the extension you specify in it must be on the list of allowed (method allowedExt)
 - The /sample/ folder contains examples of working with documents
 - If you need to pass data from a method to a method, you can use the $this->commonData property
 
 
 **Code Examples:**
 Generating docx-document for download
 
 ```php
 <?php
 
 // class-cartridge Doc.php, is in the directory document/test/Doc.php
 
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
 //end of the file
 
 
 // view-file view.php is in the directory document/test/view/view.php
 
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
 
 //end of the file
 
 
 // creating instance of class, passing params for it then download result document
 
 $testDoc = new Doc([
    'test' => 'test content',
    'randParam' => 'random param'
 ]);

 $documentManager = new DocumentManager($testDoc);

 $documentManager->build()->download();