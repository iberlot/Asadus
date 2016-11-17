<?php
$fp = fopen ( "bases/Bebidas", "a+" );

while (!feof ( $fp ))
{
	$linea = fgets ( $fp );
	
	if (strncmp($linea, "#", 1 )!=0)
	{
		$datoBruto = explode ( '|', $linea );
		$dato [$datoBruto[0]] = $datoBruto;
	}
	

}

fclose ( $fp );

print_r ( $dato );

?>