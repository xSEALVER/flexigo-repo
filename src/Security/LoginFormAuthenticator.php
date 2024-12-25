<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login'; // Adjust the route name based on your app's login route

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private AuthorizationCheckerInterface $authorizationChecker
    ) {
    }

    /**
     * This method is used to authenticate the user based on the form data.
     */
    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email'); // Get the email from the form

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email); // Store the email in session

        return new Passport(
            new UserBadge($email), // The user badge (email is used to identify the user)
            new PasswordCredentials($request->request->get('password')), // Password credentials
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')), // CSRF token to prevent CSRF attacks
            ]
        );
    }

    /**
     * This method is called on successful authentication. It handles redirection logic.
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Check if there's a target path (where the user was trying to go before being redirected to login)
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath); // Redirect the user back to their target path
        }

        // Get the roles from the authenticated token
        $roles = $token->getRoleNames();

        // Redirect based on the role
        if (in_array('ROLE_ADMIN', $roles, true)) {
            // Redirect to the admin dashboard if the user has ROLE_ADMIN
            return new RedirectResponse($this->urlGenerator->generate('admin'));
        }

        // Default redirect for regular users (not admin)
        return new RedirectResponse($this->urlGenerator->generate('app_movie')); // Adjust to your default page for users
    }

    /**
     * This method returns the login URL if the user needs to be redirected to the login page.
     */
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE); // Redirect to the login route
    }
}
