<?php require_once('plistClasses.php') ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Monkey in the Middle Quiz Generator</title>
<style>
#content {
	margin-left:auto;
	margin-right:auto;
	width:800px;
	min-width:800px;
}

#right {
	right:0;
	width:20em;
	height:800px;
	top:50px;
	position:absolute;
	padding-top:6px;
	padding-right:10px;
	margin:10px;
	overflow: hidden;
}
</style>
<script language="javascript">

var questionCounter = new Array()
questionCounter[1] = 1;
var questionSetCounter = 1;

function init()
{
	addQuestionSet();
}

function findPos(obj) {
	var curtop = 0;
	if (obj.offsetParent) {
		do {
			curtop += obj.offsetTop;
		} while (obj = obj.offsetParent);
	return [curtop];
	}
}

function addQuestionSet()
{
	var setName='questionSet' + questionSetCounter;
	var formData = document.getElementById("form_data");
	var questionSet = document.createElement('DIV');
	var setTitleLabel = document.createTextNode('Question Set ' + questionSetCounter + ': ');
	questionCounter[setName] = 1;
	
	var addQuestionLink = document.createElement('a');
	addQuestionLink.setAttribute('href','#');
	addQuestionLink.setAttribute('onClick','addQuestion("' + setName + '")');
	addQuestionLink.innerHTML = 'Add a question to this set';
	
	questionSet.setAttribute('id',setName);
	
	questionSet.appendChild(document.createElement('HR'));
	questionSet.appendChild(setTitleLabel);
	questionSet.appendChild(addQuestionLink);
	formData.appendChild(questionSet);
	
	//Add a counter for this set
	var counterContainer = document.getElementById("counterContainer");
	var counterObj = document.createElement('INPUT');
	counterObj.setAttribute('id','questionCount' + setName);
	counterObj.setAttribute('type','hidden');
	counterObj.setAttribute('name','questionCount' + setName);
	counterContainer.appendChild(counterObj);
	
	questionSetCounter++;
	document.getElementById('questionSetCount').value = questionSetCounter;
	addQuestion(setName);
}

function addAnswer(listObj,selectField,number,letter,caller){
	var fieldName='question' + questionCounter[caller];
	var listEntry = document.createElement('LI');
	var input = document.createElement('INPUT');
	var label = document.createTextNode('Answer ' + number + ': ')
	var selectOption = document.createElement('OPTION');
	
	listObj.appendChild(listEntry);
	fieldName='question' + caller + questionCounter[caller] + letter;
	listEntry.appendChild(label)
	input.setAttribute('type', 'text');
	input.setAttribute('id', fieldName);
	input.setAttribute('name', fieldName);
	listEntry.appendChild(input);
	
	selectOption.setAttribute('value', number);
	selectOption.innerHTML = 'Answer ' + number;
	selectField.appendChild(selectOption);
}

