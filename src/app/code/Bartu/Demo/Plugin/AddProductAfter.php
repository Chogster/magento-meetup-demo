<?php

namespace Bartu\Demo\Plugin;

use Magento\Framework\Message\MessageInterface;

class AddProductAfter
{
    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Customer\Model\Session $session,
        \Magento\Framework\Message\Factory $messageFactory
    ) {
        $this->messageManager = $messageManager;
        $this->session = $session;
        $this->messageFactory = $messageFactory;
    }

    public function afterAddProduct(\Magento\Quote\Model\Quote $subject, $result) {
        if (!$this->session->isLoggedIn() && !$this->messageExists()) {
            $msg = $this->messageFactory->create(MessageInterface::TYPE_WARNING, 'Did you know that you could get a discount with an account?');
            $this->messageManager->addMessage($msg);
        }
        return $result;
    }

    /**
     * Prevent duplicate messages in case messages aren't cleared
     * @return bool
     */
    private function messageExists() {
        $messages = $this->messageManager->getMessages()->getItems();
        $result = false;
        if (!empty($messages)) $result = true;
        return $result;
    }
}
