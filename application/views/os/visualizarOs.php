<?php $totalServico = 0; $totalProdutos = 0;?>
<div class="row-fluid" style="margin-top: 0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="icon-tags"></i>
                </span>
                <h5>Ordem de Serviço</h5>
                <div class="buttons">
                    <?php if($this->permission->checkPermission($this->session->userdata('permissao'),'eOs')){
                        echo '<a title="Icon Title" class="btn btn-mini btn-info" href="'.base_url().'index.php/os/editar/'.$result->idOs.'"><i class="icon-pencil icon-white"></i> Editar</a>'; 
                    } ?>
                    
                    <a id="imprimir" title="Imprimir" class="btn btn-mini btn-inverse" href=""><i class="icon-print icon-white"></i> Imprimir</a>
                </div>
            </div>
            <div class="widget-content" id="printOs">
                <div class="invoice-content">
                    <div class="invoice-head" style="margin-bottom: 0">

                        <table class="table">
                            <tbody>
                                <?php if($emitente == null) {?>
                                            
                                <tr>
                                    <td colspan="3" class="alert">Você precisa configurar os dados do emitente. >>><a href="<?php echo base_url(); ?>index.php/mapos/emitente">Configurar</a><<<</td>
                                </tr>
                                <?php } else {?>
                                <tr>
                                    <td style="width: 25%"><img src=" <?php echo $emitente[0]->url_logo; ?> "></td>
                                    <td> <span style="font-size: 12px; "> <?php echo $emitente[0]->nome; ?></span> </br><span style="font-size: 9px; "><?php echo $emitente[0]->cnpj; ?> </br> <?php echo $emitente[0]->rua.', nº:'.$emitente[0]->numero.', '.$emitente[0]->bairro.' - '.$emitente[0]->cidade.' - '.$emitente[0]->uf; ?> </span> </br> <span style="font-size: 9px; "> E-mail: <?php echo $emitente[0]->email.' - Fone: '.$emitente[0]->telefone; ?></span></td>
                                    <td style="width: 18%; text-align: center"> <span style="font-size: 10x; "> #Protocolo:<?php echo $result->idOs?></span></br> </br> <span style="font-size: 9px; ">Emissão: <?php echo date('d/m/Y')?></span> <span style="font-size: 9px; ">Venc.: <?php echo date('d/m/Y', strtotime($result->dataFinal));?></span></td>
                                </tr>

                                <?php } ?>
                            </tbody>
                        </table>

                
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td style="width: 33%; padding-left: 0">
                                        <ul>
                                            <li>
                                                <span style="font-size: 12px; "><b>CLIENTE</b></span><br>                                                
                                                <span style="font-size: 9px; "><?php echo $result->nomeCliente?></span><br/>
                                                <span style="font-size: 9px; "><?php echo $result->rua?>, <?php echo $result->numero?>, <?php echo $result->bairro?></span><br/>
                                                <span style="font-size: 9px; "><?php echo $result->celular?></span>
                                            </li>
                                        </ul>
                                    </td>
                                    <td style="width: 33%; padding-left: 0">
                                        <ul>
                                            <li>
                                                <span style="font-size: 12px; "><b>RESPONSÁVEL</b></span><br>                                                
                                                <span style="font-size: 9px; "><?php echo $result->nome?></span> <br/>
                                                <span style="font-size: 9px; ">Telefone: <?php echo $result->telefone?></span><br/>
                                                
                                            </li>
                                        </ul>
                                    </td>
                                    <td style="width: 33%; padding-left: 0">
                                        <ul>
                                            <li>
                                                <?php if($result->observacoes != null){?>
                                                 
                                                 <span style="font-size: 12px; "><b>KM / Placa / Veículo</b></span><br>                                                

                                                  
                                                      <span style="font-size: 9px; "><?php echo $result->observacoes?></span>
                                                <?php }?>
                                               
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table> 
      
                    </div>

                    <div style="margin-top: 0; padding-top: 0">

                    
                    
                        <?php if($produtos != null){?>
                        
                        <table class="table table-bordered" id="tblProdutos">
                                    <thead>
                                        <tr>
                                            <th>Produto</th>
                                            <th>Quantidade</th>
                                            <th>Sub-total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        
                                        foreach ($produtos as $p) {

                                            $totalProdutos = $totalProdutos + $p->subTotal;
                                            echo '<tr>';
                                            echo '<td><span style="font-size: 9px; ">'.$p->descricao.'</span></td>';
                                            echo '<td><span style="font-size: 9px; ">'.$p->quantidade.'</span></td>';
                                            
                                            echo '<td><span style="font-size: 9px; ">R$ '.number_format($p->subTotal,2,',','.').'</span></td>';
                                            echo '</tr>';
                                        }?>

                                        <tr>
                                            <td colspan="2" style="text-align: right"><span style="font-size: 9px; "><strong>Total:</strong></span></td>
                                            <td><span style="font-size: 9px; "><strong>R$ <?php echo number_format($totalProdutos,2,',','.');?></strong></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                               <?php }?>
                        
                        <?php if($servicos2 != null){?>
                        <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Mão de Obra</th>
                                                <th>Sub-total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                        setlocale(LC_MONETARY, 'en_US');
                                        foreach ($servicos2 as $s) {
                                            $preco = $s->totalsrv;
                                            $totalServico = $totalServico + $preco;
                                            echo '<tr>';
                                            echo '<td style="width: 75%; padding-left: 2"><span style="font-size: 9px; ">'.$s->descricao.'</span></td>';
                                            echo '<td><span style="font-size: 9px; ">R$ '.number_format($s->totalsrv, 2, ',', '.').'</span></td>';
                                            echo '</tr>';
                                        }?>

                                        <tr>
                                            <td colspan="1" style="text-align: right"><strong>Total:</strong></td>
                                            <td><span style="font-size: 9px; "><strong>R$ <?php  echo number_format($totalServico, 2, ',', '.');?></strong></span></td>
                                        </tr>
                                        </tbody>
                                    </table>
                        <?php }?>
                    
                        <h6 style="text-align: right">Valor Total: R$ <?php echo number_format($totalProdutos + $totalServico,2,',','.');?></h6>

                    </div>
            

                    
                    
              
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#imprimir").click(function(){         
            PrintElem('#printOs');
        })

        function PrintElem(elem)
        {
            Popup($(elem).html());
        }

        function Popup(data)
        {
            var mywindow = window.open('', 'MapOs', 'height=600,width=800');
            mywindow.document.write('<html><head><title>Map Os</title>');
            mywindow.document.write("<link rel='stylesheet' href='<?php echo base_url();?>assets/css/bootstrap.min.css' />");
            mywindow.document.write("<link rel='stylesheet' href='<?php echo base_url();?>assets/css/bootstrap-responsive.min.css' />");
            mywindow.document.write("<link rel='stylesheet' href='<?php echo base_url();?>assets/css/matrix-style.css' />");
            mywindow.document.write("<link rel='stylesheet' href='<?php echo base_url();?>assets/css/matrix-media.css' />");


            mywindow.document.write("</head><body >");
            mywindow.document.write(data);          
            mywindow.document.write("</body></html>");

            setTimeout(function(){
                mywindow.print();
            }, 50);

            return true;
        }

    });
</script>