<script type="text/javascript">
	$( document ).ready(function() {
		$("#freeform").on('submit', function(e){
			e.preventDefault();
			var url  = "<?=base_url('Home_C/create_ijin_free/')?>";
			$('#btn_free').text('creating...'); //change button text
		    $('#btn_free').attr('disabled',true); //set button disable
		    var formData = new FormData($('#freeform')[0]);
		    $.ajax({
		        url : url,
		        type: "POST",
		        data: formData,
		        contentType: false,
		        processData: false,
		        success: function(data)
		        {
		        	var object = JSON.parse(data);
		            $("#alert").html(object);
		            // console.log(data);
		            $('#btn_free').text('Submit'); //change button text
		            $('#btn_free').attr('disabled',false); //set button enable 
		            // $('#ijinFreeformModal').modal('hide');
		            show();
		        },
		        error: function (jqXHR, textStatus, errorThrown)
		        {
		            console.log(jqXHR, textStatus, errorThrown);
		            $('btn_free').text('eror'); //change button text
		            $('btn_free').attr('disabled',false); //set button enable 
		        }
		    });
		    show();
		});
	});
</script>
<div class="modal fade" id="ijinFreeformModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title" id="myModalLabel">ijin free form <?=date('d-F-Y')?></h4>
          	</div>
        	<form class="form-horizontal" method="POST" id="freeform">
          		<div class="modal-body">
          			<div id="alert-free"></div>	
          			<div class="form-group">
	              		<div class=" col-xs-12">
        					<h5 class="text-center"> *Atur value jam ijin secara manual. Pastikan jam awal dan jam akhir valid.</h5><br>
	                  		<select class="chosen-select" data-placeholder="Nama Karyawan" name="c_id_k" required="required" style="width: 100%">
						    <?php 
			            		foreach($nama_karyawan as $row)
					            {
					              	echo '<option value="'.$row->id_k.'">'.$row->nama_k.'</option>';
					            }
						    ?>
					        </select>
	              		</div>
          			</div>
              		<div class="form-group">
              			<div class="col-xs-12">
	              			<label class='control-label'>keterangan</label>
	                  		<textarea class="form-control" name="c_perihal"  style="min-height: 100px;" required="required"></textarea>
              			</div>
              		</div>
              		<div class='form-group'><div class='col-xs-12'>
                        <label class='control-label'>Jam Start</label>
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true" >
						    <input type="time" class="form-control" name="c_jam_start" id="clockstart" onchange="calc()" required="required">
						    <span class="input-group-addon">
						        <span class="glyphicon glyphicon-time"></span>
						    </span>
						</div>
						</div>
                    </div>
                    <div class='form-group'><div class='col-xs-12'>
                        <label class='control-label'>Jam End</label>
                        <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true" >
						    <input type="time" class="form-control" name="c_jam_end" onchange="calc()" id="clockend" required="required">
						    <span class="input-group-addon">
						        <span class="glyphicon glyphicon-time"></span>
						    </span>
						</div>
						</div>
                    </div>
                    <div class="form-group"><div class='col-xs-12'>
                        <label class="control-label">tanggal</label>
                        <input type="date" class="form-control" name="c_tanggal" required="required" value="<?=date("Y-m-d")?>">
                    </div></div>
					<div class="form-group">
						<div class="col-xs-6">
							<div class="input-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="c_kurangi" onchange="calc()" id="jam_istirahat">
										Kurangi jam istirahat
									</label>
								</div>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="input-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="c_kurangii" id="jam_setengah">
										Kurangi 1/2 jam
									</label>
								</div>
							</div>
						</div>
					</div>
					<!-- <div class="form-group"><div class="col-xs-12">
						<label class="sr-only" for="exampleInputAmount">Amount (in dollars)</label>
						<div class="input-group">
							<div class="input-group-addon">Rp</div>
							<input type="text" class="form-control" id="biaya"  placeholder="Amount" disabled="disabled">
							<div class="input-group-addon">.00</div>
						</div>
						</div>
					</div> -->

	          	</div>
	          	<div class="modal-footer">
	            	<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
	            	<button type="submit" class="btn btn-primary"  id="btn_free">Submit</button>
	          	</div>
        	</form>
        </div>
    </div>
