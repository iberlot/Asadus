<?php

function armarArray($file, $usarId = true)
{
	$fp = fopen ($file, "a+");
	$e = 0;
	while (!feof ($fp))
	{
		$linea = fgets ($fp);
		
		if (strncmp ($linea, "#", 1) != 0)
		{
			$datoBruto = explode ('|', $linea);
			
			if ($usarId == true)
			{
				$dato[$datoBruto[0]] = $datoBruto;
			}
			else
			{
				$dato[$e] = $datoBruto;
			}
		}
		$e ++;
	}
	
	fclose ($fp);
	
	return $dato;
}

function armarSelect($file, $name, $selected = "", $clases = "")
{
	$dato = armarArray ($file);
	
	$select = "<SELECT NAME='" . $name . "'>";
	$select .= "<OPTION VALUE=''>-</OPTION>";
	
	for($i = 0; $i <= count ($dato); $i ++)
	{
		if ($dato[$i][0] != "")
		{
			if ($dato[$i][0] != $selected)
			{
				$select .= "<OPTION VALUE='" . $dato[$i][0] . "'>" . $dato[$i][1] . "</OPTION>";
			}
			else
			{
				$select .= "<OPTION VALUE='" . $dato[$i][0] . "' selected='selected'>" . $dato[$i][1] . "</OPTION>";
			}
		}
	}
	$select .= "</SELECT>";
	
	return $select;
}

$selectTipo = armarSelect ("bases/TipoDeAsado", "tipo", $_POST['tipo']);

$selectNivel = armarSelect ("bases/Niveles", "nivel", $_POST['nivel']);

$selectClima = armarSelect ("bases/TemperturaClima", "clima", $_POST['clima']);

$selectAlcohol = armarSelect ("bases/NivelDeAlcohol", "alcohol", $_POST['alcohol']);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Asadus</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function()
	{
		$('#hombres').change(function() 
		{
			$('#valorhombres').val($(this).val());
		});
		$('#mujeres').change(function() 
		{
			$('#valormujeres').val($(this).val());
		});
		$('#ninios').change(function() 
		{
			$('#valorninios').val($(this).val());
		});
	});
