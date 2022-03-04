<?php

class Produto extends Controlador
{
    function __construct()
    {
        parent::__construct();
    }

    public function buscar_gtin()
    {
        $id_empr = $this->requisicao->id_empr;
        $id_pes = $this->requisicao->id_pes;
        $gtin = $this->requisicao->gtin;

        if (empty($id_empr) || empty($id_pes) || empty($gtin)) return $this->resposta(NULL);
        
        $sql = "SELECT produto.id, produto.descricao, produto_embalagem.gtin, produto_embalagem.quantidade,
                    (SELECT sigla FROM embalagem WHERE embalagem.id = produto_embalagem.id_embalagem) as sigla_embalagem,
                    (SELECT venda FROM produto_nivel_preco WHERE produto_nivel_preco.id_prod = produto.id 
                        AND produto_nivel_preco.id_nivel = (SELECT id_nivel FROM loja_nivel_preco WHERE id_empr = produto.id_empr AND id_loja = ? AND padrao = TRUE)) as valor_unit
                FROM produto 
                JOIN produto_embalagem ON produto_embalagem.id_prod = produto.id AND produto_embalagem.id_empr = produto.id_empr
                WHERE produto_embalagem.gtin = ?
                AND produto.id_empr = ?";

        $produtos = $this->db->consultar($sql, [$id_pes, $gtin, $id_empr]);

        return $this->resposta(["produto" => $produtos]);
    }
}
