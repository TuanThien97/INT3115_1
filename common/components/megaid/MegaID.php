<?php
namespace common\components\megaid;

use Yii;
use yii\base\Component;
use yii\httpclient\Client;
use common\components\megaid\Response;
use common\helpers\PhoneHelper;
use common\helpers\SignatureHelper;

/**
 * MegaID component class
 */
class MegaID extends Component {
    const GET_SERVICE_REWARDS_PATH = '/reward/get-service-rewards';
    const GET_USER_REWARDS_PATH = '/reward/get-user-rewards';
    const GET_USER_PATH = '/default/get-user';
    const COLLECT_POINT_PATH = '/reward/collect-point';
    const CONSUME_POINT_PATH = '/reward/consume-point';
    const USE_REWARD_PATH = '/reward/use-reward';

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
    public $service,$serviceApp;

    /**
     * get service's rewards
     * @param int $page (default 1)
     * @return Response
     */
    public function getServiceRewards($page=1){
        $data = ['page' => $page];
        $url = $this->getUrl(static::GET_SERVICE_REWARDS_PATH) . '?' . http_build_query($data);
        $client = new Client(['baseUrl' => $this->endpoint]);
        $mgResponse = $client->createRequest()
            ->setMethod(static::METHOD_GET)
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl($url)
            ->setHeaders($this->getHeaders())
            ->setData($data)->send();
        
        //echo $mgResponse->content;die;

        $response = new Response();
        $response->setIsOk($mgResponse->isOk);
        if ($mgResponse->isOk) {
            $respData = $mgResponse->data;
            if ($respData['code'] == static::CODE_SUCCESS) {
                $response->setIsOk(true);
                $response->setData($respData['data']);
            }else{
                $response->setIsOk(false);
                $response->setError(isset($respData['error']) ? $respData['error'] : 'api errors');
            }
        }else{
            $response->setIsOk(false);
            $response->setError('http errors');
        }
        return $response;
    }

    /**
     * get user point
     * @param string $phoneNumber
     * @return Response
     */
    public function getUserPoint($phoneNumber){
        $phoneNumber = \common\helpers\PhoneHelper::sanitizePrefix($phoneNumber);
        $data = ['user' => $phoneNumber]; //empty
        $url = $this->getUrl(static::GET_USER_PATH).'?'.http_build_query($data);
        $client = new Client(['baseUrl' => $this->endpoint]);
        $mgResponse = $client->createRequest()
            ->setMethod(static::METHOD_GET)
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl($url)
            ->setHeaders($this->getHeaders())
            ->send();

        //echo $mgResponse->content;die;

        $response = new Response();
        $response->setIsOk($mgResponse->isOk);
        if ($mgResponse->isOk) {
            $respData = $mgResponse->data;
            if ($respData['code'] == static::CODE_SUCCESS) {
                $response->setIsOk(true);
                $response->setData(isset($respData['data']['point']) ? intval($respData['data']['point']) : 0);
            } else {
                $response->setIsOk(false);
                $response->setError(isset($respData['error']) ? $respData['error'] : 'api errors');
            }
        } elseif ($mgResponse->statusCode == static::CODE_NOT_FOUND){
            // if user not found then return point 0
            $response->setIsOk(true);
            $response->setData(0);
        } else {
            $response->setIsOk(false);
            $response->setError('http errors');
        }
        return $response;
    }

