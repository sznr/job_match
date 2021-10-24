<?php

namespace App\Http\Controllers;

use App\Consts\EntryConst;
use App\Consts\UserConst;
use App\Models\JobOffer;
use App\Models\Entry;
use Illuminate\Support\Facades\Auth;

class EntryController extends Controller
{
   /**
    * Store a newly created resource in storage.
    *
    * @param  \App\Models\JobOffer  $jobOffer
    * @return \Illuminate\Http\Response
    */
   public function store(JobOffer $jobOffer)
   {
       $entry = new Entry([
           'job_offer_id' => $jobOffer->id,
           'user_id' => Auth::guard(UserConst::GUARD)->user()->id,
       ]);

       try {
           // 登録
           $entry->save();
       } catch (\Exception $e) {
           return back()->withInput()
               ->withErrors('エントリーでエラーが発生しました');
       }

       return redirect()
           ->route('job_offers.show', $jobOffer)
           ->with('notice', 'エントリーしました');
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\JobOffer  $jobOffer
    * @param  \App\Models\Entry  $entry
    * @return \Illuminate\Http\Response
    */
   public function destroy(JobOffer $jobOffer, Entry $entry)
   {
       $entry->delete();

       return redirect()->route('job_offers.show', $jobOffer)
           ->with('notice', 'エントリーを取り消しました');
   }


    /**
     *
     * @param  \App\Models\JobOffer  $jobOffer
     * @param  \App\Models\Entry  $entry
     * @return \Illuminate\Http\Response
     */
    public function approval(JobOffer $jobOffer, Entry $entry)
    {
        $entry->status = EntryConst::STATUS_APPROVAL;
        $entry->save();

        return redirect()->route('job_offers.show', $jobOffer)
            ->with('notice', 'エントリーを承認しました');
    }

    /**
     *
     * @param  \App\Models\JobOffer  $jobOffer
     * @param  \App\Models\Entry  $entry
     * @return \Illuminate\Http\Response
     */
    public function reject(JobOffer $jobOffer, Entry $entry)
    {
        $entry->status = EntryConst::STATUS_REJECT;
        $entry->save();

        return redirect()->route('job_offers.show', $jobOffer)
            ->with('notice', 'エントリーを却下しました');
    }
}