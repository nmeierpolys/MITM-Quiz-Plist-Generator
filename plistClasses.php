<?php
	
class Quiz
{
	public $questionSets = Array();
	public $name = "";
	
	function addQuestionSet($questionSet)
	{
		$this->questionSets[] = $questionSet;	
	}
	
	function buildString()
	{
		$outStr = "";
		$outStr .= "<key>" . $this->name ."</key>\n";
		$outStr .= "<dict>\n";
		
		foreach($this->questionSets as $key => $questionSet)
		{
			$questionSet->name = "Questions" . ($key+1);
			$outStr .= $questionSet->buildString();
		}
		
		$outStr .= "</dict>\n";	
		return $outStr;
	}
}
	
class QuestionSet
{
	public $questions = Array();
	public $name = "";
	
	function addQuestion($question)
	{
		$this->questions[] = $question;	
	}	

	function buildString()
	{
		$outStr = "";
		
		$outStr .= "	<key>" . $this->name ."</key>\n";
		$outStr .= "	<array>\n";
		
		foreach($this->questions as $question)
		{
			$outStr .= "		<dict>\n";
			$outStr .= $question->buildString();
			$outStr .= "		</dict>\n";
		}
		
		$outStr .= "	</array>\n";
		return $outStr;	
	}
}

class Question
{
	
	public $choiceStr;
	public $c1;
	public $c2;
	public $c3;
	public $c4;
	public $correct;
	
	function buildString()
	{	
		$outStr = "";
		switch($this->correct){
			case 1:
				$this->c1 = "*" . $this->c1;
				break;
			case 2:
				$this->c2 = "*" . $this->c2;
				break;
			case 3:
				$this->c3 = "*" . $this->c3;
				break;
			case 4:
				$this->c4 = "*" . $this->c4;
				break;
		}
		$outStr .= "			<key>Choices</key>\n";
		$outStr .= "			<array>\n";
		$outStr .= "				<string>" . $this->c1 ."</string>\n";
		$outStr .= "				<string>" . $this->c2 ."</string>\n";
		$outStr .= "				<string>" . $this->c3 ."</string>\n";
		$outStr .= "				<string>" . $this->c4 ."</string>\n";
		$outStr .= "			</array>\n";
		$outStr .= "			<key>Question</key>\n";
		$outStr .= "			<string>" . $this->choiceStr . "</string>\n";
		return $outStr;
	}
		
}

function genRandomString($length = 5) {
    $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
    $string = "";    
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters) - 1)];
    }
    return $string;
}

?>