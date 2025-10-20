<?php

namespace App\Http\Controllers\Base\TemporaryInvitation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Base\TemporaryInvitation\StoreRequest;
use App\Models\Auth\Role;
use App\Models\Base\{
    Store,
    TemporaryInvitation,
};
use App\Notifications\InvitationNotification;
use Illuminate\Support\Facades\{
    Session,
    Log,
    Notification,
    URL
};
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;

class TemporaryInvitationController extends Controller
{
    /**
     * Show the form for creating a new private link.
     */
    public function showCreateLinkForm()
    {
        $stores = Store::all();
        $roles = Role::all();

        return view('base.temporary.create', compact('stores', 'roles'));
    }

    /**
     * Show the link details.
     */
    public function showLink()
    {
        return view('base.temporary.show');
    }
    /**
     * Generate an invitation link.
     */
    public function generateLink(StoreRequest $request)
    {
        try {
            $token = Str::random(20);

            if ($request->expiration === 'custom') {
                $expiration = Carbon::createFromFormat('Y-m-d', $request->custom_expiration)->endOfDay();
            } else {
                //For predefined values, use 'addMinutes' or 'addHours' as appropriate.
                $expiration = now();
                $expiration->addHours($request->expiration);
            }

            //Generates temporarily signed link
            $invitationLink = URL::temporarySignedRoute(
                'register',
                $expiration,
                ['invitation' => $token],
            );

            // Creates the invitation link record in the database
            TemporaryInvitation::create([
                'token' => $token,
                'uses_left' => 1,
                'expiration' => $expiration->toDateTimeString(),
                'invitation_type' =>  $request->invitation_type,
                'store_id' => $request->store_id,
                'email' => $request->email,
                'roles' => join('-', $request->roles)
            ]);

            Session::flash('invitation_link', $invitationLink);

            Notification::route('mail', $request->email)->notify(new InvitationNotification($invitationLink));

            Log::info('Link generated successfully', ['token' => $token, 'link' => $invitationLink . ' | ' . __METHOD__]);

            return redirect()->route('base.temporary.show');
        } catch (Exception $e) {
            Log::channel('fatal')->error('Error while generating invitation link | ' . $e->getMessage() . ' | ' . __METHOD__);
            Session::flash('error', 'Error al generar el enlace de invitación, por favor inténtelo más tarde');

            return redirect()->back();
        }
    }
}
