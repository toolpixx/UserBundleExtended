<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 10.01.15
 * Time: 21:52
 */
namespace Avl\UserBundle\Controller;

use FOS\UserBundle\Controller\ProfileController as BaseProfileController;

use Avl\UserBundle\Entity\OAuth;
use Avl\UserBundle\Form\Type\OAuthType;
use Avl\UserBundle\Controller\Controller as BaseController;
use Facebook\GraphUser;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\Entities\AccessToken;
use Facebook\FacebookSDKException;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class ProfileController
 * @package Avl\UserBundle\Controller
 *
 * https://graph.facebook.com/oauth/authorize?client_id=831876226858370&scope=publish_stream&redirect_uri=http://localhost:8000&response_type=token
 *
 */
class FacebookProfileController extends BaseController
{
    /**
     * News-Repository
     */
    const OAUTH_REPOSITORY = 'UserBundle:OAuth';

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function authFacebookAction(Request $request)
    {
        $names = array();
        $this->hasGranted(array('ROLE_ADMIN', 'ROLE_CUSTOMER_FBC_MANAGER'));

        // Create the facebook-url
        $this->getFacebookSession();
        $helper = $this->getFacebookUrl();

        // Get the names for the selected accounts
        $entities = $this->getEm()->getRepository(self::OAUTH_REPOSITORY)->findOneByUser($this->getUser());
        $tokenList = $entities->getUnserializeInfo();
        foreach($entities->getAccounts() as $account) {
            $names[$tokenList[$account]['name'].'_'.$account] = array(
                'id' => $account,
                'name' => $tokenList[$account]['name']
            );
        }

        ksort($names);

        return $this->render(
            'UserBundle:Profile:facebook.auth.html.twig',
            array(
                'entities' => $names,
                'facebookUrl' => $helper->getLoginUrl()
            )
        );
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws \Facebook\FacebookRequestException
     */
    public function connectFacebookAction(Request $request)
    {
        $accounts = array();
        $choices = array();
        $infos = array();

        $this->hasGranted(array('ROLE_ADMIN', 'ROLE_CUSTOMER_FBC_MANAGER'));

        $this->getFacebookSession();
        $helper = $this->getFacebookUrl();

        try {
            $session = $helper->getSessionFromRedirect();
        } catch(FacebookRequestException $exception) {
            return $this->redirectOnError($exception->getMessage());
        } catch(\Exception $ex) {
            return $this->redirectOnError($exception->getMessage());
        }

        // If fbsession exists
        if ($session) {

            // Get the accounts from user
            $session = new FacebookSession($session->getAccessToken());

            $accounts = (new FacebookRequest(
                $session, 'GET', '/me/accounts'
            ))->execute()->getGraphObject();

            // Get the userdata
            $user = (new FacebookRequest(
                $session, 'GET', '/me'
            ))->execute()->getGraphObject();

            // Add the users accounts
            $choices[$user->getProperty('id')] = $user->getProperty('name');
            $infos[$user->getProperty('id')] = array(
                'token' => $session->getToken(),
                'name' => $user->getProperty('name')
            );

            // Get accounts if exists
            if (null !== $accounts->getProperty('data')) {
                foreach($accounts->getProperty('data')->asArray() as $account) {
                    $choices[$account->id] = $account->name;
                    $infos[$account->id] = array(
                        'token' => $account->access_token,
                        'name' => $account->name
                    );
                }
            }

            // Sort data for the form
            asort($choices);

            // Get existing accounts
            if ($entity = $this->getEm()->getRepository(self::OAUTH_REPOSITORY)->findOneByUser($this->getUser())) {
                $accountEntities = $entity->getAccounts();
            } else {
                // If no entity for this user exists
                // we create it
                $entity = new OAuth($this->getUser());
                $this->getEm()->persist($entity);
                $this->getEm()->flush();
            }

            // Set tokens to the form, because we need
            // them at the post-request.
            $entity->setInfo(serialize($infos));

            $form = $this->createForm(new OAuthType($choices, $accountEntities), $entity);
            $this->get('session')->getFlashBag()->add('notice', 'facebook.flash.connect.success');
            return $this->render(
                'UserBundle:Profile:facebook.accounts.html.twig',
                array(
                    'facebookUrl' => $helper->getLoginUrl(),
                    'entity' => $entity,
                    'form' => $form->createView()
                )
            );
        }

        return $this->redirectOnError('facebook.flash.connect.error');
    }

    public function testFacebookAction(Request $request)
    {
        $this->hasGranted(array('ROLE_ADMIN', 'ROLE_CUSTOMER_FBC_MANAGER'));
        $this->getFacebookSession();
        $entity = $this->getEm()->getRepository(self::OAUTH_REPOSITORY)->findOneByUser($this->getUser());
        $tokenList = $entity->getUnserializeInfo();

        try {
            foreach($entity->getAccounts() as $account) {

                $session = new FacebookSession($tokenList[$account]['token']);
                $response = (new FacebookRequest(
                    $session, 'POST', '/'.$account.'/feed', array(
                        'message' => '',
                        'link' => '',
                        'picture' => '',
                        'description' => ''
                    )
                ))->execute()->getGraphObject();
                dump("Posted with id: " . $response->getProperty('id'));
                $user_profile = (new FacebookRequest(
                    $session, 'GET', '/'.$account
                ))->execute()->getGraphObject(GraphUser::className());
                dump($user_profile);
            }
        } catch(FacebookRequestException $ex) {
            return $this->redirectOnError($exception->getMessage());
        } catch(\Exception $ex) {
            return $this->redirectOnError($exception->getMessage());
        }
        exit;


        $response = (new FacebookRequest(
            $session, 'POST', '/211196185740363/milestones',
            array (
                'title' => '',
                'description' => '',
                'start_time' => ''
            )
        ))->execute()->getGraphObject();
        echo "Posted with id: " . $response->getProperty('id');
        dump($response);
        exit;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateFacebookAccountsAction(Request $request)
    {
        $this->hasGranted(array('ROLE_ADMIN', 'ROLE_CUSTOMER_FBC_MANAGER'));
        $this->getFacebookSession();
        $helper = $this->getFacebookUrl();

        // Get the Tokens from the request, because we
        // need the id from it for the choices
        $infos = unserialize($request->get('avl_oauth')['info']);
        foreach ($infos as $key => $val) {
            $choices[$key] = '';
        }
        asort($choices);

        $entity = $this->getEm()->getRepository(self::OAUTH_REPOSITORY)->findOneByUser($this->getUser());
        $form = $this->createForm(new OAuthType($choices), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // Get only the token which the user has selected
            if (sizeof($form["accounts"]->getData()) > 0) {
                foreach($form["accounts"]->getData() as $id) {
                    $infoData[$id] = array(
                        'token' => $infos[$id]['token'],
                        'name' => $infos[$id]['name']
                    );
                }
                $entity->setInfo(serialize($infoData));
            }
            $this->getEm()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'facebook.flash.select.accounts.success');
            return $this->redirect($this->generateUrl('avl_user_profile_auth_facebook'));
        }

        return $this->redirectOnError('facebook.flash.persist.error');
    }

    /**
     * Redirect on error
     */
    private function redirectOnError($message)
    {
        $this->get('session')->getFlashBag()->add('error', $message);
        return new RedirectResponse($this->container->get('router')->generate('fos_user_profile_show'));
    }
    /**
     * Create a FacebookSession
     */
    private function getFacebookSession()
    {
        FacebookSession::setDefaultApplication(
            $this->container->getParameter('fb.app.id'),
            $this->container->getParameter('fb.app.secret')
        );
    }

    /**
     * @return FacebookRedirectLoginHelper
     */
    private function getFacebookUrl()
    {
        return new FacebookRedirectLoginHelper(
            $this->container->getParameter('fb.app.host').
            $this->container->get('router')->generate(
                $this->container->getParameter('fb.app.route')
            )
        );
    }
}
