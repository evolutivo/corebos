<?php
$mod = "MODULE";
$block = Vtiger_Block::getInstance('BLOCK', $mod);
$field = new Vtiger_Field();
$field->name = 'NAME';
$field->label = 'LABEL';
$field->column = 'NAME';
$field->table = 'TABLE';
$field->columntype = 'TYPE';
$field->typeofdata = 'TOFDATA';
$field->uitype = 'UITYPE';
//other options
$block->addField($rolefield);