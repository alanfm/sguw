<?php

namespace App\Http\Controllers\Admin\Radius;

use App\Http\Controllers\Controller;
use App\Http\Requests\Radius\StoreRadcheckRequest;
use App\Http\Requests\Radius\UpdateRadcheckRequest;
use App\Models\Radius\Radcheck;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RadcheckController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     * @throws AuthorizationException
     */
    public function index(Request $request): Response
    {
        $this->authorize('radcheck.viewAny', Radcheck::class);

        return Inertia::render('Admin/Radius/Radcheck/Index', array_merge(Radcheck::search($request), [
            'can' => [
                'create' => $request->user()->can('radcheck.create'),
                'view' => $request->user()->can('radcheck.view'),
            ],
        ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create(): Response
    {
        $this->authorize('radcheck.create', Radcheck::class);

        return Inertia::render('Admin/Radius/Radcheck/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRadcheckRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(StoreRadcheckRequest $request): RedirectResponse
    {
        $this->authorize('radcheck.create', Radcheck::class);

        try {
            $nas = Radcheck::create($request->validated());
            return redirect()->route('radcheck.show', $nas)->with('flash', ['status' => 'success', 'message' => 'Registro criado com sucesso.']);
        } catch (Exception $e) {
            return redirect()->route('radcheck.index')->with('flash', ['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Radcheck $nas
     * @return Response
     * @throws AuthorizationException
     */
    public function show(Request $request, Radcheck $nas): Response
    {
        $this->authorize('radcheck.view', $nas);

        return Inertia::render('Admin/Radius/Radcheck/Show', [
            'data' => $nas,
            'can' => [
                'update' => $request->user()->can('radcheck.update'),
                'delete' => $request->user()->can('radcheck.delete'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Radcheck $nas
     * @return Response
     * @throws AuthorizationException
     */
    public function edit(Radcheck $nas): Response
    {
        $this->authorize('radcheck.update', $nas);

        return Inertia::render('Admin/Radius/Radcheck/Edit', [
            'nas' => $nas
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRadcheckRequest $request
     * @param Radcheck $nas
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(UpdateRadcheckRequest $request, Radcheck $nas): RedirectResponse
    {
        $this->authorize('radcheck.update', $nas);

        try {
            $nas->update($request->validated());
            return redirect()->route('radcheck.show', $nas)->with('flash', ['status' => 'success', 'message' => 'Registro atualizado com sucesso.']);
        } catch (Exception $e) {
            return redirect()->route('radcheck.index')->with('flash', ['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Radcheck $Radcheck
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Radcheck $nas): RedirectResponse
    {
        $this->authorize('radcheck.delete', $nas);

        try {
            $nas->delete();
            return redirect()->route('radcheck.index')->with('flash', ['status' => 'success', 'message' => 'Registro apagado com sucesso.']);
        } catch (Exception $e) {
            return redirect()->route('radcheck.index')->with('flash', ['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }
}
