<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" class="en-us">
<head>
	 <title>Undang-Undang Markup Language - Undang-Undang No.32 Tahun 2002</title>
	 <style>
	.list_indent {
		margin-left:48px;
	}
	.list_indent p {
		text-indent:-26px;
	}
p {
	font-family: 'Bookman Old Style';
	font-size: 14px;
	font-style: normal;
	font-variant: normal;
	font-weight: 400;
	line-height: 20px;
}
	 </style>
</head>
<body onload="window.print()">
<?php
$start = microtime(true);
include_once('class.MySQL.php');
$oMySQL = new MySQL(); 
$oMySQL->ExecuteSQL("SELECT * FROM `judul` WHERE `id_dokumen` = 'uu-32-2002'");
$results = $oMySQL->ArrayResults(); 
foreach ($results as &$result) {
	echo "<CENTER><p><b>UNDANG-UNDANG REPUBLIK INDONESIA <br/>NOMOR ".$result['nomor']." TAHUN ".$result['tahun']." <br/>TENTANG<br/> ".$result['nama_peraturan']."</b></p>";
	echo "<p>DENGAN RAHMAT TUHAN YANG MAHA ESA</p></CENTER>";
}

$oMySQL->ExecuteSQL("SELECT * FROM `pembukaan` WHERE `id_dokumen` = 'uu-32-2002'");
$results = $oMySQL->ArrayResults(); 
foreach ($results as &$result) {
	$oMySQL->ExecuteSQL("SELECT * FROM `dasar_hukum` WHERE `id_pembukaan` = '".$result['id']."'");
	$results1 = $oMySQL->ArrayResults();
	echo "Menimbang:<ol type=a>";
	foreach ($results1 as &$result1) {
		echo "<li>".$result1['isi']."</li>";
	}
	echo "</ol>";
	$oMySQL->ExecuteSQL("SELECT * FROM `diktum` WHERE `id_pembukaan` = '".$result['id']."'");
	$results1 = $oMySQL->ArrayResults();
	echo "Mengingat:<ol type=1>";
	foreach ($results1 as &$result1) {
		echo "<li>".$result1['isi_diktum']."</li>";
	}
	echo "</ol>";
}

$oMySQL->ExecuteSQL("SELECT * FROM `batang_tubuh` WHERE `id_batang_tubuh_ref` IS NULL");
$columns1= $oMySQL->ArrayResults(); 
//Level 1 - Buku
if(count($columns1)>0){
	foreach ($columns1 as &$column1) {
		if ($column1['jenis']=='BAB' or $column1['jenis']=='BAGIAN') echo '<center><b>';echo "<p>".$column1['isi_batang_tubuh']."</p>\n";if ($column1['jenis']=='BAB' or $column1['jenis']=='BAGIAN') echo '</b></center>';
		$oMySQL->ExecuteSQL("SELECT * FROM `batang_tubuh` WHERE `id_batang_tubuh_ref` ='" . $column1['id'] . "'");
		$columns2= $oMySQL->ArrayResults();
		if(count($columns2)==0){
			continue;
		} 
		//Level 2 - Bab
		foreach ($columns2 as &$column2){
			if ($column2['jenis']=='AYAT') echo '<div class="list_indent">';if ($column2['jenis']=='BAB' or $column2['jenis']=='BAGIAN') echo '<center><b>';
			echo "<p>".$column2['isi_batang_tubuh']."</p>\n";
			if ($column2['jenis']=='AYAT') echo '</div>';if ($column2['jenis']=='BAB' or $column2['jenis']=='BAGIAN') echo '</b></center>';
			$oMySQL->ExecuteSQL("SELECT * FROM `batang_tubuh` WHERE `id_batang_tubuh_ref` ='" . $column2['id'] . "'");
			$columns3= $oMySQL->ArrayResults(); 
			if(count($columns3)==0){
				continue;
			}
			//Level 3 - Bagian
			foreach ($columns3 as &$column3){
				if ($column3['jenis']=='AYAT') echo '<div class="list_indent">';
				echo "<p>".$column3['isi_batang_tubuh']."</p>\n";
				if ($column3['jenis']=='AYAT') echo '</div>';
				$oMySQL->ExecuteSQL("SELECT * FROM `batang_tubuh` WHERE `id_batang_tubuh_ref` ='" . $column3['id'] . "'");
				$columns4= $oMySQL->ArrayResults(); 
				if(count($columns4)==0){
					continue;
				}
				//Level 4 - Pasal
				foreach ($columns4 as &$column4){
					if ($column4['jenis']=='AYAT') echo '<div class="list_indent">';
					echo "<p>".$column4['isi_batang_tubuh']."</p>\n";
					if ($column4['jenis']=='AYAT') echo '</div>';
					$oMySQL->ExecuteSQL("SELECT * FROM `batang_tubuh` WHERE `id_batang_tubuh_ref` ='" . $column4['id'] . "'");
					$columns5= $oMySQL->ArrayResults(); 
					if(count($columns5)==0){
						continue;
					}
					//Level 5 - Sub Pasal
					foreach ($columns5 as &$column5){
						if ($column5['jenis']=='AYAT') echo '<div class="list_indent">';
						echo "<p>".$column5['isi_batang_tubuh']."</p>\n";
						if ($column5['jenis']=='AYAT') echo '</div>';
						$oMySQL->ExecuteSQL("SELECT * FROM `batang_tubuh` WHERE `id_batang_tubuh_ref` ='" . $column5['id'] . "'");
						$columns6= $oMySQL->ArrayResults(); 
						if(count($column6)==0){
							continue;
						}
						//Level 6 - Ayat
						echo '<div class="list_indent">';
						foreach ($columns6 as &$column6){
							echo "<p>".$column6['isi_batang_tubuh']."</p>\n";
							$oMySQL->ExecuteSQL("SELECT * FROM `batang_tubuh` WHERE `id_batang_tubuh_ref` ='" . $column6['id'] . "'");
							$columns7= $oMySQL->ArrayResults(); 
							if(count($column7)==0){
								continue;
							}
							//Level 7 - Sub Ayat
							foreach ($columns7 as &$column7){
								echo "<p>".$column2['isi_batang_tubuh']."</p>\n";
							}
						echo "</div>";
						}
					}
				}
			}
		}
	}
}
$foo = (microtime(true) - $start);
echo "<p>Process on " . number_format((float)$foo, 2, '.', '') . " second.</p>";
$oMySQL->ExecuteSQL("SELECT * FROM 
(SELECT COUNT(`jenis`) AS `bab` FROM `batang_tubuh` WHERE `jenis` = 'BAB') aa,
(SELECT COUNT(`jenis`) AS `bagian` FROM `batang_tubuh` WHERE `jenis` = 'BAGIAN') bb,
(SELECT COUNT(`jenis`) AS `pasal` FROM `batang_tubuh` WHERE `jenis` = 'PASAL') cc,
(SELECT COUNT(`jenis`) AS `ayat` FROM `batang_tubuh` WHERE `jenis` = 'AYAT') dd;");
$results = $oMySQL->ArrayResults();
foreach ($results as &$result){
echo "<p>Statistic - Bab: ".$result['bab'].", Bagian: ".$result['bagian'].", Pasal: ".$result['pasal'].", Ayat: ".$result['ayat']."</p>";
}
?>
</body>
</html>