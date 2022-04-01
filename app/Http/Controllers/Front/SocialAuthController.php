<?php

namespace App\Http\Controllers\Front;


use App\Http\Controllers\Controller;
use App\Models\FrontUser;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleFacebookCallback()
    {

        //$user = Socialite::driver('facebook')->stateless()->user();

        $user = Socialite::driver('facebook')->stateless()->fields([
            'first_name', 'last_name', 'email'
        ])->user();

        $authUser = $this->findOrCreateFacebookUser($user);

        $response = null;
        if ($authUser != false) {
            $response = FrontUser::findOrFail($authUser->id);

            if ($response)
                Session::put('session-front-user', $response->id);
        }else {
            return redirect('/' . $this->lang()['lang'] . '/')->with('email-exist', 'Email exist');
            Session::forget('email-exist');
        }

        return redirect('/' . $this->lang()['lang'] . '/');
        /*}*/
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $facebookUser
     * @return mixed
     */
    public function findOrCreateFacebookUser($facebookUser)
    {
        if ($authUser = FrontUser::where('facebook_id', $facebookUser->id)->where('email', $facebookUser->email)->first()) {
            FrontUser::where('facebook_id', $facebookUser->id)
                ->where('email', $facebookUser->email)
                ->update([
                    'name' => $facebookUser->user['first_name'],
                    'last_name' => $facebookUser->user['last_name'],
                ]);

            return $authUser;
        }

        $check_email = FrontUser::where('email', $facebookUser->getEmail())->first();

        if ($check_email)
            return false;

        $authUser = new FrontUser();
        $authUser->facebook_id = $facebookUser->id;
        $authUser->email = $facebookUser->email;
        $authUser->name = $facebookUser->user['first_name'];
        $authUser->last_name = $facebookUser->user['last_name'];
        $authUser->save();

        return $authUser;
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->stateless()->user();


            $authUser = $this->findOrCreateGoogleUser($user);

            $response = null;
            if ($authUser != false) {
                $response = FrontUser::findOrFail($authUser->id);

                if ($response)
                    Session::put('session-front-user', $response->id);
            }else {
                return redirect('/' . $this->lang()['lang'] . '/')->with('email-exist', 'Email exist');
                Session::forget('email-exist');
            }

            return redirect('/' . $this->lang()['lang'] . '/');
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $googleUser
     * @return mixed
     */
    public function findOrCreateGoogleUser($googleUser)
    {
        if ($authUser = FrontUser::where('google_id', $googleUser->id)->where('email', $googleUser->email)->first()) {
            FrontUser::where('google_id', $googleUser->id)
                ->where('email', $googleUser->email)
                ->update([
                    'name' => $googleUser->user['given_name'],
                    'last_name' => $googleUser->user['family_name'],
                ]);

            return $authUser;
        }

        $check_email = FrontUser::where('email', $googleUser->getEmail())->first();

        if ($check_email)
            return false;

        $authUser = new FrontUser();
        $authUser->google_id = $googleUser->id;
        $authUser->email = $googleUser->email;
        $authUser->name = $googleUser->user['given_name'];
        $authUser->last_name = $googleUser->user['family_name'];
        $authUser->save();

        return $authUser;
    }


}