
<div class='card text-xs'>
	<div class='card-header'>
		<h3 class='card-title'>
			Histori Pembimbingan Skripsi
		</h3>
	</div>

	<div class='card-body table-responsive'>
<?php
	$qp = mysqli_query($con,"SELECT ijazah.*,mhs.name,mhs.prodi,trakm.ta FROM ijazah,mhs,trakm WHERE mhs.nim=trakm.nim AND trakm.status='L' AND mhs.nim=ijazah.nim GROUP BY mhs.nim ORDER BY trakm.ta DESC,mhs.prodi,mhs.name ");
?>

				<table id='example1' class='table table-bordered'>
					<thead>
					<tr class='bg-info'>
						<td>NO</td>
						<td>PRODI</td>
						<td>NIM</td>
						<td>NAMA LENGKAP</td>
						<td>PEMBIMBING</td>
						<td>SMT LULUS</td>
						<td>JUDUL SKRIPSI</td>
						<td>REPOSITORI</td>
					</tr>
					</thead>
					<tbody>
<?php
				$no=1;
				while($p=mysqli_fetch_array($qp)) {
					$name = strtoupper($p[name]);
					$prodi = mysqli_fetch_array(mysqli_query($con,"SELECT singkatan FROM prodi WHERE kode='$p[prodi]' "));
					$pembimbing = mysqli_fetch_array(mysqli_query($con,"SELECT name_glr FROM dosen WHERE nik='$p[pembimbing]' "));
										
					echo"

					<tr>
						<td>$no</td>
						<td>$prodi[singkatan]</td>
						<td>$p[nim]</td>
						<td>$name</td>
						<td>$pembimbing[name_glr]</td>
						<td>$p[ta]</td>
						<td>$p[judul]</td>
						<td><a href='http://repo.upmk.ac.id/detail.php?id=$p[nim]' target='_blank'><i class='fa fa-file'></i> File</a></td>
					</tr>

					";
					$no++;
				}


?>
					</tbody>					
				</table>

	</div>
</div>
