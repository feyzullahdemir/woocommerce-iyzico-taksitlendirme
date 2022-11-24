<?php
/*
Plugin Name: iyzico taksitlendirme
Plugin URI: https://wordpress.org/plugins/iyzi-taksit-new
Description: Ürün için Taksitlendirme tablosunu gösterir
Version: 1.0.0
Author: Feyzullah Demir
Author URI: https:feyzullahdemir.com.tr
License: GNU
*/


add_filter('woocommerce_product_tabs', 'woo_new_product_tab');
function woo_new_product_tab($tabs)
{

    // Adds the new tab
    $tabs['test_tab'] = array(
        'title' => __('Taksit Seçenekleri', 'woocommerce'),
        'priority' => 50,
        'callback' => 'woo_new_product_tab_content'
    );
    return $tabs;
}

function woo_new_product_tab_content()
{
    //Include external resources
    require_once('config.php');
    require_once ('style.css');

    //Get product price
    global $product;

    $realPrice = ( $product->get_display_price() );



    // The new tab content
    echo '<h2>Taksit Seçenekleri</h2>';
    if($realPrice && $realPrice != '0' && $realPrice != '0.0' && $realPrice != '0.00' && $realPrice != false){

    # create request class
    $request = new \Iyzipay\Request\RetrieveInstallmentInfoRequest();
    $request->setLocale(\Iyzipay\Model\Locale::TR);
    $request->setConversationId(uniqid());

    $request->setPrice("$realPrice");

    # make request
    $installmentInfo = \Iyzipay\Model\InstallmentInfo::retrieve($request, Config::options());

    # json decode
    $result = $installmentInfo->getRawResult();
    $result = json_decode($result);

    $data['statusApi'] = $installmentInfo->getStatus();

    if($data['statusApi'] != 'success')
        exit('Error');

    $result = $result->installmentDetails;
    $data['result'] = $result;

    # declaration
    $data['installments'] = array();
    $data['banks'] 	= array();
    $data['totalPrices'] = array();
    $data['installmentPrice'] = array();

    # create table
    echo ('<div class="cards">');

    # data parsing
    foreach ($result as $key => $dataParser) {

        $data['banks'][$key] = esc_js($dataParser->cardFamilyName);
        $div = '<div class="card card--';
        $div2 = '">';
        echo (($div . $data['banks'][$key] . $div2));

        $divIn1 = '<div class="card__head">';
        $divIn2 = '</div>';
        $divIn3 = '<div class="card__content">';
        echo(($divIn1 . $data['banks'][$key] . $divIn2 . $divIn3));

        $div1 = '<div class="card__cell card__cell--value">';
        $div2 = '</div>';

        echo('<div class="card__col card__col--installment"><div class="card__cell card__cell--head">Taksit</div>');
        foreach ($dataParser->installmentPrices as $key => $installment) {
            $data['installments'][$key] = esc_js($installment->installmentNumber);
            echo (($div1 . $data['installments'][$key] . $div2));
        }
        echo('</div>');

        echo('<div class="card__col card__col--default"><div class="card__cell card__cell--head">Tutar</div>');
        foreach ($dataParser->installmentPrices as $key => $installment) {

            $data['installmentPrice'][$key] = esc_js($installment->installmentPrice);
            echo (($div1 . $data['installmentPrice'][$key] . $div2));

        }
        echo('</div>');

        echo('<div class="card__col card__col--default"><div class="card__cell card__cell--head">Toplam</div>');
        foreach ($dataParser->installmentPrices as $key => $installment) {

            $data['totalPrices'][$key] = esc_js($installment->totalPrice);
            echo (($div1 . $data['totalPrices'][$key] . $div2));

        }
        echo('</div>');
    echo ('</div></div>');
    }
echo('</div>');
 }
 else {
   exit;
 }
}
?>
