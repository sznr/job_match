<x-app-layout>
    <div class="container lg:w-3/4 md:w-4/5 w-11/12 mx-auto my-8 px-4 py-4 bg-white shadow-md">

        <x-flash-message :message="session('notice')" />
        <x-validation-errors :errors="$errors" />

        <article class="mb-2">
            <div class="flex justify-between text-sm">
                <div class="flex item-center">
                    <div class="border border-gray-900 px-2 h-7 leading-7 rounded-full">{{ $jobOffer->occupation->name }}</div>
                </div>
                <div>
                    <span>on {{ $jobOffer->created_at->format('Y-m-d') }}</span>
                    <span class="inline-block mx-1">|</span>
                    <span>{{ $jobOffer->jobOfferViews->count() }} views</span>
                </div>
            </div>
            <p class="text-gray-700 text-base text-right">応募期限 :{{ $jobOffer->due_date }}</p>
            <h2 class="font-bold font-sans break-normal text-gray-900 pt-6 pb-1 text-3xl md:text-4xl">
                {{ $jobOffer->title }}</h2>
            <div class="flex mt-1 mb-3">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    {{-- <div><img src="{{ Auth::guard('companies')->user()->profile_photo_url }}" alt="" class="h-10 w-10 rounded-full object-cover mr-3"></div> --}}
                    <div><img src="{{ $jobOffer->company->profile_photo_url }}" alt="" class="h-10 w-10 rounded-full object-cover mr-3"></div>
                @endif
                <h3 class="text-lg h-10 leading-10">{{ $jobOffer->company->name }}</h3>
            </div>
            <p class="text-gray-700 text-base">{!! nl2br(e($jobOffer->description)) !!}</p>
        </article>
        <div class="flex flex-col sm:flex-row items-center sm:justify-end text-center my-4">
            @if (Auth::guard(UserConst::GUARD)->check())
                @if (empty($entry))
                    <form action="{{ route('job_offers.entries.store', $jobOffer) }}" method="post">
                        @csrf
                        <input type="submit" value="エントリー" onclick="if(!confirm('エントリーしますか？')){return false};" class="w-full sm:w-40 bg-gradient-to-r from-indigo-500 to-blue-600 hover:bg-gradient-to-l hover:from-blue-500 hover:to-indigo-600 text-gray-100 p-2 rounded-full tracking-wide font-semibold shadow-lg cursor-pointer transition ease-in duration-500 w-full sm:w-32">
                    </form>
                @else
                    <form action="{{ route('job_offers.entries.destroy', [$jobOffer, $entry]) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="エントリー取消" onclick="if(!confirm('エントリーを取り消しますか？')){return false};" class="w-full sm:w-40 bg-gradient-to-r from-pink-500 to-purple-600 hover:bg-gradient-to-l hover:from-purple-500 hover:to-pink-600 text-gray-100 p-2 rounded-full tracking-wide font-semibold shadow-lg cursor-pointer transition ease-in duration-500 w-full sm:w-32">
                    </form>
                @endif
            @endif
            @if (Auth::guard(CompanyConst::GUARD)->check() &&
                Auth::guard(CompanyConst::GUARD)->user()->can('update', $jobOffer))
                <a href="{{ route('job_offers.edit', $jobOffer) }}" class="bg-gradient-to-r bg-gradient-to-r from-indigo-500 to-blue-600 hover:bg-gradient-to-l hover:from-blue-500 hover:to-indigo-600 text-gray-100 p-2 rounded-full tracking-wide font-semibold shadow-lg cursor-pointer transition ease-in duration-500 w-full sm:w-32 sm:mr-2 mb-2 sm:mb-0">編集</a>
            @endif
            @if (Auth::guard(CompanyConst::GUARD)->check() &&
                Auth::guard(CompanyConst::GUARD)->user()->can('delete', $jobOffer))
                <form action="{{ route('job_offers.destroy', $jobOffer) }}" method="post" class="w-full sm:w-32">
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="削除" onclick="if(!confirm('削除しますか？')){return false};" class="bg-gradient-to-r from-pink-500 to-purple-600 hover:bg-gradient-to-l hover:from-purple-500 hover:to-pink-600 text-gray-100 p-2 rounded-full tracking-wide font-semibold shadow-lg cursor-pointer transition ease-in duration-500 w-full sm:w-32">
                </form>
            @endif
        </div>
        @if (!empty($entries))
            <hr>
            <h2 class="flex justify-center font-bold text-lg my-4">エントリー一覧</h2>
            <div class="">
                <form method="post">
                    @csrf
                    @method('PATCH')
                <table class="min-w-full table-fixed text-center">
                    <thead>
                        <tr class="text-gray-700 ">
                            <th class="w-1/5 px-4 py-2">氏名</th>
                            <th class="w-1/5 px-4 py-2">エントリー日</th>
                            <th class="w-1/5 px-4 py-2">ステータス</th>
                            <th class="w-2/5 px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entries as $e)
                            <tr>
                                <td>{{ $e->user->name }}</td>
                                <td>{{ $e->created_at->format('Y-m-d') }}</td>
                                <td>{{ array_search($e->status, EntryConst::STATUS_LIST) }}</td>
                                <td>
                                    <div class="flex flex-col sm:flex-row items-center sm:justify-end text-center">
                                        <input type="submit" value="承認" formaction="{{ route('job_offers.entries.approval', [$jobOffer, $e]) }}" onclick="if(!confirm('承認しますか？')){return false};" class="w-full sm:w-32 bg-gradient-to-r from-indigo-500 to-blue-600 hover:bg-gradient-to-l hover:from-blue-500 hover:to-indigo-600 text-gray-100 p-2 rounded-full tracking-wide font-semibold shadow-lg cursor-pointer transition ease-in duration-500 w-full sm:w-32">
                                        <input type="submit" value="却下" formaction="{{ route('job_offers.entries.reject', [$jobOffer, $e]) }}" onclick="if(!confirm('却下しますか？')){return false};" class="bg-gradient-to-r from-pink-500 to-purple-600 hover:bg-gradient-to-l hover:from-purple-500 hover:to-pink-600 text-gray-100 p-2 rounded-full tracking-wide font-semibold shadow-lg cursor-pointer transition ease-in duration-500 w-full sm:w-32 ml-2">
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
            </div>
        @endif
    </div>
</x-app-layout>