    /**
     * collect point
     * @param string $phoneNumber
     * @param string $transactionType
     * @param int $point
     * @param mixed $data (optional)
     * @param string $note (optional)
     * @return Response
     */
    public function collectPoint($phoneNumber, $transactionType, $point, $data=null, $note=null){
        $params = [
            'user' => $phoneNumber,
            'service_app' => getenv('MEGAID_SERVICE_APP'),
            'transaction_type' => $transactionType,
            'point' => $point
        ];

        if ($data){
            $params['data'] = $data;
        }
        if ($note) {
            $params['note'] = $note;
        }

        $signature = SignatureHelper::signature(getenv('MEGAID_SECRET_KEY'),$params);
        $params['signature'] = $signature;

        $url = $this->getUrl(static::COLLECT_POINT_PATH);
        $client = new Client(['baseUrl' => $this->endpoint]);
        $mgResponse = $client->createRequest()
            ->setMethod(static::METHOD_POST)
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl($url)
            ->setHeaders($this->getHeaders())
            ->setData($params)
            ->send();

        //echo $mgResponse->content;die;

        $response = new Response();
        $response->setIsOk($mgResponse->isOk);
        if ($mgResponse->isOk) {
            $respData = $mgResponse->data;
            if ($respData['code'] == static::CODE_SUCCESS) {
                $response->setIsOk(true);
                $response->setData($respData['data']);
            } else {
                $response->setIsOk(false);
                $response->setError(isset($respData['error']) ? $respData['error'] : 'api errors');
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
     * consume point
     * @param string $phoneNumber
     * @param string $rewardId
     * @param mixed $data (optional)
     * @param string $note (optional)
     * @return Response
     */
    public function consumePoint($phoneNumber, $rewardId, $data = null, $note = null)
    {
        $params = [
            'user' => $phoneNumber,
            'service_app' => getenv('MEGAID_SERVICE_APP'),
            'reward_id' => $rewardId
        ];

        if ($data) {
            $params['data'] = $data;
        }
        if ($note) {
            $params['note'] = $note;
        }

        $signature = SignatureHelper::signature(getenv('MEGAID_SECRET_KEY'), $params);
        $params['signature'] = $signature;

        $url = $this->getUrl(static::CONSUME_POINT_PATH);
        $client = new Client(['baseUrl' => $this->endpoint]);
        $mgResponse = $client->createRequest()
            ->setMethod(static::METHOD_POST)
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl($url)
            ->setHeaders($this->getHeaders())
            ->setData($params)
            ->send();

        //echo $mgResponse->content;die;

        $response = new Response();
        $response->setIsOk($mgResponse->isOk);
        if ($mgResponse->isOk) {
            $respData = $mgResponse->data;
            if ($respData['code'] == static::CODE_SUCCESS) {
                $response->setIsOk(true);
                $response->setData($respData['data']);
                $response->setCode(static::CODE_SUCCESS);
                $response->setStatus($mgResponse->statusCode);
                $response->setMessage(isset($respData['message']) ? $respData['message'] : null);
            } else {
                $response->setIsOk(false);
                $response->setError(isset($respData['error']) ? $respData['error'] : 'api errors');
                $response->setCode($respData['code']);
                $response->setStatus(isset($respData['status']) ? $respData['status'] : 400);
                $response->setData(isset($respData['data']) ? $respData['data'] : null);
                $response->setMessage(isset($respData['message']) ? $respData['message'] : null);
            }
        } elseif (in_array($mgResponse->statusCode, [501,502,503])) {
            // not enough point
            $respData = $mgResponse->data;
            $code = 500 - $mgResponse->statusCode;
            $response->setIsOk(false);
            $response->setError(isset($respData['message']) ? $respData['message'] : 'http error');
            $response->setCode($code);
            $response->setStatus($mgResponse->statusCode);
            $response->setData(isset($respData['data']) ? $respData['data'] : null);
            $response->setMessage(isset($respData['message']) ? $respData['message'] : null);
        } elseif ($mgResponse->statusCode == static::CODE_NOT_FOUND) {
            $respData = $mgResponse->data;
            $response->setIsOk(false);
            $response->setError(isset($respData['message']) ? $respData['message'] : 'http error');
            $response->setCode(-4);
            $response->setStatus(static::CODE_NOT_FOUND);
            $response->setData(isset($respData['data']) ? $respData['data'] : null);
            $response->setMessage(isset($respData['message']) ? $respData['message'] : null);
        } else {
            $response->setIsOk(false);
            $response->setError('http errors');
            $response->setCode(-2);
            $response->setStatus(500);
        }
        return $response;
    }

    /**
     * get user's rewards
     * @param string $phoneNumber
     * @param string $type (default active)
     * @param int $page (default 1)
     * @return Response
     */
    public function getUserRewards($phoneNumber,$type='active',$page=1){
        $phoneNumber = \common\helpers\PhoneHelper::sanitizePrefix($phoneNumber);
        $data = ['user' => $phoneNumber,'type'=>$type,'page'=>$page]; //empty
        $url = $this->getUrl(static::GET_USER_REWARDS_PATH) . '?' . http_build_query($data);
        $client = new Client(['baseUrl' => $this->endpoint]);
        $mgResponse = $client->createRequest()
            ->setMethod(static::METHOD_GET)
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl($url)
            ->setHeaders($this->getHeaders())
            ->send();

        //echo $mgResponse->content;die;

        $response = new Response();
        $response->setIsOk($mgResponse->isOk);
        if ($mgResponse->isOk) {
            $respData = $mgResponse->data;
            if ($respData['code'] == static::CODE_SUCCESS) {
                $response->setIsOk(true);
                $response->setData(isset($respData['data']) ? $respData['data']: null);
            } else {
                $response->setIsOk(false);
                $response->setError(isset($respData['error']) ? $respData['error'] : 'api errors');
            }
        } elseif ($mgResponse->statusCode == static::CODE_NOT_FOUND) {
            $response->setIsOk(false);
            $response->setData(isset($respData['data']) ? $respData['data'] : null);
        } else {
            $response->setIsOk(false);
            $response->setError('http errors');
        }
        return $response;
    }

    /**
     * use reward
     * @param string $phoneNumber
     * @param string $userRewardId
     * @return Response
     */
    public function useReward($phoneNumber, $userRewardId){
        $params = [
            'user' => $phoneNumber,
            'service_app' => getenv('MEGAID_SERVICE_APP'),
            'user_reward_id' => $userRewardId
        ];

        $signature = SignatureHelper::signature(getenv('MEGAID_SECRET_KEY'), $params);
        $params['signature'] = $signature;

        $url = $this->getUrl(static::USE_REWARD_PATH);
        $client = new Client(['baseUrl' => $this->endpoint]);
        $mgResponse = $client->createRequest()
            ->setMethod(static::METHOD_POST)
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl($url)
            ->setHeaders($this->getHeaders())
            ->setData($params)
            ->send();

        $response = new Response();
        $response->setIsOk($mgResponse->isOk);
        if ($mgResponse->isOk) {
            $respData = $mgResponse->data;
            if ($respData['code'] == static::CODE_SUCCESS) {
                $response->setIsOk(true);
                $response->setData(isset($respData['data']) ? $respData['data'] : null);
                $response->setCode(static::CODE_SUCCESS);
                $response->setStatus($mgResponse->statusCode);
                $response->setMessage(isset($respData['message']) ? $respData['message'] : null);
            } else {
                $response->setIsOk(false);
                $response->setError(isset($respData['error']) ? $respData['error'] : 'api errors');
                $response->setCode($respData['code']);
                $response->setStatus(isset($respData['status']) ? $respData['status'] : 400);
                $response->setData(isset($respData['data']) ? $respData['data'] : null);
                $response->setMessage(isset($respData['message']) ? $respData['message'] : null);
            }
        } elseif ($mgResponse->statusCode == static::CODE_NOT_FOUND) {
            $respData = $mgResponse->data;
            $response->setIsOk(false);
            $response->setError(isset($respData['message']) ? $respData['message'] : 'http error');
            $response->setCode(-4);
            $response->setStatus(static::CODE_NOT_FOUND);
            $response->setData(isset($respData['data']) ? $respData['data'] : null);
            $response->setMessage(isset($respData['message']) ? $respData['message'] : null);
        } else {
            $response->setIsOk(false);
            $response->setError('http errors');
            $response->setCode(-2);
            $response->setStatus(500);
        }
        return $response;
        
    }

    /**
     * get url from path
     * @param string $path
     */
    private function getUrl($path){
        return $this->version . $path;
    }

    /**
     * gen request headers 
     */
    private function getHeaders(){
        return [
            'Authorization' => 'Bearer ' . $this->accessKey
        ];
    }
}