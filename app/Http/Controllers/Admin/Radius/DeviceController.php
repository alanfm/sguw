<?php

namespace App\Http\Controllers\Admin\Radius;

use App\Http\Controllers\Controller;
use App\Http\Requests\Radius\StoreNasRequest;
use App\Http\Requests\Radius\UpdateNasRequest;
use App\Models\Device;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DeviceController extends Controller
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
        $this->authorize('devices.viewAny', Device::class);

        return Inertia::render('Admin/Radius/Device/Index', array_merge(Device::search($request), [
            'can' => [
                'create' => $request->user()->can('devices.create'),
                'view' => $request->user()->can('devices.view'),
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
        $this->authorize('devices.create');

        return Inertia::render('Admin/Radius/Device/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreNasRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(StoreNasRequest $request): RedirectResponse
    {
        $this->authorize('devices.create');

        try {
            // $device = Device::create($request->validated());
            // return redirect()->route('devices.show', $device)->with('flash', ['status' => 'success', 'message' => 'Registro criado com sucesso.']);
        } catch (Exception $e) {
            return redirect()->route('devices.index')->with('flash', ['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Device $devices
     * @return Response
     * @throws AuthorizationException
     */
    public function show(Request $request, Device $device): Response
    {
        $this->authorize('devices.view', $device);

        return Inertia::render('Admin/Radius/Device/Show', [
            'data' => $device,
            'can' => [
                'update' => $request->user()->can('devices.update'),
                'delete' => $request->user()->can('devices.delete'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Device $devices
     * @return Response
     * @throws AuthorizationException
     */
    public function edit(Device $device): Response
    {
        $this->authorize('devices.update', $device);

        return Inertia::render('Admin/Radius/Device/Edit', [
            'device' => $device
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateNasRequest $request
     * @param Device $device
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(UpdateNasRequest $request, Device $device): RedirectResponse
    {
        $this->authorize('devices.update', $device);

        try {
            // $device->update($request->validated());
            return redirect()->route('devices.show', $device)->with('flash', ['status' => 'success', 'message' => 'Registro atualizado com sucesso.']);
        } catch (Exception $e) {
            return redirect()->route('devices.index')->with('flash', ['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Device $device
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Device $device): RedirectResponse
    {
        $this->authorize('devices.delete', $device);

        try {
            // $device->delete();
            return redirect()->route('devices.index')->with('flash', ['status' => 'success', 'message' => 'Registro apagado com sucesso.']);
        } catch (Exception $e) {
            return redirect()->route('devices.index')->with('flash', ['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }
}
