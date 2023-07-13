<?php
/**
 * New Relic Monitoring
 * @copyright Scandiweb, Inc. All rights reserved.
 */

namespace ReadyMage\BetterNewRelic\Plugin;

use Magento\Framework\GraphQl\Query\QueryProcessor;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema;
use Magento\Framework\App\RequestInterface;
use JomaShop\NewRelicMonitoring\Helper\NewRelicReportData;

class NewRelicMonitoring
{
    /**
     * @var \Magento\NewRelicReporting\Model\NewRelicWrapper
     */
    private $newRelicWrapper;

    /**
     * @var NewRelicReportData
     */
    private $dataHelper;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * NewRelicMonitoring constructor.
     * @param \Magento\NewRelicReporting\Model\NewRelicWrapper $newRelicWrapper
     * @param \JomaShop\NewRelicMonitoring\Helper\NewRelicReportData $dataHelper
     */
    public function __construct(
        \Magento\NewRelicReporting\Model\NewRelicWrapper       $newRelicWrapper,
        NewRelicReportData                                     $dataHelper,
        RequestInterface                                       $request
    )
    {
        $this->newRelicWrapper = $newRelicWrapper;
        $this->dataHelper = $dataHelper;
        $this->request = $request;
    }

    /**
     * Rename a GraphQl transaction for New Relic before processing it
     * @param QueryProcessor $subject
     * @param Schema $schema
     * @param string $source
     * @param ContextInterface|null $contextValue
     * @param array|null $variableValues
     * @param string|null $operationName
     */
    public function beforeProcess(
        QueryProcessor   $subject,
        Schema           $schema,
        string           $source,
        ContextInterface $contextValue = null,
        array            $variableValues = null,
        string           $operationName = null
    )
    {
        $transactionData = $this->dataHelper->getTransactionData($schema, $source);

        if (empty($transactionData)) {
            return;
        }

        $params = $this->request->getParams();

        if (isset($params['hash'])) {
            $name = NewRelicReportData::PREFIX . 'Query' . NewRelicReportData::BACKSLASH . $params['hash'];
        } else {
            $name = $transactionData['transactionName'];
        }

        $this->newRelicWrapper->setTransactionName($name);
        $this->newRelicWrapper->addCustomParameter('GraphqlNumberOfFields', $transactionData['fieldCount']);
        $this->newRelicWrapper->addCustomParameter('FieldNames', implode('|', $transactionData['fieldNames']));
    }
}
