# Módulo Parcelamento & Boleto Magento

Módulo que exibe as formas de parcela da loja e desconto no pagamento em boleto, na tela de detalhe do produto e listagens dos produtos.

Obs.: Lembrando que o módulo só faz a exibição dos valores nas telas de listagem e detalhe do produto, porem os valores não são 
contabilizados ou integrados com o checkout, ainda. :D

# Instalação

1 - Fazer upload da pasta completa, na raiz do seu magento.

2 - Caso tenha template customizado, lembre de subir as pastas de layout para a pasta do seu template.

3 - Use este código "<?php echo $this->getChildHtml('Facilestore_Quota_ProductQuota') ?>" para chamar o módulo dentro da página do produto 
    <i>(/app/design/frontend/base/default/template/catalog/product/view.phtml)</i>
	
4 - Para funcionar a exibição nas listagens de produtos, segue os procedimentos abaixo:

	4a - Abra o arquivo <i>(/app/design/frontend/base/default/template/catalog/product/list.phtml)</i>
	4b - Adicione este código "<?php $this->getChild('Facilestore_Quota_ProductQuota')->setData("product", $_product); ?>"
		 abaixo da linha "<?php foreach ($_productCollection as $_product): ?>" ~Linha 95 (Format List)
	4c - Adicione este código "<?php $this->getChild('Facilestore_Quota_ProductQuota')->setData("product", $_product); ?>"
		 abaixo da linha "<?php foreach ($_productCollection as $_product): ?>" ~Linha 216 (Format Grid)
	4d - Adicione o código "<?php echo $this->getChildHtml('Facilestore_Quota_ProductQuota') ?>" abaixo de todos este código 
		"<?php echo $this->getPriceHtml($_product, true); ?>"
	
5 - Para configurar o Módulo: Sistema > Configurações > Facile Store Extensions > Parcelas Produtos