</div>
<script type="text/javascript">
	function diff_minutes(dt1, dt2){
		var diffm =(dt2.getTime() - dt1.getTime()) / 1000;
		// var diffh =(dt2.getHours() - dt1.getHours());
		diffm /= 60;
		
		var  menit= (Math.round(diffm));
		// var  menit= Math.ceil((Math.round(diffm)));
		// var nominal = Math.ceil((Math.round(diffm)) / 60 );
		// var result = [ Math.abs(diffh),nominal];
		return menit;
	}

	function calc() {
		var date = <?=date('Y-m-d') ?>;
		var start = document.getElementById('clockstart');
		var manipulate_start = start.value +':00';
		var end =  document.getElementById('clockend');
		var manipulate_end = end.value +':00';
		var jam_pulang = "<?=$jam_pulang?>";
		var jam_masuk = "<?=$jam_masuk?>";
		if (manipulate_start > jam_pulang || manipulate_start < jam_masuk) {
			// console.log(manipulate_start, manipulate_end);
			document.getElementById('alert-free').innerHTML='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <strong>Diluar jam kerja.</strong></div>';
			start.value = '';
			end.value = '';
		}
		else{
			if (manipulate_end < manipulate_start) {
				end.value = '';
				document.getElementById('alert-free').innerHTML='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <strong>Jam tidak valid.</strong></div>';
				document.getElementById('biaya').value = '';
			}
			else if (end.value != '') {
				if (manipulate_end >jam_pulang) {
					document.getElementById('alert-free').innerHTML='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <strong>End lebih dari jam pulang.</strong></div>';
					document.getElementById('biaya').value = '';
					end.value='';
				}else{
					dt1 = new Date(date+' '+manipulate_start);
					dt2 = new Date(date+' '+manipulate_end);

					var result = diff_minutes(dt1, dt2);
					if (result < 30) {
						// console.log(result);
						end.value = '';
						start.value = '';
						document.getElementById('alert-free').innerHTML='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <strong>kurang dari 30 menit.</strong> tidak dihitung</div>';
						document.getElementById('biaya').value = '';
					}
					// else if(result >= 30){
					// 	// console.log(result);
					// 	// var biaya;
					// 	// if (document.getElementById('jam_istirahat').value == 1) {
					// 	// 	biaya = Math.ceil(result / 60) - 1;
					// 	// }
					// 	// else if(document.getElementById('jam_setengah').value == 1){
					// 	// 	biaya = Math.ceil(result / 60) - 0.5;
					// 	// }
					// 	// else{
					// 	// 	biaya = Math.ceil(result / 60);
					// 	// }
					// 	var biaya = Math.ceil(result / 60) * <?=$denda_ijin?>;
					// 	document.getElementById('biaya').value = biaya;
					// }
					// else{
					// 	console.log(result);
					// 	end.value = '';
					// 	start.value = '';
					// 	document.getElementById('alert-free').innerHTML='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <strong>not handled.</strong></div>';	
					// 	document.getElementById('biaya').value = '';
					// }
				}
			}
		}
	}
</script>


<script type="text/javascript">
	$('#ijinFreeformModal').on('shown.bs.modal', function () {
		$('.chosen-select').chosen("destroy");
		$('.chosen-select').chosen();
    	$('#clockstart').clockpicker({placement: 'top',donetext: 'Done'});
    	$('#clockend').clockpicker({placement: 'top',donetext: 'Done'});
	});
</script>

