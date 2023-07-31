<?php
/**
 * New Relic Monitoring
 * @copyright Scandiweb, Inc. All rights reserved.
 */

namespace ReadyMage\BetterNewRelic\Plugin;

use Magento\Framework\GraphQl\Query\ErrorHandler as QueryErrorHandler;
use Magento\NewRelicReporting\Model\NewRelicWrapper;

class ErrorHandler
{
    /**
     * @var NewRelicWrapper
     */
    private $newRelicWrapper;

    /**
     * @param NewRelicWrapper $newRelicWrapper
     */
    public function __construct(
        NewRelicWrapper $newRelicWrapper
    ) {
        $this->newRelicWrapper = $newRelicWrapper;
    }

    public function beforeHandle(QueryErrorHandler $errorHandler, array $errors, callable $formatter)
    {
        if (empty($errors)) {
            return;
        }

        foreach ($errors as $error) {
            $previousError = $error->getPrevious();
            if ($previousError instanceof \Exception) {
                $this->newRelicWrapper->reportError($previousError);
            }
        }
    }
}
