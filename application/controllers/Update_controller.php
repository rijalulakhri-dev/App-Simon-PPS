<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Update_controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Insert_model', 'ins');
        $this->load->model('View_model', 'view');
        $this->load->model('Update_model', 'upd');
        
    }
    
    /**
     * sendProgress
     * tampilan halaman untuk kirim progrsess
     * @param  mixed $id_pemohonPE
     * @return void
     */
    function sendProgress($id_pemohonPE)
    {
        $load = $this->view->joinPermohonan($id_pemohonPE);
        $data = array(
            'title' => 'Kirim Progress Bulanan',
            'pageTitle' => 'Kirim Progress Pekerjaan Bulanan Untuk Data :<b>['.$load['id_pemohonPE'].']</b>',
            'pages' => 'pages/guest/progress_pekerjaan',
            'data' => $load,
            'id_pemohonPE' => $id_pemohonPE
        );    

        $this->load->view('main', $data);
    }
    
    /**
     * update_process_progress
     * fungsi proses masukya data progess 
     * @param  mixed $id_progPR
     * @return void
     */
    function update_process_progress($id_progPR) 
    {
        $this->_rules('progress');

        if ($this->form_validation->run() == FALSE) {
            $this->sendProgress($id_progPR);
        } else {
            $nama_file_foto = 'default' . '_' . date('Y-m-d');
    
            $it_pkjPR = $_FILES['it_pkjPR']['tmp_name']; // upload foto
            $pemohon_idPR = $this->input->post('pemohon_idPR');
        
            $data = array(
                'rcn_progPR'    =>  $this->input->post('rencana_progress'),
                'rl_progPR'     =>  $this->input->post('realisasi_progress'),
                'deviasiPR'     =>  $this->input->post('deviasi'),
                'rl_keuanPR'    =>  $this->input->post('realisasi_keuangan'),
                'lp_bulanPR'    =>  $this->input->post('laporan_bulanan'),
                'waktuPR'       =>  $this->input->post('waktu'),
                'updateDatePR'  => date('Y-m-d')
            );
            
            // upload gambar / foto
            $config = array(
                'allowed_types' => 'jpg|jpeg|png|gif',
                'max_size'      => 10000,
                'overwrite'     => TRUE,
                'file_name'     => $nama_file_foto,
                'upload_path'   => './public/lampiran/foto',
                'encrypt_name'  => FALSE,
            );
        
            $this->load->library('upload', $config);
        
            $data['it_pkjPR'] = $nama_file_foto;
        
            if ($this->upload->do_upload('it_pkjPR')) {
                $it_pkjPR_data = $this->upload->data();
                $data['it_pkjPR'] = $nama_file_foto . $it_pkjPR_data['file_ext'];
            }
            
            $this->ins->update_progress($data, $id_progPR); // Gunakan update_progress dari model Insert_model
            $this->session->set_flashdata('success', '<strong>Data progress telah berhasil dikirimkan!</strong>');
            redirect('daftar_progress');
        }
        
       
    }
    
     
     /**
      * tindakPermohonan
      * tampilan halaman tindak lanjuti permohonan
      * @param  mixed $dokumen_idPE
      * @return void
      */
     function tindakPermohonan($dokumen_idPE)
    {
        $load = $this->view->joinDokumen($dokumen_idPE);
        
        $data = array(
            'title' => 'Kirim Undangan Pemaparan',
            'pageTitle' => 'Kirim Undangan Pemaparan Untuk Data :<b>['.$load['dokumen_idPE'].']</b>',
            'pages' => 'pages/pps/tindak_permohonan',
            'data' => $load,
            'id_dokumenDO' => $dokumen_idPE,
        );

        $this->load->view('main', $data);
    }
    
    /**
     * processTindak
     * fungsi proses tindak lanjuti permohonan
     * @param  mixed $id_dokumenDO
     * @return void
     */
    public function processTindak($id_dokumenDO)
    {
        $this->load->library('upload');
        $id_dokumenDO = $this->input->post('id_dokumenDO');
        $nama_file = 'default';
        $ud_pprDO = $_FILES['ud_pprDO']['name'];

        $option = 1;
        $data = array(
            'id_dokumenDO' =>  $id_dokumenDO,
            'ud_pprDO'     => $ud_pprDO,
            'jns_dokDO'    => 'Ditindak lanjuti'
        );

        $config = array(
            'allowed_types' => 'pdf|docx|doc|odt',
            'max_size'      => 10000,
            'overwrite'     => TRUE,
            'file_name'     => $nama_file,
            'upload_path'   => './public/lampiran/',
            'encrypt_name'  => FALSE,
        );

        $this->upload->initialize($config); 

        if (!empty($ud_pprDO)) {
            if ($this->upload->do_upload('ud_pprDO')) {
                $ud_pprDO_file_data = $this->upload->data();
                $data['ud_pprDO'] = $nama_file . $ud_pprDO_file_data['file_ext'];
            }
        }

        $this->ins->insert_dokumen($data, $id_dokumenDO, $option);
        $this->session->set_flashdata('success', '<strong>Permohonan telah ditindak lanjuti! </strong>');
        redirect('seksi-pps/daftar_permohonan');
        
    }
    
    /**
     * approvePermohonan
     * tampilan halaman untuk menerima data permohonan
     * @param  mixed $dokumen_idPE
     * @return void
     */
    function approvePermohonan($dokumen_idPE)
    {
        $load = $this->view->joinDokumen($dokumen_idPE);
        $data = array(
            'title' => 'Kirim Dokumen',
            'pageTitle' => 'Kirim Dokumen Untuk Data :<b>['.$load['dokumen_idPE'].']</b>',
            'pages' => 'pages/pps/approve_permohonan',
            'data' => $load,
            'id_dokumenDO' => $dokumen_idPE
        );

        $this->load->view('main', $data);
    }
    
    /**
     * processApprove
     * fungsi untuk menerima data permohonan
     * @param  mixed $id_dokumenDO
     * @return void
     */
    public function processApprove($id_dokumenDO)
    {
        $this->load->library('upload');
        $id_dokumenDO = $this->input->post('id_dokumenDO');
        $nama_file = 'sample';
        $IN13DO = $_FILES['IN13DO']['name'];
        $IN2DO = $_FILES['IN2DO']['name'];
        $option = 2;
        $data = array(
            'id_dokumenDO' =>  $id_dokumenDO,
            'IN13DO' => $IN13DO,
            'IN2DO' => $IN2DO,
            'jns_dokDO' => 'Diterima'
        );

        $config = array(
            'allowed_types' => 'pdf|docx|doc|odt',
            'max_size'      => 10000,
            'overwrite'     => TRUE,
            'file_name'     => $nama_file,
            'upload_path'   => './public/lampiran/',
            'encrypt_name'  => FALSE,
        );

        $this->upload->initialize($config); 

        if (!empty($IN13DO)) {
            if ($this->upload->do_upload('IN13DO')) {
                $IN13DO_file_data = $this->upload->data();
                $data['IN13DO'] = $nama_file . $IN13DO_file_data['file_ext'];
            }
        }
        if (!empty($IN14DO)) {
            if ($this->upload->do_upload('IN14DO')) {
                $IN14DO_file_data = $this->upload->data();
                $data['IN14DO'] = $nama_file . $IN14DO_file_data['file_ext'];
            }
        }
        if (!empty($IN2DO)) {
            if ($this->upload->do_upload('IN2DO')) {
                $IN2DO_file_data = $this->upload->data();
                $data['IN2DO'] = $nama_file . $IN2DO_file_data['file_ext'];
            }
        }

        $this->ins->insert_dokumen($data, $id_dokumenDO, $option);
        $this->session->set_flashdata('success', '<strong>Permohonan telah diterima! </strong>');
        redirect('seksi-pps/daftar_permohonan');

    }
    
    /**
     * cancelPermohonan
     * tampilan halaman untuk menolak fungsi permohonan
     * @param  mixed $dokumen_idPE
     * @return void
     */
    function cancelPermohonan($dokumen_idPE)
    {
        $load = $this->view->joinDokumen($dokumen_idPE);
        $data = array(
            'title' => 'Kirim Dokumen',
            'pageTitle' => 'Kirim Dokumen Untuk Data :<b>['.$load['dokumen_idPE'].']</b>',
            'pages' => 'pages/pps/cancel_permohonan',
            'data' => $load,
            'id_dokumenDO' => $dokumen_idPE
        );

        $this->load->view('main', $data);
    }
    
    /**
     * processCancel
     * ini merupakan fungsi untuk menolak permohonan 
     * @param  mixed $id_dokumenDO
     * @return void
     */
    public function processCancel($id_dokumenDO)
    {
        $this->load->library('upload');
        $id_dokumenDO = $this->input->post('id_dokumenDO');
        $nama_file = 'cancel';
        $IN14DO = $_FILES['IN14DO']['name'];
        $option = 3;

        $data = array(
            'id_dokumenDO' =>  $id_dokumenDO,
            'IN14DO' => $IN14DO,
            'jns_dokDO' => 'Ditolak'
        );

        $config = array(
            'allowed_types' => 'pdf|docx|doc|odt',
            'max_size'      => 10000,
            'overwrite'     => TRUE,
            'file_name'     => $nama_file,
            'upload_path'   => './public/lampiran/',
            'encrypt_name'  => FALSE,
        );

        $this->upload->initialize($config); 

        if (!empty($IN14DO)) {
            if ($this->upload->do_upload('IN14DO')) {
                $IN14DO_file_data = $this->upload->data();
                $data['IN14DO'] = $nama_file . $IN14DO_file_data['file_ext'];
            }
        }

        $this->ins->insert_dokumen($data, $id_dokumenDO, $option);
        $this->session->set_flashdata('success', '<strong>Permohonan telah ditolak! </strong>');
        redirect('seksi-pps/daftar_permohonan');

    }
    
    /**
     * delete
     * fungsi untuk delete data pengguna
     * @param  mixed $id_pengguna
     * @return void
     */
    function delete($id_pengguna)
    {
        $row = $this->upd->get_pengguna_id($id_pengguna);

        if($row) {
            $this->upd->delete_pengguna($id_pengguna);
            $this->db->query("DELETE FROM tb_pengguna WHERE id_pengguna = '$id_pengguna'");

            $this->session->set_flashdata('success', 'Akun berhasil dihapus!');
            redirect('list_user');
        }
    }

    function _rules($validasi) {
        switch ($validasi) {
            case 'progress':
                $this->form_validation->set_rules('rencana_progress', 'Rencana Progress', 'trim|required|min_length[2]|max_length[4]',array(
                    'required' => '%s wajib di isi !',
                    'min_length' => '%s terlalu pendek !',
                    'max_length' => '%s terlalu panjang !',
                ));
                $this->form_validation->set_rules('realisasi_progress', 'Realisasi Progress', 'trim|required|min_length[2]|max_length[4]',array(
                    'required' => '%s wajib di isi !',
                    'min_length' => '%s terlalu pendek !',
                    'max_length' => '%s terlalu panjang !',
                ));
                $this->form_validation->set_rules('deviasi', 'Deviasi', 'trim|required|min_length[2]|max_length[4]',array(
                    'required' => '%s wajib di isi !',
                    'min_length' => '%s terlalu pendek !',
                    'max_length' => '%s terlalu panjang !',
                ));
                $this->form_validation->set_rules('realisasi_keuangan', 'Realisasi Keuangan', 'trim|required|min_length[5]|max_length[50]',array(
                    'required' => '%s wajib di isi !',
                    'min_length' => '%s terlalu pendek !',
                    'max_length' => '%s terlalu panjang !',
                ));
                $this->form_validation->set_rules('laporan_bulanan', 'Laporan Bulanan', 'trim|required|min_length[5]|max_length[50]',array(
                    'required' => '%s wajib di isi !',
                    'min_length' => '%s terlalu pendek !',
                    'max_length' => '%s terlalu panjang !',
                ));
                
                break;
            
            default:
                # code...
                break;
        }
        
    }
}

