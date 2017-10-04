<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FlashMessenger extends AbstractHelper
{
    /**
     * @var \Zend\Mvc\Plugin\FlashMessenger\FlashMessenger
     */
    private $flashMessenger;

    public function __construct($flashMessenger)
    {
        $this->flashMessenger = $flashMessenger;
    }

    public function flashMessenger()
    {
        return $this->flashMessenger;
    }

}
