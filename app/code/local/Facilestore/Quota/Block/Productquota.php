<?php

class Facilestore_Quota_Block_Productquota extends Mage_Catalog_Block_Product_Abstract{
    protected $_configs = array();
    
    public function _construct() {
        $this->_configs['enable'] = Mage::getStoreConfig('facilestore_quota/general/enable');
        $this->_configs['qtdparcelas'] = Mage::getStoreConfig('facilestore_quota/general/qtdparcelas');
        $this->_configs['valorminimoparcela'] = Mage::getStoreConfig('facilestore_quota/general/valorminimoparcela');
        $this->_configs['juroscartao'] = Mage::getStoreConfig('facilestore_quota/general/juroscartao');
        $this->_configs['boleto_enable'] = Mage::getStoreConfig('facilestore_quota/general/boleto_enable');
        $this->_configs['porcentagem_boleto'] = Mage::getStoreConfig('facilestore_quota/general/porcentagem_boleto');
        $this->_configs['txt_boleto'] = Mage::getStoreConfig('facilestore_quota/general/txt_boleto');
    }
    
    public function isEnable(){
        return $this->_configs['enable'];
    }
    
    public function getDescBoleto(){
        if($this->_configs['boleto_enable'] && $this->_configs['porcentagem_boleto']){
            $product = $this->_getProduct();
            if($product){
                $percent = $this->_configs['porcentagem_boleto'];
                $txt_boleto = $this->_configs['txt_boleto'];            
                $valor = $product->getFinalPrice() - ($product->getFinalPrice() * ($percent / 100));            
                $txt_boleto = str_replace("#percent#", $percent, $txt_boleto);
                $txt_boleto = str_replace("#valor#", $this->_formatPrice($valor), $txt_boleto);
                return $txt_boleto;    
            }            
        }
        return false;
    }
    
    public function getParcelas($list=false){
        $product = $this->_getProduct();
        $parcelas = array();
        if($product){            
            for($i=1; $i<=$this->_configs['qtdparcelas']; $i++):
                $parcela = $product->getFinalPrice() / $i;
                if($parcela < $this->_configs['valorminimoparcela'] && $this->_configs['juroscartao']>0){
                    $juroscartao = str_replace(",", ".", $this->_configs['juroscartao']);
                    $valor_final = $product->getFinalPrice() * pow((1 + ($juroscartao/100)), $i);
                    $parcela = $valor_final / $i;
                }
                $return = array(
                    'numero' => $i,
                    'valor_parcela' => $this->_formatPrice($parcela),
                    'valor_parcela_total' => $this->_formatPrice($i * $parcela),
                );
                $parcelas[] = $return;
            endfor;
        }
        
        if($list){
            return array_pop($parcelas);
        }
        
        return $parcelas;
    }
    
    public function _getProduct(){
        if($this->getProduct()){
            return $this->getProduct();
        } elseif(Mage::registry('current_product')){
            return Mage::registry('current_product');
        }
    }
    
    public function _formatPrice($price){
        return Mage::helper('core')->currency($price, true, false);
    }
    
    public function isCatalog(){
        $onCatalog = true;
        if(Mage::registry('current_product')) {
            $onCatalog = false;
        }
        return $onCatalog;
    }
}