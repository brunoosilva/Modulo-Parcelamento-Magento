<?php

class Facilestore_Quota_Block_Productquota extends Mage_Catalog_Block_Product_Abstract{
    protected $_configs = array();
    
    public function _construct() {
        $this->_configs['enable'] = Mage::getStoreConfig('facilestore_quota/general/enable');
        $this->_configs['qtdparcelas'] = Mage::getStoreConfig('facilestore_quota/general/qtdparcelas');
        $this->_configs['valorminimoparcela'] = Mage::getStoreConfig('facilestore_quota/general/valorminimoparcela');
        $this->_configs['juroscartao'] = Mage::getStoreConfig('facilestore_quota/general/juroscartao');
    }
    
    public function isEnable(){
        return $this->_configs['enable'];
    }
    
    public function getParcelas(){
        $product = $this->_getProduct();
        $parcelas = array();
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
}