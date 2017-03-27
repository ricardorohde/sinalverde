<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Financeiro extends CI_Controller {

    /**
     * author: Leandro Silveira
     * email: silveira.jedi@gmail.com
     * 
     */

	public function __construct()
	{
		parent::__construct();
		if((!$this->session->userdata('session_id')) || (!$this->session->userdata('logado'))){
        	redirect('mapos/login');
        }
        $this->load->model('financeiro_model','',TRUE);
        $this->data['menuFinanceiro'] = 'financeiro';
        $this->load->helper(array('codegen_helper'));
	}
	public function index(){
		$this->lancamentos();
	}

	public function lancamentos(){
		if(!$this->permission->checkPermission($this->session->userdata('permissao'),'vLancamento')){
           $this->session->set_flashdata('error','Você não tem permissão para visualizar lançamentos.');
           redirect(base_url());
        }

		$where = '';
		$periodo = $this->input->get('periodo');
		$situacao = $this->input->get('situacao');

        // busca todos os lançamentos
        if($periodo == 'todos'){

            if($situacao == 'previsto'){
                $where = 'data_vencimento > "'.date('Y-m-d').'" AND baixado = "0"'; 
            }
            else{
                if($situacao == 'atrasado'){
                    $where = 'data_vencimento < "'.date('Y-m-d').'" AND baixado = "0"'; 
                }
                else{
                    if($situacao == 'realizado'){
                        $where = 'baixado = "1"';
                    }

                    if($situacao == 'pendente'){
                        $where = 'baixado = "0"';
                    }
                }
            }
        }
        else{

            // busca lançamentos do dia 
            if($periodo == null || $periodo == 'dia'){
                $where = 'data_vencimento = "'.date('Y-m-d'.'"');
            

            } // fim lançamentos dia


            else{

                // busca lançamentos da semana
                if($periodo == 'semana'){
                    $semana = $this->getThisWeek();

                    if(! isset($situacao) || $situacao == 'todos'){
                    
                        $where = 'data_vencimento BETWEEN "'.$semana[0].'" AND "'.$semana[1].'"'; 

                    }
                    else{
                        if($situacao == 'previsto'){
                            $where = 'data_vencimento BETWEEN "'.date('Y-m-d').'" AND "'.$semana[1].'" AND baixado = "0"'; 
                        }
                        else{
                            if($situacao == 'atrasado'){
                                $where = 'data_vencimento BETWEEN "'.$semana[0].'" AND "'.date('Y-m-d').'" AND baixado = "0"'; 
                            }
                            else{
                                if($situacao == 'realizado'){
                                    $where = 'data_vencimento BETWEEN "'.$semana[0].'" AND "'.$semana[1].'" AND baixado = "1"';
                                }
                                else{
                                    $where = 'data_vencimento BETWEEN "'.$semana[0].'" AND "'.$semana[1].'" AND baixado = "0"';
                                }
                            }
                        }
                    }
                
                } // fim lançamentos dia
                else{

                    // busca lançamento do mês


                    if($periodo == 'mes'){
                        
                        $mes = $this->getThisMonth();
                        
                        if(! isset($situacao) || $situacao == 'todos'){
                    
                            $where = 'data_vencimento BETWEEN "'.$mes[0].'" AND "'.$mes[1].'"'; 

                        }
                        else{
                            if($situacao == 'previsto'){
                                $where = 'data_vencimento BETWEEN "'.date('Y-m-d').'" AND "'.$mes[1].'" AND baixado = "0"'; 
                            }
                            else{
                                if($situacao == 'atrasado'){
                                    $where = 'data_vencimento BETWEEN "'.$mes[0].'" AND "'.date('Y-m-d').'" AND baixado = "0"'; 
                                }
                                else{
                                    if($situacao == 'realizado'){
                                        $where = 'data_vencimento BETWEEN "'.$mes[0].'" AND "'.$mes[1].'" AND baixado = "1"';    
                                    }
                                    else{
                                        $where = 'data_vencimento BETWEEN "'.$mes[0].'" AND "'.$mes[1].'" AND baixado = "0"';
                                    }
                                    
                                }
                            }
                        }
                    }

                    // busca lançamentos do ano
                    else{
                        $ano = $this->getThisYear();
                        
                        if(! isset($situacao) || $situacao == 'todos'){
                    
                            $where = 'data_vencimento BETWEEN "'.$ano[0].'" AND "'.$ano[1].'"';

                        }
                        else{
                            if($situacao == 'previsto'){
                                $where = 'data_vencimento BETWEEN "'.date('Y-m-d').'" AND "'.$ano[1].'" AND baixado = "0"'; 
                            }
                            else{
                                if($situacao == 'atrasado'){
                                    $where = 'data_vencimento BETWEEN "'.$ano[0].'" AND "'.date('Y-m-d').'" AND baixado = "0"'; 
                                }
                                else{
                                    if($situacao == 'realizado'){
                                        $where = 'data_vencimento BETWEEN "'.$ano[0].'" AND "'.$ano[1].'" AND baixado = "1"';        
                                    }
                                    else{
                                        $where = 'data_vencimento BETWEEN "'.$ano[0].'" AND "'.$ano[1].'" AND baixado = "0"';
                                    }
                                    
                                }
                            }
                        }   
                    }
                }
            }    
        }

	

		$this->load->library('pagination');
        
        $config['base_url'] = base_url().'financeiro/lancamentos';
        $config['total_rows'] = $this->financeiro_model->count('lancamentos');
        $config['per_page'] = 100;
        $config['next_link'] = 'Próxima';
        $config['prev_link'] = 'Anterior';
        $config['full_tag_open'] = '<div class="pagination alternate"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li><a style="color: #2D335B"><b>';
        $config['cur_tag_close'] = '</b></a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';	
        $this->pagination->initialize($config); 	

		$this->data['results'] = $this->financeiro_model->get('lancamentos','idLancamentos,descricao,valor,data_vencimento,data_pagamento,baixado,cliente_fornecedor,tipo,forma_pgto,parcela1,parcela2,parcela3,parcela4,dataparcela1,dataparcela2,dataparcela3,dataparcela4,numcheque,nomecheque',$where,$config['per_page'],$this->uri->segment(3));
       
	    $this->data['view'] = 'financeiro/lancamentos';
       	$this->load->view('tema/topo',$this->data);
	}



	function adicionarReceita() {

        if(!$this->permission->checkPermission($this->session->userdata('permissao'),'aLancamento')){
           $this->session->set_flashdata('error','Você não tem permissão para adicionar lançamentos.');
           redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        $urlAtual = $this->input->post('urlAtual');
        if ($this->form_validation->run('receita') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {


            $vencimento = $this->input->post('vencimento');
            $recebimento = $this->input->post('recebimento');

            if($recebimento != null){
                $recebimento = explode('/', $recebimento);
                $recebimento = $recebimento[2].'-'.$recebimento[1].'-'.$recebimento[0];
            }

            if($vencimento == null){
                $vencimento = date('d/m/Y');
            }
            
            try {
                
                $vencimento = explode('/', $vencimento);
                $vencimento = $vencimento[2].'-'.$vencimento[1].'-'.$vencimento[0];   

            } catch (Exception $e) {
               $vencimento = date('Y/m/d'); 
            }

            $data = array(
                'descricao' => set_value('descricao'),
				'valor' => set_value('valor'),
				'data_vencimento' => $vencimento,
				'data_pagamento' => $recebimento != null ? $recebimento : date('Y-m-d'),
				'baixado' => $this->input->post('recebido'),
				'cliente_fornecedor' => set_value('cliente'),
				'forma_pgto' => $this->input->post('formaPgto'),
				'tipo' => set_value('tipo'),
                                'numcheque' => $this->input->post('numcheque'),
                                'nomecheque' => $this->input->post('nomecheque')
            );

            if ($this->financeiro_model->add('lancamentos',$data) == TRUE) {
                $this->session->set_flashdata('success','Receita adicionada com sucesso!');
                redirect($urlAtual);
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->session->set_flashdata('error','Ocorreu um erro ao tentar adicionar receita.');
        redirect($urlAtual);
        
    }


    function adicionarDespesa() {

        if(!$this->permission->checkPermission($this->session->userdata('permissao'),'aLancamento')){
           $this->session->set_flashdata('error','Você não tem permissão para adicionar lançamentos.');
           redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        $urlAtual = $this->input->post('urlAtual');
        if ($this->form_validation->run('despesa') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {

            $vencimento = $this->input->post('vencimento');
            $pagamento = $this->input->post('pagamento');

            if($pagamento != null){
                $pagamento = explode('/', $pagamento);
                $pagamento = $pagamento[2].'-'.$pagamento[1].'-'.$pagamento[0];
            }

            if($vencimento == null){
                $vencimento = date('d/m/Y');
            }

            try {
                
                $vencimento = explode('/', $vencimento);
                $vencimento = $vencimento[2].'-'.$vencimento[1].'-'.$vencimento[0];

            } catch (Exception $e) {
               $vencimento = date('Y/m/d'); 
            }

            $data = array(
                'descricao' => set_value('descricao'),
				'valor' => set_value('valor'),
				'data_vencimento' => $vencimento,
				'data_pagamento' => $pagamento != null ? $pagamento : date('Y-m-d'),
				'baixado' => $this->input->post('pago'),
				'cliente_fornecedor' => set_value('fornecedor'),
				'forma_pgto' => $this->input->post('formaPgto'),
				'tipo' => set_value('tipo'),
                                'numcheque' => $this->input->post('numcheque'),
                                'nomecheque' => $this->input->post('nomecheque')
            );

            if ($this->financeiro_model->add('lancamentos',$data) == TRUE) {
                $this->session->set_flashdata('success','Despesa adicionada com sucesso!');
                redirect($urlAtual);
            } else {
                $this->session->set_flashdata('error','Ocorreu um erro ao tentar adicionar despesa!');
                redirect($urlAtual);
            }
        }

        $this->session->set_flashdata('error','Ocorreu um erro ao tentar adicionar despesa.');
        redirect($urlAtual);
        
        
    }


    public function editar(){   
        if(!$this->permission->checkPermission($this->session->userdata('permissao'),'eLancamento')){
           $this->session->set_flashdata('error','Você não tem permissão para editar lançamentos.');
           redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        $urlAtual = $this->input->post('urlAtual');

        $this->form_validation->set_rules('descricao', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('fornecedor', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('valor', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('vencimento', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('pagamento', '', 'trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {

            $vencimento = $this->input->post('vencimento');
            $pagamento = $this->input->post('pagamento');
            $dataparcela1 = $this->input->post('dataparcela1');
            $dataparcela2 = $this->input->post('dataparcela2');
            $dataparcela3 = $this->input->post('dataparcela3');
            $dataparcela4 = $this->input->post('dataparcela4');

            try {
                
                $vencimento = explode('/', $vencimento);
                $vencimento = $vencimento[2].'-'.$vencimento[1].'-'.$vencimento[0];

                $pagamento = explode('/', $pagamento);
                $pagamento = $pagamento[2].'-'.$pagamento[1].'-'.$pagamento[0];
                
                if($dataparcela1){
                    $dataparcela1 = explode('/', $dataparcela1);
                    $dataparcela1 = $dataparcela1[2].'-'.$dataparcela1[1].'-'.$dataparcela1[0];
                }else{
                    $dataparcela1 = $vencimento;
                }
                
                if($dataparcela2){
                    $dataparcela2 = explode('/', $dataparcela2);
                    $dataparcela2 = $dataparcela2[2].'-'.$dataparcela2[1].'-'.$dataparcela2[0];
                }else{
                    $dataparcela2 = date('Y/m/d');
                }
                
                if($dataparcela3){
                    $dataparcela3 = explode('/', $dataparcela3);
                    $dataparcela3 = $dataparcela3[2].'-'.$dataparcela3[1].'-'.$dataparcela3[0];
                }else{
                    $dataparcela3 = date('Y/m/d');
                }
                
                if($dataparcela4){
                    $dataparcela4 = explode('/', $dataparcela4);
                    $dataparcela4 = $dataparcela4[2].'-'.$dataparcela4[1].'-'.$dataparcela4[0];
                }else{
                    $dataparcela4 = date('Y/m/d');
                }
                

            } catch (Exception $e) {
               $vencimento = date('Y/m/d'); 
               $dataparcela1 = date('Y/m/d');
               $dataparcela2 = date('Y/m/d');
               $dataparcela3 = date('Y/m/d');
               $dataparcela4 = date('Y/m/d');
            }

            $data = array(
                'descricao' => $this->input->post('descricao'),
                'valor' => $this->input->post('valor'),
                'data_vencimento' => $vencimento,
                'data_pagamento' => $pagamento,
                'baixado' => $this->input->post('pago'),
                'cliente_fornecedor' => $this->input->post('fornecedor'),
                'forma_pgto' => $this->input->post('formaPgto'),
                'tipo' => $this->input->post('tipo'),
                'parcela1' => $this->input->post('parcela1'),
                'parcela2' => $this->input->post('parcela2'),
                'parcela3' => $this->input->post('parcela3'),
                'parcela4' => $this->input->post('parcela4'),
                'dataparcela1' => $dataparcela1,
                'dataparcela2' => $dataparcela2,
                'dataparcela3' => $dataparcela3,
                'dataparcela4' => $dataparcela4,
                'numcheque' => $this->input->post('numcheque'),
                'nomecheque' => $this->input->post('nomecheque')
            );

            if ($this->financeiro_model->edit('lancamentos',$data,'idLancamentos',$this->input->post('id')) == TRUE) {
                $this->session->set_flashdata('success','lançamento editado com sucesso!');
                redirect($urlAtual);
            } else {
                $this->session->set_flashdata('error','Ocorreu um erro ao tentar editar lançamento!');
                redirect($urlAtual);
            }
        }

        $this->session->set_flashdata('error','Ocorreu um erro ao tentar editar lançamento.');
        redirect($urlAtual);

        $data = array(
                'descricao' => $this->input->post('descricao'),
                'valor' => $this->input->post('valor'),
                'data_vencimento' => $this->input->post('vencimento'),
                'data_pagamento' => $this->input->post('pagamento'),
                'baixado' => $this->input->post('pago'),
                'cliente_fornecedor' => set_value('fornecedor'),
                'forma_pgto' => $this->input->post('formaPgto'),
                'tipo' => $this->input->post('tipo'),
                'parcela1' => $this->input->post('parcela1'),
                'parcela2' => $this->input->post('parcela2'),
                'parcela3' => $this->input->post('parcela3'),
                'parcela4' => $this->input->post('parcela4'),
                'dataparcela1' => $this->input->post('dataparcela1'),
                'dataparcela2' => $this->input->post('dataparcela2'),
                'dataparcela3' => $this->input->post('dataparcela3'),
                'dataparcela4' => $this->input->post('dataparcela4'),
                'numcheque' => $this->input->post('numcheque'),
                'nomecheque' => $this->input->post('nomecheque')
            );
        print_r($data);

    }

    public function excluirLancamento(){   

        if(!$this->permission->checkPermission($this->session->userdata('permissao'),'dLancamento')){
           $this->session->set_flashdata('error','Você não tem permissão para excluir lançamentos.');
           redirect(base_url());
        }

    	$id = $this->input->post('id');

    	if($id == null || ! is_numeric($id)){
    		$json = array('result'=>  false);
    		echo json_encode($json);
    	}
    	else{

    		$result = $this->financeiro_model->delete('lancamentos','idLancamentos',$id); 
    		if($result){
    			$json = array('result'=>  true);
    			echo json_encode($json);
    		}
    		else{
    			$json = array('result'=>  false);
    			echo json_encode($json);
    		}
    		
    	}
    }




	protected function getThisYear() {

        $dias = date("z");
        $primeiro = date("Y-m-d", strtotime("-".($dias)." day"));
        $ultimo = date("Y-m-d", strtotime("+".( 364 - $dias)." day"));
        return array($primeiro,$ultimo);

    }

    protected function getThisWeek(){

        return array(date("Y/m/d", strtotime("last sunday", strtotime("now"))),date("Y/m/d", strtotime("next saturday", strtotime("now"))));
    }

    protected function getLastSevenDays() {

        return array(date("Y-m-d", strtotime("-7 day", strtotime("now"))), date("Y-m-d", strtotime("now")));
    }

    protected function getThisMonth(){

        $mes = date('m');
        $ano = date('Y'); 
        $qtdDiasMes = date('t');
        $inicia = $ano."-".$mes."-01";

        $ate = $ano."-".$mes."-".$qtdDiasMes;
        return array($inicia, $ate);
    }

}