</script>
</head>
<body>
	<div>
		<form action="" method="post">

			<Br />
			<label for="hombres">Hombres</label>
			<input type="range" name="hombres" id="hombres" min="0" max="50" step="1" value="<?php if(isset($_POST['valorhombres'])){ echo $_POST['valorhombres']; } else { echo "0"; } ?>">
			<input type="text" name="valorhombres" id="valorhombres" value="<?php if(isset($_POST['valorhombres'])){ echo $_POST['valorhombres']; } else { echo "0"; } ?>" style="width: 2Em; border: none">

			<Br />
			<label for="mujeres">Mujeres</label>
			<input type="range" name="mujeres" id="mujeres" min="0" max="50" step="1" value="<?php if(isset($_POST['valormujeres'])){ echo $_POST['valormujeres']; } else { echo "0"; } ?>">
			<input type="text" name="valormujeres" id="valormujeres" value="<?php if(isset($_POST['valormujeres'])){ echo $_POST['valormujeres']; } else { echo "0"; } ?>" style="width: 2Em; border: none">

			<Br />
			<label for="ninios">Niños</label>
			<input type="range" name="ninios" id="ninios" min="0" max="50" step="1" value="<?php if(isset($_POST['valorninios'])){ echo $_POST['valorninios']; } else { echo "0"; } ?>">
			<input type="text" name="valorninios" id="valorninios" value="<?php if(isset($_POST['valorninios'])){ echo $_POST['valorninios']; } else { echo "0"; } ?>" style="width: 2Em; border: none">

			<Br />
			<?php echo $selectTipo;?>	
			<Br />
			<?php echo $selectNivel;?>
			<Br />
			<?php echo $selectClima;?>
			<Br />
			<?php echo $selectAlcohol;?>
			<Br />
			<Br />

			<input type="submit">
		</form>
	</div>
	<Br />
	<Br />
	<Br />
	<div>
	<?php
	
	if ($_POST)
	{
		$cantHombres = $_POST['valorhombres'];
		$cantMujeres = $_POST['valormujeres'];
		$cantNinios = $_POST['valorninios'];
		
		$tipo = $_POST['tipo'];
		$tipo = $tipo + 1;
		$nivel = $_POST['nivel'];
		$clima = $_POST['clima'];
		$alcohol = $_POST['alcohol'];
		
		$carnes = armarArray ("bases/Carnes");
		$categoriasCarnes = armarArray ("bases/CategoriasCarnes");
		$tiposCarne = armarArray ("bases/TiposCarne");
		$RelacionComidas = armarArray ("bases/RelacionComidas", false);
		
// 		print_r ($RelacionComidas);
		
		print_r ("<Br />");
		print_r ("<Br />");
		print_r ("<Br />");
		
		for($f = 0; $f <= count ($tiposCarne); $f ++)
		{
			if ($tiposCarne[$f][0] != "")
			{
				array_push ($tiposCarne[$f], 0);
// 				print_r ($tiposCarne[$f]);
// 				print_r ("<Br />");
			}
		}
		
		for($e = 0; $e <= count ($RelacionComidas); $e ++)
		{
			if (($RelacionComidas[$e][0] == "0") and ($RelacionComidas[$e][1] == $nivel))
			{
				for($f = 0; $f <= count ($tiposCarne); $f ++)
				{
					if ((($tiposCarne[$f][0] != "") and ($RelacionComidas[$e][3] != "")) and ($tiposCarne[$f][0] == $RelacionComidas[$e][2]))
					{
						array_push ($tiposCarne[$f], $RelacionComidas[$e][3]);
//  						print_r ($tiposCarne[$f]);
//  						print_r ("<Br />");
					}
				}
				
// 				print_r ($RelacionComidas[$f]);
// 				print_r ("<Br />");
			}
		}
		
		print_r ("<Br />");
		print_r ("<Br />");
		print_r ("<Br />");
		
		for($e = 0; $e <= count ($carnes); $e ++)
		{
			if (($carnes[$e][0] != "") and (strpbrk ($carnes[$e][4], $tipo) != ""))
			{
				$nombreCarne = $carnes[$e][1];
				$nombreCategoria = $categoriasCarnes[$carnes[$e][3]][1];
				$idTipoCategoria = $categoriasCarnes[$carnes[$e][3]][2];
				$idTipoCategoria = trim ($idTipoCategoria);
				$idTipoCarne = $tiposCarne[$idTipoCategoria][0];
				$nombreTipoCarne = $tiposCarne[$idTipoCategoria][1];
				
				for($f = 0; $f <= count ($tiposCarne); $f ++)
				{
					if (($tiposCarne[$f][0] != "") and ($tiposCarne[$f][0] == $idTipoCarne))
					{
						$tiposCarne[$f][2] = $tiposCarne[$f][2] + 1;
					}
				}
			}
		}

		for($e = 0; $e <= count ($carnes); $e ++)
		{
			if (($carnes[$e][0] != "") and (strpbrk ($carnes[$e][4], $tipo) != ""))
			{
				$nombreCarne = $carnes[$e][1];
				$nombreCategoria = $categoriasCarnes[$carnes[$e][3]][1];
				$idTipoCategoria = $categoriasCarnes[$carnes[$e][3]][2];
				$idTipoCategoria = trim ($idTipoCategoria);
				$idTipoCarne = $tiposCarne[$idTipoCategoria][0];
				$nombreTipoCarne = $tiposCarne[$idTipoCategoria][1];
				$cantidad = $tiposCarne[$idTipoCategoria][3]/$tiposCarne[$idTipoCategoria][2];
				
				$cantidadHombres = $cantidad*$cantHombres;
				$cantidadMujeres = $cantidad*$cantMujeres*0.8;
				$cantidadNinios = $cantidad*$cantNinios/2;
				
				$cantidadTotal = $cantidadNinios+$cantidadMujeres+$cantidadHombres;
				
				print_r ($e);
				print_r (" - ");
				print_r ($nombreCarne);
				print_r (" - ");
				print_r ($nombreCategoria);
				print_r (" - ");
				print_r ($idTipoCategoria);
				print_r (" - ");
				print_r ($nombreTipoCarne);
				print_r (" - ");
				print_r ($cantidad);
				print_r (" - ");
				print_r (number_format($cantidadTotal));
				
				// var_dump($tiposCarne);
				
				print_r ("<Br />");
			}
		}
	}
	?>
	
	</div>


</body>
</html>