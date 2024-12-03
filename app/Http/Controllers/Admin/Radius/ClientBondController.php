<?php

namespace App\Http\Controllers\Admin\Radius;

use App\Http\Controllers\Controller;
use App\Http\Requests\Radius\StoreClientBondRequest;
use App\Http\Requests\Radius\UpdateClientBondRequest;
use App\Models\ClientBond;
use App\Models\Discentes\GroupRADIUS as DiscentesGroup;
use App\Models\Servidores\GroupRADIUS as ServidoresGroup;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str;

class ClientBondController extends Controller
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
        $this->authorize('bonds.viewAny', ClientBond::class);

        return Inertia::render('Admin/Radius/Bond/Index', array_merge(ClientBond::search($request), [
            'can' => [
                'create' => $request->user()->can('bonds.create'),
                'view' => $request->user()->can('bonds.view'),
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
        $this->authorize('bonds.create', ClientBond::class);

        return Inertia::render('Admin/Radius/Bond/Create', [
            'servers' => [
                ['id' => ClientBond::SERVIDORES, 'name' => 'Servidores'],
                ['id' => ClientBond::DISCENTES, 'name' => 'Discentes'],
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreClientRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(StoreClientBondRequest $request): RedirectResponse
    {
        $this->authorize('bonds.create', ClientBond::class);

        try {
            $group = [
                'groupname' => Str::of($request->description)->slug('_'),
                'attribute' => 'Simultaneous-Use',
                'op' => ':=',
                'value' => $request->value,
            ];

            if ($request->get('server') == ClientBond::DISCENTES)
                $group = DiscentesGroup::create($group);
            else
                $group = ServidoresGroup::create($group);

            $client = ClientBond::create(array_merge($request->only(['description', 'priority', 'server']), ['radgroupcheck_id' => $group->id]));
            return redirect()->route('bonds.show', $client)->with('flash', ['status' => 'success', 'message' => 'Registro criado com sucesso.']);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('bonds.index')->with('flash', ['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param ClientBond $client_bond
     * @return Response
     * @throws AuthorizationException
     */
    public function show(Request $request, ClientBond $client_bond): Response
    {
        $this->authorize('bonds.view', $client_bond);

        return Inertia::render('Admin/Radius/Bond/Show', [
            'data' => $client_bond->load(['radgroupcheck']),
            'can' => [
                'update' => $request->user()->can('bonds.update'),
                'delete' => $request->user()->can('bonds.delete'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ClientBond $client_bond
     * @return Response
     * @throws AuthorizationException
     */
    public function edit(ClientBond $client_bond): Response
    {
        $this->authorize('bonds.update', $client_bond);

        return Inertia::render('Admin/Radius/Bond/Edit', [
            'bond' => $client_bond->load(['radgroupcheck']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateClientRequest $request
     * @param ClientBond $client_bond
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(UpdateClientBondRequest $request, ClientBond $client_bond): RedirectResponse
    {
        $this->authorize('bonds.update', $client_bond);

        try {
            // Radgroupcheck::updateOrCreate(
            //     ['id' => $client_bond->radgroupcheck_id],
            //     [
            //         'value' => $request->value,
            //         'groupname' => Str::of($request->description)->slug('_')
            //     ]
            // );

            // $client_bond->update($request->only(['description', 'priority']));

            return redirect()->route('bonds.show', $client_bond)->with('flash', ['status' => 'success', 'message' => 'Registro atualizado com sucesso.']);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('bonds.index')->with('flash', ['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ClientBond $client_bond
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(ClientBond $client_bond): RedirectResponse
    {
        $this->authorize('bonds.delete', $client_bond);

        try {
            // $group = Radgroupcheck::where('id', $client_bond->radgroupcheck_id)->firstOrFail();
            // $group->delete();
            // $client_bond->delete();
            return redirect()->route('bonds.index')->with('flash', ['status' => 'success', 'message' => 'Registro apagado com sucesso.']);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('bonds.index')->with('flash', ['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }
}
