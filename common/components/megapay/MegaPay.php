<?php
namespace common\components\megapay;

use Yii;
use yii\base\Component;
use yii\httpclient\Client;
use common\components\megapay\Response;
use common\helpers\PhoneHelper;
use common\helpers\SignatureHelper;

/**
 * MegaPay component class
 */
class MegaPay extends Component {
    const CREATE_TRANSACTION_PATH = '/transactions/create';
    const TRANSACTION_STATUS_PATH = '/transactions/status';
    const CREATE_REFUND_PATH = '/transactions/refund';
    const REFUND_STATUS_PATH = '/transactions/refund-status';

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    const CODE_SUCCESS = 0;
    const CODE_NOT_FOUND = 404;

    /**
     * endpoint URL, access key, secret configuration
     */
    public $endpoint, $version, $accessKey, $secretKey;
    /**
     * service, service app configuration
     */
    public $service, $serviceApp;

    /**
     * create transaction
     * @param string $paymentGw
     * @param string $transactionType
     * @param string $merchantId
     * @param string $amount
     * @param string $orderId
     * @param string $orderInfo
     * @param string $additionalData
     * @param string $returnUrl
     * @param string $notifyUrl
     * @return Response
     */
    public function createTransaction($paymentGw, $transactionType, $merchantId, $amount, $orderId, $orderInfo, $additionalData, $returnUrl, $notifyUrl){
        $params = [
            'payment_gw' => $paymentGw,
            'service_app' => $this->serviceApp,
            'transaction_type' => $transactionType,
            'merchant_id' => $merchantId,
            'amount' => (string) $amount,
            'order_id' => (string) $orderId,
            'order_info' => $orderInfo,
            'additional_data' => $additionalData,
            'return_url' => $returnUrl,
            'notify_url' => $notifyUrl,
        ];
        $signature = SignatureHelper::signature($this->secretKey, $params);
        $params['signature'] = $signature;

        $client = new Client(['baseUrl' => $this->endpoint]);
        $url = $this->getUrl(static::CREATE_TRANSACTION_PATH);
        $mgResponse = $client->createRequest()
            ->setMethod(static::METHOD_POST)
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl($url)
            ->setHeaders($this->getHeaders())
            ->setData($params)->send();

        $response = new Response();
        $response->setIsOk($mgResponse->isOk);
        if ($mgResponse->isOk) {
            $respData = $mgResponse->data;
            if ($respData['code'] == static::CODE_SUCCESS) {
                $response->setIsOk(true);
                $response->setData(isset($respData['data']) ? $respData['data'] : null);
                $response->setCode(isset($respData['code']) ? $respData['code'] : 0);
                $response->setStatus(isset($respData['status']) ? $respData['status'] : 200);
                $response->setMessage(isset($respData['message']) ? $respData['message'] : 'success');
            } else {
                $response->setIsOk(false);
                $response->setData(isset($respData['data']) ? $respData['data'] : null);
                $response->setError(isset($respData['error']) ? $respData['error'] : 'api errors');
                $response->setCode(isset($respData['code']) ? $respData['code'] : -1);
                $response->setStatus(isset($respData['status']) ? $respData['status'] : 501);
                $response->setMessage(isset($respData['message']) ? $respData['message'] : 'api errors');
            }
        } elseif ($mgResponse->statusCode == static::CODE_NOT_FOUND) {
            $respData = $mgResponse->data;
            $response->setIsOk(false);
            $response->setError(isset($respData['message']) ? $respData['message'] : 'http error');
        } else {
            $response->setIsOk(false);
            $response->setError('http errors');
        }
        return $response;
    }


    /**
     * get url from path
     * @param string $path
     */
    private function getUrl($path)
    {
        return $this->version . $path;
    }

    /**
     * gen request headers 
     */
    private function getHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . $this->accessKey
        ];
    }


}