<!--<?php
//error_reporting(E_ALL);
error_reporting(0);
?>-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="bg" dir="ltr"> <head profile="http://gmpg.org/xfn/11">
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>

<?php

echo 'Брой изпълнени задачи според вашето запитване: <br /><br />';

//отваряне на файла
$myfile = fopen("PHP-VERSION.txt", "r") or die("Unable to open file!");

//променливи
$blokove = 0; 		//общият брой на "блоковете"
$curr_line = 0; 	//текущ ред във файла
$curr_day = 0;		//текущ ден в блока
$end_of_block = 0;	//за по-лесно - запомняне къде е края на текущия блок
$temp = ''; 		//променлива за четене на редове, всеки ред се презаписва, за да не хабя 10 променливи
$temp_array = array(); //temp масив за изваждане на цифрите, когато са 2 на ред с интервал между тях, другия начин е със string и търсене на първото празно място
$dni = 0; 			//брой дни за смятане в текущия блок (10 в примера)
$smqtania = 0; 		//брой смятания за текущия блок (4 в примера)
$start = 0;			//начална дата за смятане
$end = 0;			//крайна дата за смятане
$suma = 0;			//сумата от изпълнените задачи за желания за смятане период

//масиви
//с $block[1] до $block[...] ще означа изпълнените задачи по дни за рекущия период ("блок"), не обичам от 0 да почва масива, обърква
$block = array();

while(!feof($myfile)) {
	$curr_line++;
	
	if ($curr_line == 1) {
	//първият ред в текста - броят на "блоковете" (периодите от време работа без отпуска)
		$blokove = fgets($myfile);
	};
	
	if ($curr_line == 2) {
		//Брой работни дни, брой сметнати периоди = колко реда напред смятам; къде свършва блока (ред2 + 10+4 = 16)
		$temp = fgets($myfile);
		$temp_array =  explode(" ", $temp); //прочитане на текущия ред, за да извадим 2-те цифри от него
		$dni = $temp_array[0];		//в примера това е 10 дни
		$smqtania = $temp_array[1]; //в примера това е 4 смятания
		$end_of_block = 2 + $dni + $smqtania; //10 + 4 + 2-та прочетени вече реда
		unset($temp_array); //нулиране на temp масив за изваждане на цифрите, когато са 2 на ред с интервал между тях
	};
	
	if (($curr_line > 2) AND ($curr_line <= 2 + $dni)) {
		//събиране на данните за дните
		$curr_day++; //увеличаване на текущия ден в блока, беше 0, сега е 1 и +
		//зареждане на числата в масива $block
		$block[$curr_day] = fgets($myfile); //с $block[1] до $block[...] .... не обичам от 0 да почва масива, обърква
	};
	
	if (($curr_line > 2 + $dni) AND ($curr_line <= $end_of_block)) {
		$suma = 0; //нулиране на сбора, за да започнем с новия период
		//събиране на данните за исканите периоди и смятане след това на резултатите
		$temp = fgets($myfile);
		$temp_array =  explode(" ", $temp);
		$start = $temp_array[0]; //първи ден от желания за смятане период
		$end = $temp_array[1];	 //брой дни от желания за смятане период
		//последният ден от периода за смятане ще намерим като $end-$ включително
		for ($i=$start; $i<=$end; $i++) { //смятане на броя изпълнени задачи през желания за смятане период
			$suma = $suma + $block[$i];
		};
		echo $suma , '<br />';
		unset($temp_array); //нулиране на temp масив за изваждане на цифрите, когато са 2 на ред с интервал между тях
	};	
	
	if ($curr_line == $end_of_block) { 
		//ако сме стигнали края на текущия блок, нулираме променливите и почваме следващия блок
		//нулирам ги, за да не забравя някоя и после да ми прави проблем. Когато е малка програмата, е лесно, но когато е огромна, е важно да не се забрави нещо, защото след това търсенето къде е, отнема много желано за сън време :P
		$curr_line = 1;	//нулиране на текущия ред - слагаме го да е 1 + 1 след това = 2, т.е. прескачаме четенето колко са блоковете
		$curr_day = 0;		//текущ ден в блока
		$temp = ''; 		//нулиране на променливата за четене на редове, всеки ред се презаписва, за да не хабя 10 променливи
		unset($block);		//нулиране на масива с числата изпълнени задачи по дните от текущия "блок" (период без отпуска)
		$dni = 0; 			//брой дни за смятане в текущия блок (10 в примера)
		$smqtania = 0; 		//брой смятания за текущия блок (4 в примера)
		$start = 0;			//начална дата за смятане
		$end = 0;			//крайна дата за смятане
		$suma = 0;			//сумата от изпълнените задачи за желания за смятане период
	};
	
};

//затваряне на файла
fclose($myfile);

?>

</body>
</html>