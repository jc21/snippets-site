<?php

use yii\db\Migration;

class m150922_000000_initial extends Migration
{
    /**
     *
     */
    public function up()
    {
        $this->execute('CREATE TABLE `Bookmark` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `memberId` int(11) NOT NULL DEFAULT "0",
          `snippetId` int(11) NOT NULL DEFAULT "0",
          `createdTime` int(11) NOT NULL DEFAULT "0",
          `updatedTime` int(11) NOT NULL DEFAULT "0",
          PRIMARY KEY (`id`)
        )');

        $this->execute('CREATE TABLE `Language` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `slug` varchar(150) NOT NULL DEFAULT "",
          `name` varchar(30) NOT NULL DEFAULT "",
          `renderCode` varchar(20) DEFAULT NULL,
          `isHidden` tinyint(1) NOT NULL DEFAULT "0",
          `createdTime` int(11) NOT NULL DEFAULT "0",
          `updatedTime` int(11) NOT NULL DEFAULT "0",
          PRIMARY KEY (`id`)
        )');

        $this->execute('INSERT INTO `Language` (`id`, `slug`, `name`, `renderCode`, `isHidden`) VALUES
          ("php","PHP","php",0),
          ("javascript","Javascript","javascript",0),
          ("actionscript","Actionscript","actionscript",0),
          ("ada","Ada","ada",0),
          ("apache-log","Apache Log","apache",0),
          ("asm","ASM","asm",0),
          ("asp","ASP","asp",0),
          ("autoit","AutoIt","autoit",0),
          ("bash","Bash","bash",0),
          ("blitzbasic","BlitzBasic","blitzbasic",0),
          ("c","C","c",0),
          ("c_mac","C (Mac)","c_mac",0),
          ("caddcl","CAD DCL","caddcl",0),
          ("cadlisp","CAD Lisp","cadlisp",0),
          ("cfdg","CFDG","cfdg",0),
          ("coldfusion","Coldfusion","cfm",0),
          ("cpp","C++","cpp",0),
          ("csharp","C#","csharp",0),
          ("css","CSS","css",0),
          ("d","D","d",0),
          ("delphi","Delphi","delphi",0),
          ("diff","Diff","diff",0),
          ("div","DIV","div",0),
          ("dos","DOS","dos",0),
          ("eiffel","Eiffel","eiffel",0),
          ("fortran","Fortran","fortran",0),
          ("freebasic","FreeBasic","freebasic",0),
          ("gml","GML","gml",0),
          ("groovy","Groovy","groovy",0),
          ("html","HTML","html4strict",0),
          ("unoidl","Unoidl","idl",0),
          ("ini","Ini","ini",0),
          ("inno","Inno","inno",0),
          ("java5","Java 5","java5",0),
          ("java","Java","java",0),
          ("latex","LaTeX","latex",0),
          ("lisp","Lisp","lisp",0),
          ("lua","Lua","lua",0),
          ("matlab","Matlab M","matlab",0),
          ("mpasm","Microchip Assembler","mpasm",0),
          ("mysql","MySql","mysql",0),
          ("nsis","NSIS","nsis",0),
          ("objc","Objective C","objc",0),
          ("ocaml","OCaml","ocaml",0),
          ("oobas","OpenOffice.org Basic","oobas",0),
          ("oracle8","Oracle 8 SQL","oracle8",0),
          ("pascal","Pascal","pascal",0),
          ("perl","Perl","perl",0),
          ("python","Python","python",0),
          ("qbasic","QBasic/QuickBASIC","qbasic",0),
          ("msreg","Microsoft Registry","reg",0),
          ("robots","robots.txt","robots",0),
          ("ruby","Ruby","ruby",0),
          ("sas","SAS","sas",0),
          ("scheme","Scheme","scheme",0),
          ("sdlbasic","sdlBasic","sdlbasic",0),
          ("smalltalk","Smalltalk","smalltalk",0),
          ("smarty","Smarty","smarty",0),
          ("sql","SQL","sql",0),
          ("tcl","TCL","tcl",0),
          ("text","Text","text",0),
          ("thinbasic","thinBasic","thinbasic",0),
          ("tsql","T-SQL","tsql",0),
          ("visual-basic","Visual Basic","vb",0),
          ("vbnet","VB.net","vbnet",0),
          ("vhdl","VHDL","vhdl",0),
          ("visualfoxpro","Visual Fox Pro","visualfoxpro",0),
          ("winbatch","Winbatch","winbatch",0),
          ("xml","XML","xml",0),
          ("regular-expressions","Regular Expressions","javascript",0)');

        $this->execute('CREATE TABLE `Member` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `email` varchar(255) NOT NULL DEFAULT "",
          `password` varchar(255) NOT NULL DEFAULT "",
          `name` varchar(100) NOT NULL DEFAULT "",
          `createdTime` int(11) NOT NULL DEFAULT "0",
          `lastSeenTime` int(11) NOT NULL DEFAULT "0",
          `isActive` tinyint(1) NOT NULL DEFAULT "1",
          `isAdmin` tinyint(1) NOT NULL DEFAULT "0",
          `updatedTime` int(11) NOT NULL DEFAULT "0",
          `passwordResetKey` varchar(32) NOT NULL DEFAULT "",
          PRIMARY KEY (`id`)
        )');

        $this->execute('CREATE TABLE `Snippet` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `slug` varchar(150) NOT NULL DEFAULT "",
          `name` varchar(150) NOT NULL DEFAULT "",
          `description` text NOT NULL,
          `code` text NOT NULL,
          `languageId` int(11) NOT NULL DEFAULT "0",
          `memberId` int(11) NOT NULL DEFAULT "0",
          `views` int(11) NOT NULL DEFAULT "0",
          `isHidden` tinyint(1) NOT NULL DEFAULT "0",
          `createdTime` int(11) NOT NULL DEFAULT "0",
          `updatedTime` int(11) NOT NULL DEFAULT "0",
          `downloads` int(11) NOT NULL DEFAULT "0",
          PRIMARY KEY (`id`)
        )');

        $this->execute('CREATE TABLE `SnippetComment` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `memberId` int(11) NOT NULL DEFAULT "0",
          `snippetId` int(11) NOT NULL DEFAULT "0",
          `createdTime` int(11) NOT NULL DEFAULT "0",
          `comment` text NOT NULL,
          `updatedTime` int(11) NOT NULL DEFAULT "0",
          PRIMARY KEY (`id`)
        )');
    }


    /**
     *
     */
    public function down()
    {
        print 'This migration doesn\'t go down!' . PHP_EOL;
    }
}
