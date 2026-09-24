<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(ProfileUpdateRequest $request)
    {
        DB::transaction(function () use ($request) {
            $request->user()->update($request->validated());
            if ($request->user()->esCliente()) {
                $request->user()->cliente?->update(['nombre' => $request->nombre]);
            }
        });

        return back()->with('success', 'Tu perfil se actualizó correctamente.');
    }
}
