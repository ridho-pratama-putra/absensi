<div class="container">
 	<ol class="breadcrumb">
	  <li><a href="<?php echo base_url('User_C')?>" class='active' >User</a></li>
	  <li class='active'>overview</li>
	</ol>
	
	<div class="row">
		<?php 		$date = date('m');	?><br>
		<?=$this->session->flashdata("alert_delete_absensi_ku");?>
		<?=$this->session->flashdata("alert_update_absensi_ku");?>
		<?=$this->session->flashdata("alert_update_ijin_ku");?>
		<?=$this->session->flashdata("alert_delete_ijin_ku");?>
		<div class="col-xs-12 col-sm-8">
			<h3>Anda memiliki sisa cuti <?= $date - ($cuti[0]->cuti_berapakali);?></h3>
			<h5>Anda telah cuti sebanyak <?= $cuti[0]->cuti_berapakali;?></h5>
		</div>
	
		<div class="col-xs-12 col-sm-2">
			<div class="dropdown">
				<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
				    Dropdown
				    <span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
					<li><a href="<?php echo base_url('User_C/detail_per_user_per_bulan/'.$siapa.'/01/'.date('Y'))?>">Januari</a></li>
					<li><a href="<?php echo base_url('User_C/detail_per_user_per_bulan/'.$siapa.'/02/'.date('Y'))?>">Februari</a></li>
					<li><a href="<?php echo base_url('User_C/detail_per_user_per_bulan/'.$siapa.'/03/'.date('Y'))?>">Maret</a></li>
					<li><a href="<?php echo base_url('User_C/detail_per_user_per_bulan/'.$siapa.'/04/'.date('Y'))?>">April</a></li>
					<li><a href="<?php echo base_url('User_C/detail_per_user_per_bulan/'.$siapa.'/05/'.date('Y'))?>">Mei</a></li>
					<li><a href="<?php echo base_url('User_C/detail_per_user_per_bulan/'.$siapa.'/06/'.date('Y'))?>">juni</a></li>
					<li><a href="<?php echo base_url('User_C/detail_per_user_per_bulan/'.$siapa.'/07/'.date('Y'))?>">juli</a></li>
					<li><a href="<?php echo base_url('User_C/detail_per_user_per_bulan/'.$siapa.'/08/'.date('Y'))?>">agustus</a></li>
					<li><a href="<?php echo base_url('User_C/detail_per_user_per_bulan/'.$siapa.'/09/'.date('Y'))?>">september</a></li>
					<li><a href="<?php echo base_url('User_C/detail_per_user_per_bulan/'.$siapa.'/10/'.date('Y'))?>">oktober</a></li>
					<li><a href="<?php echo base_url('User_C/detail_per_user_per_bulan/'.$siapa.'/11/'.date('Y'))?>">november</a></li>
					<li><a href="<?php echo base_url('User_C/detail_per_user_per_bulan/'.$siapa.'/12/'.date('Y'))?>">desember</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

	<div class="container">
		<h3>Absensi</h3>
		<div class="table-responsive">
	  		<table class="table  table-condensed">
	  			<thead>
	        		<tr>
		            	<th>id</th>
		            	<th>tanggal</th>
		            	<th>jam</th>
		            	<th>keterangan</th>
		            	<th>detail</th>
		            	<th>acc</th>

		            	<th>action</th>
	            	</tr>
	        	</thead>
		        <tbody>
		        	<?php
		        		$ontime=0;$late=0;$sick=0;$other=0;
						foreach ($absen as $key){
			        		echo "<tr>";
			        		echo "<td>".$key->id_A."</td>";
			        		echo "<td>".$key->tanggal."</td>";
			        		echo "<td>".$key->jam."</td>";
			        		echo "<td>".$key->keterangan_s."</td>";
			        		echo "<td>".$key->detail."</td>";
			        		if ($key->keterangan_s =='hadir' AND $key->detail ='tepat waktu') {
			        			$ontime = $ontime + 1 ;
			        		}elseif($key->keterangan_s =='hadir' AND $key->detail ='telat') {
			        			$late = $late + 1;
			        		}elseif($key->keterangan_s =='sakit') {
			        			$sick = $sick + 1;
			        		}elseif ($key->keterangan_s =='ijin 1 hari' or $key->keterangan_s =='alpha') {
								$other = $other +1;
			        		}
			        		
			        		echo "<td>".$key->acc."</td>";
			        		echo "<td> 
			        			<a href='".base_url()."User_C/delete_absensi_ku/".$key->id_A."/".$key->id_k."'><span class='glyphicon glyphicon-trash'></span></a>
			        			<a href='".base_url()."User_C/edit_absensi_ku/".$key->id_A."/".$key->id_k."'><span class='glyphicon glyphicon-edit'></span></a>
			                  </td>";
			        		echo "</tr>";
		        		}
		        	?>
		        </tbody>
	  		</table>
		</div>
		<br>

		
		
		<br>
		<h3>Ijin</h3>
		<div class="table-responsive">
	  		<table class="table  table-condensed">
	  			<thead>
	        		<tr>
		            	<th>id i</th>
		            	<th>urusan</th>
		            	<th>start</th>
		            	<th>end</th>
		            	<th>tanggal</th>
		            	<th>action</th>
	            	</tr>
	        	</thead>
		        <tbody>
		        	<?php
		        		// print_r($absen);
						foreach ($ijin as $kuy){
			        		echo "<tr>";
			        		echo "<td>".$kuy->id_i."</td>";
			        		echo "<td>".$kuy->perihal."</td>";
			        		echo "<td>".$kuy->start."</td>";
			        		echo "<td>".$kuy->end."</td>";
			        		echo "<td>".$kuy->tanggal."</td>";
			        		echo "<td> 
			        			<a href='".base_url()."User_C/delete_ijin_ku/".$kuy->id_i."/".$key->id_k."'><span class='glyphicon glyphicon-trash'></span></a>
			        			<a href='".base_url()."User_C/edit_ijin_ku/".$kuy->id_i."/".$key->id_k."'><span class='glyphicon glyphicon-edit'></span></a>
			                  </td>";
			        		echo "</tr>";
		        		}
		        	?>
		        </tbody>
	  		</table>
		</div>
		<div>
			<button type="button" class="btn btn-xs btn-success">hadir tepat waktu: <?= $ontime?> kali</button>
			<button type="button" class="btn btn-xs btn-danger">hadir terlambat: <?= $late?> kali</button>
			<button type="button" class="btn btn-xs btn-primary">sakit: <?=$sick?> kali</button>
			<button type="button" class="btn btn-xs btn-info">lainnyat: <?=$other?> kali</button>
			<!-- <button type="button" class="btn btn-xs btn-warning">hadir terlambat: 5</button>
			<button type="button" class="btn btn-xs btn-default">hadir terlambat: 5</button> -->
		</div><br>
		<div class="col-xs-12 col-sm-2">
			<a class="btn btn-default" href="<?php echo base_url('User_C/lihat5bulan/'.$siapa."/".date('n')."/".$tahun)?>" role="button">grafik 5 bulan terakhir</a>
		</div>

		<br><?php
		if ($data_chart != array()) {?>
			<div class="good" id="good" style="min-width: 310px;max-width: 100%;height: 400px;margin: 0 auto;"></div>
			<script type="text/javascript">
				$(function () { 
				    var myChart = Highcharts.chart('good', {

					    title: {
					        text: 'Grafik Hadir'
					    },
					    
					    xAxis: {
					    	type: 'category'
					    },

					    series: [{
					    	data: <?=$data_chart?>
					    }]

					});
				});
			</script>
			
		<?php }
		?>

	</div>
	