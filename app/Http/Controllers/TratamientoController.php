<?php

namespace App\Http\Controllers;

use App\Models\Tratamiento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TratamientoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TratamientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tratamientos = Tratamiento::paginate();

        return view('tratamiento.index', compact('tratamientos'))
            ->with('i', ($request->input('page', 1) - 1) * $tratamientos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tratamiento = new Tratamiento();

        return view('tratamiento.create', compact('tratamiento'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TratamientoRequest $request): RedirectResponse
    {
        Tratamiento::create($request->validated());

        return Redirect::route('tratamientos.index')
            ->with('success', 'Tratamiento created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $tratamiento = Tratamiento::find($id);

        return view('tratamiento.show', compact('tratamiento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $tratamiento = Tratamiento::find($id);

        return view('tratamiento.edit', compact('tratamiento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TratamientoRequest $request, Tratamiento $tratamiento): RedirectResponse
    {
        $tratamiento->update($request->validated());

        return Redirect::route('tratamientos.index')
            ->with('success', 'Tratamiento updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Tratamiento::find($id)->delete();

        return Redirect::route('tratamientos.index')
            ->with('success', 'Tratamiento deleted successfully');
    }
}