<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<h3 class="text-center">Perijinan</h3>
			<h6 class="text-center">*jika ijin seharian, inputkan start dengan nilai jam masuk dan input end dengan nilai jam pulang</h6>
			<h6 class="text-center">*jangan lupa mengakhiri ijin dengan menekan tombol selesai</h6>
		</div>
		<div class="col-xs-12">
			<button type="button" class="btn btn-primary col-xs-12" data-toggle="modal" data-target="#ijinFreeformModal">Ijin Free form</button>
		</div>
	</div><br>
	<div class="row">
		<div class="col-xs-12" id="alert">
		</div>
	</div><br>
	<div class="panel panel-default" style="margin-top: 20px;">
	  	<div class="panel-body">
		  	<div class="row">
		  		<form  method="POST" id="form-ijin">
					<div class="form-group col-xs-12" >
				        <select class="chosen-select" data-placeholder="Nama Karyawan" tabindex="2" style="width: 100%" name="c_id_k" required >
					    <?php 
		            		foreach($nama_karyawan as $row)
				            {
				              	echo '<option value="'.$row->id_k.'">'.$row->nama_k.'</option>';
				            }
					    ?>
				        </select>
					</div>
					<div class="form-group col-xs-12">
						<textarea class="form-control" name="c_perihal" value="<?php echo set_value('c_perihal'); ?>" style="min-height: 100px;" required></textarea>
					</div>
					<div class="col-xs-12">
						<a class="btn btn-primary" onclick="submit()" id="start-ijin">Start ijin</a>
					</div>
				</form>
		  	</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="table-responsive">
  		<table class="table  table-condensed" id="tabel">
				<thead>
					<tr>
						<th>id</th>
						<th>nama</th>
						<th>tanggal</th>
						<th>urusan</th>
						<th>start</th>
						<th>finish</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		<hr>
		<br>
		<h3 class="text-center">List ijin hari ini</h3>
		<div class="table-responsive">
  			<table class="table  table-condensed" id="tabel-ijin">
				<thead>
					<tr>
						<th>id</th>
						<th>nama</th>
						<th>tanggal</th>
						<th>urusan</th>
						<th>start</th>
						<th>end</th>
						<th>kompensasi</th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
		</div>
	</div>

<script type="text/javascript">
	window.onload = show();
	function show() {
		$.get('<?php echo base_url('Home_C/show_ijin/')?>', function(html){
	    	var data = JSON.parse(html);
	    	$('#tabel').DataTable().destroy();
	    	$('#tabel-ijin').DataTable().destroy();
			$('#tabel').DataTable(
				{"data" :(data.ijin),
				"columns": [
					{ "data": "id_i" },
					{ "data": "nama_k" },
					{ "data": "tanggal" },
					{ "data": "perihal" },
					{ "data": "start" },
					{ "data": "end" },
					{ "data": "id_i",
						"render": function ( data, type, full, meta ) {
							return '<a  class="btn btn-xs btn-danger" data-idi="'+data+'" onclick="stop(this)">Stop</a>';
						}
					}
				],
				"paging" : false,
				"columnDefs": [
                    { "width": "10px", "targets": 6 }
                ],
                "aoColumnDefs": [
					{ "bSortable": false, "aTargets": [6] }
				]
			});
			$('#tabel-ijin').DataTable({
				"data" :(data.list_ijin),
				"columns": [
					{ "data": "id_i" },
					{ "data": "nama_k" },
					{ "data": "tanggal" },
					{ "data": "perihal" },
					{ "data": "start" },
					{ "data": "end" },
					{ 	"data": "denda",
						"render": $.fn.dataTable.render.number( ',', '.', 2, 'Rp.' )}
				],
				"paging" : false
			});
	    });
	}
</script>


<script type="text/javascript">
	
	function submit() {
		// console.log('aaaa');
		var url = "<?=base_url('Home_C/create_ijin')?>";
		$('#start-ijin').text('starting...'); //change button text
	    $('#start-ijin').attr('disabled',true); //set button disable 

	    var formData = new FormData($('#form-ijin')[0]);
	    $.ajax({
	        url : url,
	        type: "POST",
	        data: formData,
	        contentType: false,
	        processData: false,
	        success: function(data)
	        {
	        	var object = JSON.parse(data);
	            $("#alert").html(object);
	            // console.log(data);
	            $('#start-ijin').text('Starts'); //change button text
	            $('#start-ijin').attr('disabled',false); //set button enable 
	            show();

	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	            console.log(jqXHR, textStatus, errorThrown);
	            $('#start-ijin').text('eror'); //change button text
	            $('#start-ijin').attr('disabled',false); //set button enable 
	        }
	    });
	    show();
	}
	// function free(){
		
	// }
	function stop(elem){
		var uidi = $(elem).data('idi');
		var url = "<?=base_url('Home_C/stop_ijin/')?>";
		var alert = document.getElementById('alert');
	    $.get(url + uidi, function(html){
	        var object = JSON.parse(html);
	        alert.innerHTML = object;
	    });
	    show();
	}
</script>