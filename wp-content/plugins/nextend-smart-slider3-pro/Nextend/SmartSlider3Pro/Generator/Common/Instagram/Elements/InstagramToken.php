<?php

namespace Nextend\SmartSlider3Pro\Generator\Common\Instagram\Elements;

use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Request\Request;


class InstagramToken extends Text {

    protected function fetchElement() {

        $authUrl = $this->getForm()
                        ->createAjaxUrl(array(
                            "generator/getAuthUrl",
                            array(
                                'group' => Request::$REQUEST->getVar('group'),
                                'type'  => Request::$REQUEST->getVar('type')
                            )
                        ));

        Js::addInline('new _N2.FormElementInstagramToken("' . $this->fieldID . '", "' . $authUrl . '");');

        return parent::fetchElement();
    }

    protected function post() {
        return '<a class="n2_field_text__choose_text" href="#">' . n2_('Request token') . '</a>';
    }
}


