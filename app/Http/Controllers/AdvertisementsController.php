<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Models\UserField;

class AdvertisementsController extends Controller
{
    private $rules = [
        'title' => 'required',
        'description' => 'required|min:10|max:500',
        'image' => 'required|file|mimes:png,jpg,webp,svg,jpeg',
        'specialization_id' => 'required',
        'start_date' => 'required|date',
        'end_date' => 'required|date'
    ];

    public function index()
    {
        return view('advertisements.index', [
            'advertisements' => Advertisement::with('fields')->paginate(20),
        ]);
    }

    public function edit($locale, Advertisement $advertisement)
    {
        $user_fields = UserField::with('children')->where('field', 'general_specialization')->get();
        return view('advertisements.edit', [
            'advertisement' => $advertisement,
            'user_fields' => $user_fields
        ]);
    }

    public function create()
    {
        $user_fields = UserField::with('children')->where('field', 'general_specialization')->get();
        return view('advertisements.create', [
            'user_fields' => $user_fields,
        ]);
    }

    public function store($locale, Request $request)
    {
        $validated = $request->validate($this->rules);
        $validated['user_id'] = auth()->id();

        if($request->has('image'))
        {
            $image = $request->file('image');
            $filename = $image->getClientOriginalName();
            $path = $image->store('vertisements', 'public');

            $validated['image'] = $path;
        }
        $advertisement = Advertisement::create($validated);

        $advertisement->fields()->sync( $request->input('specialization_id') );

        return redirect()->route('advertisements.create', app()->getLocale())->with('success', 'Advertisement has been created');
    }

    public function update($locale, Advertisement $advertisement)
    {
        $this->rules['image'] = 'nullable|file|mimes:png,jpg,webp,svg,jpeg';

        $validated = request()->validate($this->rules);
        if(request()->has('image'))
        {
            $image = request()->file('image');
            $filename = $image->getClientOriginalName();
            $path = $image->store('vertisements', 'public');

            $validated['image'] = $path;
        }
        $advertisement->update($validated);

        $advertisement->fields()->sync( request()->input('specialization_id') );
        return redirect()->route('advertisements.edit', ['advertisement' => $advertisement, 'locale' => app()->getLocale()])->with('success', 'Advertisement has been updated');
    }

    public function destroy($locale, Advertisement $advertisement)
    {
        $advertisement->delete();
        return redirect()->route('advertisements.index', ['locale' => app()->getLocale()])->with('success', 'Advertisement has been deleted');
    }

    public function check_user_ads()
    {
        // return the active ad for user.
        $user = auth()->user();
        $now = now()->toDateString();

        $ad = $user->advertisements()
        ->where('start_date', '<=', $now)
        ->where('end_date', '>=', $now)
        ->where('seen', 0)
        ->orderBy('id', 'ASC')
        ->first();

        return response()->json(['ad' => $ad]);
    }

    public function check_ad_seen()
    {
        $user = auth()->user();
        $id = $user->id;
        $ad_id = request()->input('ad_id');

        $user->advertisements()
        ->where('advertisement_user.advertisement_id', $ad_id)
        ->where('advertisement_user.user_id', $id)
        ->update(['seen' => 1]);

        return response()->json(['success' => 1]);
    }

}
