<?php
	
	function format_uang ($angka){
		return number_format($angka, 0, ',', '.');
	}

	function terbilang($angka)
	{
		$angka = abs($angka);
		$baca = array('', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas');
		$terbilang = '';

		if ($angka < 12) { // 1 -11
			$terbilang = '' .$baca[$angka];
		}elseif ($angka < 20) { // 12 - 19
			$terbilang = terbilang($angka - 10). 'belas';
		}elseif ($angka < 100) { // 20 - 99
			$terbilang = terbilang($angka / 10). 'puluh'. ' '. terbilang($angka % 10); //fungsi modulus
		}elseif ($angka < 200) { // 100 - 199
			$terbilang = 'seratus'.' '. terbilang($angka - 100);
		}elseif ($angka <  1000) { // 200 - 999
			$terbilang = terbilang($angka / 100). 'ratus'. ' '.terbilang($angka % 100);
		}elseif ($angka < 2000) { // 1000 - 1999
			$terbilang = 'seribu'.' '.terbilang($angka - 1000);
		}elseif (condition) {
			// code...
		}

		return $terbilang;
	}
?>