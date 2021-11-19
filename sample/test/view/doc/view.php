<?php


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
$section->addText('Hizmet karşılığı ödeme sözleşmesinin', 'nStyle', 'pRight');
$section->addText('Eki №1', 'nStyle', 'pRight');

$textrun = $section->createTextRun('pRight');
$textrun->addText($data['test']);