<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\WithdrawalApproved as Approval;
use App\Notifications\WithdrawalApproved as ApprovalNotification;
use App\Notifications\WithdrawalRejected as RejectionNotification;
use App\Models\WithdrawRequest;
use App\Services\TransactionService;
use App\Models\User;
use App\Enums\TransactionType;

class WithdrawalApproved
{
    /**
     * Create the event listener.
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Approval $event): void {
        $request = $event->request;
        $approved = $event->approved;
        $user = User::where('id', $event->request->user_id)->first();

        if($user != NULL) {
            if($approved == TRUE && $request->trans_id == NULL) {
                $req = WithdrawRequest::where('id', $request->id)->first();
                if($req->amount > $user->wallet) {
                    return;
                }
                $account = $user->account->where('type', 'wallet')->first();
                $service = new TransactionService();
                $debit_id = $service->debitAccount($account, $req->amount,NULL, "Wallet Withdrawal", TransactionType::WALLET_WITHDRAWAL);
                $req->status = "approved";
                $req->trans_id = $debit_id;
                $req->save();
                if(app()->environment('production')){
                    $notification = new ApprovalNotification($user->name, $request->amount);
                    $user->notify($notification);
                }
            } else {
                $req->status = "cancelled";
                $req->save();
                if(app()->environment('production')){
                    $notification = new RejectionNotification($user->username, $request->amount); //($event->approved === true) ? new ApprovalNotification($request->name, $request->amount) : ;
                    $user->notify($notification);
                }
                
            }
        }
    }
}