function addQuestion(caller){
	questionCounter[caller]
	var base = document.getElementById(caller);
	if(base == null){
		base = document.getElementById('form_data');
	}
	
	var fieldName='question' + caller + questionCounter[caller];
	var listObj = document.createElement('UL')
	listObj.setAttribute('style', 'margin-top: 0px; padding-top:0px; margin-bottom: 0px');
	
	var input = document.createElement('INPUT');
	var label = document.createTextNode(questionCounter[caller] + '. Question: ')
	var table = document.createElement('TABLE');
	table.setAttribute('style', 'margin-left: 17px');
	
	var row = document.createElement('TR');
	var col1 = document.createElement('TD');
	var col2 = document.createElement('TD');
	var col3 = document.createElement('TD');
	col1.setAttribute('style', 'vertical-align: top;');
	col2.setAttribute('style', 'vertical-align: top;');
	col3.setAttribute('style', 'vertical-align: top;');
	
	
	var selectLabel = document.createTextNode('Correct Answer: ');
	var selectField = document.createElement('SELECT');
	var selectName='select' + caller + questionCounter[caller];
	selectField.setAttribute('id', selectName);
	selectField.setAttribute('name', selectName);
	
	base.appendChild(table)
	table.appendChild(row)
	row.appendChild(col1)
	row.appendChild(col2)
	row.appendChild(col3)
	input.setAttribute('type', 'text');
	input.setAttribute('id', fieldName);
	input.setAttribute('name', fieldName);
	col1.appendChild(label)
	col1.appendChild(input);
	
	addAnswer(listObj, selectField, "1", "a", caller);
	addAnswer(listObj, selectField, "2", "b", caller);
	addAnswer(listObj, selectField, "3", "c", caller);
	addAnswer(listObj, selectField, "4", "d", caller);
	col2.appendChild(listObj);
	
	col3.appendChild(selectLabel);
	col3.appendChild(selectField);
	
	questionCounter[caller]++;
	document.getElementById("questionCount" + caller).value = questionCounter[caller];
	
}
</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-25846706-1']);
  _gaq.push(['_setDomainName', 'endious.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>

<body onload="addQuestionSet()">
<div id="content">
<h1>Monkey in the Middle Quiz Generator</h1>
<img src="images/trythese.png" align="right"/>
<?php
	
	if(isset($_GET["submitplist"])){
		
	$header = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>';
		
		$footer = "</dict>\n</plist>";
		
		$questionSet = new QuestionSet();
		$quiz = new Quiz();
		
		echo '<b>' . $_GET['Title'] . '</b><br>';
		$questionSetCount = $_GET['questionSetCount'];
		for ($j=1; $j<$questionSetCount; $j++)
		{
			$baseName = 'questionSet' . $j;
			$questionSet = new QuestionSet();
			$questionCount = $_GET['questionCount' . $baseName];
			
			for ($i=1; $i<$questionCount; $i++)
			{
				$questionBaseName = 'question' . $baseName . $i;
				if(isset($_GET[$questionBaseName]))
				{				
					$question = new Question();
					$question->choiceStr = $_GET[$questionBaseName];
					if(isset($_GET[$questionBaseName . 'a'])){
						$question->c1 = $_GET[$questionBaseName . 'a'];
					} else {
						$question->c1 = "";
					}
					if(isset($_GET[$questionBaseName . 'b'])){
						$question->c2 = $_GET[$questionBaseName . 'b'];
					} else {
						$question->c2 = "";
					}
					if(isset($_GET[$questionBaseName . 'c'])){
						$question->c3 = $_GET[$questionBaseName . 'c'];
					} else {
						$question->c3 = "";
					}
					if(isset($_GET[$questionBaseName . 'd'])){
						$question->c4 = $_GET[$questionBaseName . 'd'];
					} else {
						$question->c4 = "";
					}
					$question->correct = 1;
					$questionSet->addQuestion($question);
					
					if(isset($_GET['select' . $baseName . $i]))
					{
						$question->correct = $_GET['select' . $baseName . $i];
					}
					
				}
			}
			
			$quiz->addQuestionSet($questionSet);
		}
		$quiz->name = $_GET['Title'];
		$finalOutput = "";
		$finalOutput .= $header;
		$finalOutput .= "\n";
		$finalOutput .= $quiz->buildString();
		$finalOutput .= $footer;
		$randString = genRandomString();
		$quizFileName = "plists/" . $randString . ".plist";
		$ourFileHandle = fopen($quizFileName, 'w') or die("can't open file");
		fwrite($ourFileHandle, $finalOutput);
		addPlistLink($randString, $quiz->name);
		echo "File " . $quizFileName . " successfully generated.<br><a href='" . $quizFileName . "'>Generated file</a>";
		echo "<hr><br>";
	}
	showPlistStore();
?>
<p>This site generates a plist quiz file for use with the <a href="http://www.monkeyinthemiddleapps.com">Monkey in the Middle</a> iPhone game.</p>
<p>Enter a Title for your quiz and any number of questions below. Clicking the Submit button will generate a plist file which can be used in the game.</p>
<br />
<form action="index.php" method="get">
Title: <input name="Title" id="Title" type="text" size="50"/>&nbsp;&nbsp;&nbsp;
<a href="#" onclick="addQuestionSet()">Add a question set</a>
<br />
<div  id="form_data"></div>
<div id="counterContainer"></div>
<input name="questionSetCount" id="questionSetCount" type="hidden" />
<button name="submitplist" id="submitplist" type="submit">Submit</button>&nbsp;&nbsp;&nbsp;
<a href="#" onclick="addQuestionSet()">Add a question set</a>
</form>
</div>
</body>
</html>
<?php
function showPlistStore(){
	echo "<div id='right'>";
	echo "<h2>Available Quizzes:</h2>\n";
	$fileName = "plists/plistStore.txt";
	$fileHandle = fopen($fileName, 'r+') or exit;
	$plistBase = "plists/";
	
	//Output a line of the file until the end is reached
	$i = 1;
	while(!feof($fileHandle))
	{  
		$line = fgets($fileHandle);
		$path = $plistBase . substr($line,0,5) . ".plist";
		$name = substr($line,6,strlen($line));
		echo $i . ". <a href='" . $path . "'>" . $name . "</a>\n";
		echo '<br>';
		$i++;
	}
	
	echo "</div>";
	fclose($fileHandle);
}

function addPlistLink($code, $title){
	if($title == ""){
		$title = "Untitled quiz";
	}
	if($code == ""){
		return;
	}
	$fileName = "plists/plistStore.txt";
	$fileHandle = fopen($fileName, 'r+') or exit;
	$plistBase = "plists/";
	$insertText = "\n" . $code . "|" . $title;
	file_put_contents( $fileName , $insertText, FILE_APPEND);
	fclose($fileHandle);
}
?>