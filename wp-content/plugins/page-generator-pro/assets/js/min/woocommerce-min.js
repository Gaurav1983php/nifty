jQuery(document).ready((function($){$("input.wc_input_price").removeClass("wc_input_price"),$("input.wc_input_decimal").removeClass("wc_input_decimal");for(let e in page_generator_pro_woocommerce)0!==$("input#"+e).length&&("number"===$("input#"+e).attr("type")&&$("input#"+e).attr("type","text"),$("input#"+e).val(page_generator_pro_woocommerce[e]))}));