<?php

namespace common\components;

use Yii;
use \sizeg\jwt\JwtHttpBearerAuth;
use yii\helpers\StringHelper;
use common\models\TeacherClasses;
use common\models\Classes;
use common\models\Teachers;
use common\components\GateKeeperHelper;

class ApiAuth extends JwtHttpBearerAuth
{
    public $indexUrl = '/site/index';

    /**
     * @var array List of action that not need to check access.
     */
    public $allowRoutes = [];

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        $authHeader = $request->getHeaders()->get('Authorization');
        if ($authHeader !== null && preg_match('/^' . $this->schema . '\s+(.*?)$/', $authHeader, $matches)) {
            $token = $this->loadToken($matches[1]);
            if ($token === null) {
                return null;
            }

            if ($this->auth) {
                $identity = call_user_func($this->auth, $token, get_class($this));
            } else {
                $app_id = $token->getClaim('app_id');
                if ($app_id !== Yii::$app->id) {
                    return null;
                }
                $identity = $user->loginByAccessToken($token, get_class($this));
            }

            return $identity;
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $response = $this->response ?: Yii::$app->getResponse();

        try {
            $identity = $this->authenticate(
                $this->user ?: Yii::$app->getUser(),
                $this->request ?: Yii::$app->getRequest(),
                $response
            );
        } catch (\yii\web\UnauthorizedHttpException $e) {
            if ($this->isOptional($action)) {
                return true;
            }

            throw $e;
        }

        if ($this->isOptional($action)) {
            return true;
        }

        if ($identity !== null) {
            $actionId = $action->getUniqueId();
            //echo $actionId;die;
            $user = $identity;
            //print_r($user);die;
            if (!$user) {
                $this->denyAccess($user);
            } else {
                $school = $this->getSchool($user);
                if ($school) {
                    $school_teacher = \common\models\SchoolTeachers::findOne(['teacher_id' => $user->id, 'school_id' => $school->id, 'status' => ACTIVE]);
                    //print_r($school_teacher);
                    //die;
                    if ($school_teacher) {
                        if (GateKeeperHelper::checkRoute('/' . $actionId, Yii::$app->getRequest()->get(), $school_teacher->id)) {
                            return true;
                        } else if (GateKeeperHelper::isAllowedRoute('/' . $actionId, $this->allowRoutes)) {
                            return true;
                        }
                    }
                } else {
                    if (GateKeeperHelper::isAllowedRoute('/' . $actionId, $this->allowRoutes)) {
                        return true;
                    }
                }

                $this->denyAccess($user);
            }

            return true;
        }

        $this->challenge($response);
        $this->handleFailure($response);

        return false;
    }

    /**
     * Denies the access of the user.
     * The default implementation will redirect the user to the login page if he is a guest;
     * if the user is already logged, a 403 HTTP exception will be thrown.
     * @param  User $user the current user
     * @throws ForbiddenHttpException if the user is already logged in.
     */
    protected function denyAccess($user)
    {
        if (!$user) {
            $user->loginRequired();
        } else {
            throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * get school data from request parameter
     * @param Teachers $user (optional)
     * @param \common\models\Schools $school
     */
    private function getSchool($user=null)
    {
        $class_id = Yii::$app->request->post('class_id');
        if ($user){
            $teacher_class = TeacherClasses::findOne(['teacher_id' => $user->id,'class_id' => $class_id, 'status' => ACTIVE]);
            if (!$teacher_class){
                return null;
            }
        }

        $class = \common\models\Classes::findOne(['id' => $class_id, 'status' => ACTIVE]);
        if (!$class) {
            return null;    
        }
        
        return $class->schoolGrade->school;
    }
}
