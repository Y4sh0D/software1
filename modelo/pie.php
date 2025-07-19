<?php
	session_start();
 include("pChart/pData.class");
 include("pChart/pChart.class");
	 if(file_exists('pie.png')){
		unlink('pie.png');
		sleep(1);
	}
 	
	$idpaciente = $_SESSION['param_idpaciente'];
	$cnn = new mysqli("localhost","root","","bdic");
	if($cnn->connect_errno)
	{
		echo "No hay conexión: (" . $cnn->connect_errno . ") " . $cnn->connect_error;
	}
	$cnn->set_charset("utf8");
	$sql = "select resultado,count(*) as cantidad from atencion where idpaciente=$idpaciente group by resultado";
	$resultado=mysqli_query($cnn,$sql);
	$mresultado = array();
	$cantidad = array();
	$i=0;
	while($mostrar=mysqli_fetch_array($resultado)){
		$i++;
		$mresultado[] = $mostrar['resultado'];
		$cantidad[] = $mostrar['cantidad'];				
	}
 // Dataset definition
 if ($i>0){
 $DataSet = new pData;
 $DataSet->AddPoint($cantidad,"Serie1");
 $DataSet->AddPoint($mresultado,"Serie2");
 $DataSet->AddAllSeries();
 $DataSet->SetAbsciseLabelSerie("Serie2");

 // Initialise the graph
 $Test = new pChart(420,250);
 $Test->drawFilledRoundedRectangle(7,7,413,243,5,240,240,240);
 $Test->drawRoundedRectangle(5,5,415,245,5,230,230,230);
 $Test->createColorGradientPalette(195,204,56,223,110,41,5);

 // Draw the pie chart
 $Test->setFontProperties("Fonts/tahoma.ttf",8);
 $Test->AntialiasQuality = 0;
 $Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),180,130,110,PIE_PERCENTAGE_LABEL,FALSE,50,20,5);
 $Test->drawPieLegend(330,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);

 // Write the title
 $Test->setFontProperties("Fonts/MankSans.ttf",10);
 $Test->drawTitle(10,20,"Porcentaje de Diagnosticos",100,100,100);

 $Test->Render("pie.png");
 }
?>