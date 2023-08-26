<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-sm-6">
				<h5><?= $pageTitle ?></h6>
				<?php $this->load->view('partials/breadcumb');?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<form class="form theme-form" action="<?= base_url('progress/send_progress/').$data['id_progPR']; ?>" method="POST" enctype="multipart/form-data">
					<div class="card-body">
						<div class="row">
							<input type="hidden" name="id_pemohonPR" value="<?= $pemohon_idPR; ?>">
							<div class="col-md-4 mt-2">
								<label class="form-label f-w-700 txt-dark" for="">Nama Proyek <code>*bagian ini terisi otomatis</code></label>
								<input class="form-control" name="nama_pekerjaan" id="nama_pekerjaan" type="text" value="<?= $data['pkj_namaPR']?>" disabled>
							</div>
							<div class="col-md-4">
								<label class="form-label f-w-700 txt-dark" for="">Rencana Progress <span class="txt-danger">*%</span></label>
								<input class="form-control" name="rencana_progress" id="rencana_progress" type="text">
							</div>
							<div class="col-md-4 mb-4">
								<label class="form-label f-w-700 txt-dark" for="">Realisasi Progress <span class="txt-danger">*%</span></label>
								<input class="form-control" name="realisasi_progress" id="realisasi_progress" type="text">
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<label class="form-label f-w-700 txt-dark" for="">Deviasi <span class="txt-danger">*%</span></label>
								<input class="form-control" name="deviasi" id="deviasi" type="text">
							</div>
							<div class="col-md-4">
								<label class="form-label f-w-700 txt-dark" for="">Realisasi Keuangan</label>
								<input class="form-control" name="realisasi_keuangan" id="realisasi_keuangan" type="text" placeholder="Rp.10.000.000">
							</div>
							<div class="col-md-4 mb-4">
								<label class="form-label f-w-700 txt-dark" for="">Laporan Bulanan</label>
								<input class="form-control" name="laporan_bulanan" id="laporan_bulanan" type="text">
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<label class="form-label f-w-700 txt-dark" for="">Waktu</label>
								<input class="form-control" name="waktu" id="waktu" type="datetime-local">
							</div>
							<div class="col-md-8 mb-4">
								<label class="form-label f-w-700 txt-dark" for="">Item Pekerjaan <span class="txt-danger">*foto</span></label>
								<input class="form-control" name="it_pkjPR" id="it_pkjPR" type="file">
							</div>
						</div>
					</div>
					<div class="card-footer text-start">
						<button class="btn btn-primary" type="submit">Kirim</button>
				</form>
			</div>
		</div>
	</div>
</div>