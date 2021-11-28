<?php

namespace UserBundle\Controller;

use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Provider\Github;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use UserBundle\Document\User;
use UserBundle\Security\OAuthToken;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        $helper = $this->get('security.authentication_utils');
        $error = $helper->getLastAuthenticationError();
        $lastUsername = $helper->getLastUsername();

        return $this->render('UserBundle:Security:login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    public function oauthAction(Request $request)
    {
        if ($request->request->has('vk')) {
            return $this->redirectToRoute('vk_login');
        }
        if ($request->request->has('github')) {
            return $this->redirectToRoute('github_login');
        }

        $e = new AuthenticationException('Неизвестный OAuth провайдер');
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $e);
        return $this->redirectToRoute('login');
    }

    public function vkLoginAction()
    {
        $params = $this->getParameter('oauth.vk');

        $fields = 'photo,first_name,last_name,screen_name';
        $provider = new GenericProvider([
            'clientId'                => $params['id'],
            'clientSecret'            => $params['secret'],
            'redirectUri'             => $params['redirect'],
            'urlAuthorize'            => 'https://oauth.vk.com/authorize',
            'urlAccessToken'          => 'https://oauth.vk.com/access_token',
            'urlResourceOwnerDetails' => 'https://api.vk.com/method/getProfiles?v=5.131&fields=' . $fields
        ]);

        // step 1
        if (!isset($_GET['code'])) {
            $authUrl = $provider->getAuthorizationUrl();
            $_SESSION['oauth2state'] = $provider->getState();
            return $this->redirect($authUrl);

        // ошибочное состояние
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);
            exit('Invalid state');

        // step 2
        } else {
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code'],
            ]);

            try {
                $user = $provider->getResourceOwner($token);
                $userVk = $user->toArray()['response'][0];
            } catch (\Exception $e) {
                echo $e->getMessage();
                exit('Сломалась oauth авторизация с VK...');
            }
        }

        $user = $this->get('my_user_provider')->loadUserByOAuthProvider('vk', $userVk['id'] );

        // register new user
        if (!$user) {
            $name  = $userVk['first_name'] . ' ' . $userVk['last_name'];
            $login = $userVk['screen_name'] ?? uniqid();
            $providers = [
                'vk' => [
                    'id'           => $userVk['id'],
                    'login'        => $login,
                    'avatar_url'   => $userVk['photo'],
                    'name'         => $name,
                ]
            ];

            $login = $this->selectUniqUsername($login);

            $user = new User([
                'providers' => $providers,
                'roles'     => ['ROLE_USER'],
                'username'  => $login,
            ]);

            $this->get('mongo')->wshell->users->insertOne($user);
        }

        $this->get('security.token_storage')->setToken(new OAuthToken($user));

        return $this->redirectToRoute('news');
    }

    public function githubLoginAction(Request $request)
    {
        $params = $this->getParameter('oauth.github');

        $provider = new Github([
            'clientId'     => $params['id'],
            'clientSecret' => $params['secret'],
            'redirectUri'  => $params['redirect'],
        ]);

        // step 1
        if (!isset($_GET['code'])) {
            $authUrl = $provider->getAuthorizationUrl();
            $_SESSION['oauth2state'] = $provider->getState();
            return $this->redirect($authUrl);

        // ошибочное состояние
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);
            exit('Invalid state');

        // step 2
        } else {
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code'],
            ]);

            try {
                $user = $provider->getResourceOwner($token);
                $userGithub = $user->toArray();
            } catch (\Exception $e) {
                exit('Сломалась oauth авторизация с GitHub...');
            }
        }

        $user = $this->get('my_user_provider')->loadUserByOAuthProvider( 'github', $userGithub['id'] );

        // register new user
        if (!$user) {
            $providers = [
                'github' => [
                    'id'           => $userGithub['id'],
                    'login'        => $userGithub['login'],
                    'email'        => $userGithub['email'],
                    'avatar_url'   => $userGithub['avatar_url'],
                    'html_url'     => $userGithub['html_url'],
                    'name'         => $userGithub['name'],
                    'location'     => $userGithub['location'],
                    'public_repos' => $userGithub['public_repos'],
                ]
            ];

            $login = $this->selectUniqUsername($userGithub['login']);

            $user = new User([
                'providers' => $providers,
                'roles'     => ['ROLE_USER'],
                'username'  => $login,
            ]);

            $this->get('mongo')->wshell->users->insertOne($user);
        }

        $this->get('security.token_storage')->setToken(new OAuthToken($user));

        return $this->redirectToRoute('news');
    }

    /**
     * если username занят, нужно подобрать незанятый
     */
    protected function selectUniqUsername($login)
    {
        $suffix = 1;
        $loginWithSuffix = $login;
        while ($this->get('my_user_provider')->loadUserByUsername($loginWithSuffix)) {
            $loginWithSuffix = $login . '_' . $suffix;
            $suffix++;
        }
        return $loginWithSuffix;
    }
}
