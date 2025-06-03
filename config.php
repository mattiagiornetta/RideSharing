<?php

	function getConfiguration ($connfFilePath)
	{
		$conf_file = fopen ($connfFilePath, "r") or die ('Errore lettura file configurazione');

		while (!feof($conf_file))
		{
			$conf_line = fgets($conf_file);
			
			$conf_splitted = explode("=", $conf_line);

			if(count($conf_splitted, COUNT_NORMAL) == 2)
			{
				$configs[trim($conf_splitted[0])] = trim($conf_splitted[1]);
			}
		}

		fclose($conf_file);

		return $configs;
	}
