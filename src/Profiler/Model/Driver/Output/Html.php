<?php
namespace Mirasvit\Profiler\Model\Driver\Output;

use Magento\Framework\Profiler\Driver\Standard\Stat;
use Magento\Framework\Profiler\Driver\Standard\OutputInterface;
use Magento\Framework\App\ObjectManager;

class Html implements OutputInterface
{
    /**
     * {@inheritdoc}
     */
    public function display(Stat $stat)
    {
        $objectManager = ObjectManager::getInstance();

        /** @var \Mirasvit\Profiler\Model\Config $config */
        $config = $objectManager->get('\Mirasvit\Profiler\Model\Config');

        if (!$config->isEnabled()) {
            return;
        }

        /** @var \Magento\Framework\View\LayoutInterface $layout */
        $layout = $objectManager->create('\Magento\Framework\View\LayoutInterface');

        $context = $objectManager->get('\Mirasvit\Profiler\Block\Context');
        $context->setProfilerStat($stat);

        $storage = $objectManager->get('\Mirasvit\Profiler\Model\Storage');
        $storage->dump();

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';

        if (!$isAjax) {
            echo $layout->createBlock('\Mirasvit\Profiler\Block\Container')
                ->toHtml();
        }
    }
}