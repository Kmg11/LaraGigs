<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    // * All listings
    public function index()
    {
        return view('listings.index', [
            // 'listings' => Listing::all()
            // 'listings' => Listing::latest()->get()
            // 'listings' => Listing::latest()->filter(request(['tag', 'search']))->get()
            // 'listings' => Listing::latest()->filter(request(['tag', 'search']))->simplePaginate(2)
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(6)
        ]);
    }

    // * Single listing
    public function show(Listing $listing)
    {
        return view('listings.show', [
            'listing' => $listing
        ]);
    }

    // * Create listing
    public function create()
    {
        return view('listings.create');
    }

    // * Store listing
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'title' => 'required',
            'location' => 'required',
            'website' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required',
        ]);

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $formFields["user_id"] = auth()->id();

        Listing::create($formFields);

        return redirect('/')->with('message', 'Listing created successfully!');
    }

    // * Edit listing
    public function edit(Listing $listing)
    {
        return view('listings.edit', [
            'listing' => $listing
        ]);
    }

    // * Update listing
    public function update(Request $request, Listing $listing)
    {
        // * Validate user have access to edit this listing
        if ($request->user()->id !== $listing->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $formFields = $request->validate([
            'title' => 'required',
            'location' => 'required',
            'website' => 'required',
            'company' => ['required'],
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required',
        ]);

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $listing->update($formFields);

        return redirect('/listings/' . $listing->id)->with('message', 'Listing updated successfully!');
    }

    // * Delete listing
    public function destroy(Listing $listing)
    {
        // * Validate user have access to delete this listing
        if (request()->user()->id !== $listing->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $listing->delete();

        return redirect('/')->with('message', 'Listing deleted successfully!');
    }

    // * Manage listings
    public function manage()
    {
        return view('listings.manage', [
            'listings' => auth()->user()->listings
        ]);
    }
}
