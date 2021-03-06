<?php /** @noinspection SpellCheckingInspection */

/**
 * Handles payment actions for LHV
 * Based on iPizza platform.
 *
 * Specification: http://www.seb.ee/ari/maksete-kogumine/maksete-kogumine-internetis/tehniline-spetsifikatsioon
 *
 * @author Ivo Kund <ivo@opus.ee>
 * @author Martin Karu <martin@opus.ee>
 * @date 05.08.2013
 */
namespace opus\payment\adapters;

use opus\payment\services\payment\Dataset;
use opus\payment\services\payment\Response;
use yii\helpers\ArrayHelper;

/**
 * Class Lhv
 *
 * @author Ivo Kund <ivo@opus.ee>
 * @package opus\payment\adapters
 */
class Lhv extends AbstractIPizza
{
    /**
     * @inheritdoc
     */
    public $name = 'LHV';
    /**
     * @inheritdoc
     */
    public $charset = 'ISO-8859-1';

    /**
     * @inheritdoc
     */
    public function canHandlePaymentResponse(Response $response)
    {
        $isLhv = 'LHV' == $response->getParam('VK_SND_ID', false);

        return $isLhv && parent::canHandlePaymentResponse($response);
    }

    /**
     * @inheritdoc
     */
    public function getParamMacOrderDefinition()
    {
        return ArrayHelper::merge(
            parent::getParamMacOrderDefinition(),
            array(
                // 'transaction not completed' response with error message
                '1012' => array(
                    'VK_SERVICE',
                    'VK_VERSION',
                    'VK_SND_ID',
                    'VK_STAMP',
                    'VK_AMOUNT',
                    'VK_CURR',
                    'VK_REF',
                    'VK_MSG',
                    'VK_RETURN',
                    'VK_CANCEL',
                    'VK_DATETIME',
                ),
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function addCommonParams(Dataset $dataset)
    {
        parent::addCommonParams($dataset);
        $dataset->setParam('VK_CHARSET', $this->getCharset());
    }
}