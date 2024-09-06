<?php

namespace App\Http\Controllers\Admin\Radius;

use App\Http\Controllers\Controller;
use App\Http\Requests\Radius\StoreClientRequest;
use App\Http\Requests\Radius\StoreClientsUploadRequest;
use App\Http\Requests\Radius\UpdateClientRequest;
use App\Jobs\ProcessClientUpload;
use App\Models\Client;
use App\Models\ClientBond;
use App\Models\ClientUpload;
use App\Models\Radius\Radcheck;
use App\Models\Radius\Radusergroup;
use App\Services\ClientService;
use Aspera\Spreadsheet\XLSX\Reader;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    public function __construct(
        protected ClientService $clientService
    ){}

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     * @throws AuthorizationException
     */
    public function index(Request $request): Response
    {
        $this->authorize('clients.viewAny', Client::class);

        return Inertia::render('Admin/Radius/Client/Index', array_merge($this->clientService->search($request->term, (int) $request->page), [
            'can' => [
                'create' => $request->user()->can('clients.create'),
                'view' => $request->user()->can('clients.view'),
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
        $this->authorize('clients.create', Client::class);

        return Inertia::render('Admin/Radius/Client/Create', [
            'bonds' => ClientBond::getForSelect(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreClientRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(StoreClientRequest $request): RedirectResponse
    {
        $this->authorize('clients.create', Client::class);

        try {
            // $radcheck = Radcheck::firstOrCreate(
            //     ['username' => $request->registry],
            //     [
            //         'attribute' => 'Cleartext-Password',
            //         'op' => ':=',
            //         'value' => $request->pass,
            //     ]
            // );

            // $bond = ClientBond::find($request->client_bond_id);

            // Radusergroup::updateOrCreate(
            //     ['username' => $request->registry],
            //     [
            //         'groupname' => $bond->radgroupcheck->groupname,
            //         'priority' => $bond->priority
            //     ]
            // );

            // $data = [
            //     ...$request->except(['pass']),
            //     'radcheck_id' => $radcheck->id,
            // ];

            // $this->clientService->create($data);

            // return redirect()->route('clients.show', $this->clientService)->with('flash', ['status' => 'success', 'message' => 'Registro criado com sucesso.']);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('clients.index')->with('flash', ['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Client $client
     * @return Response
     * @throws AuthorizationException
     */
    public function show(Request $request, Client $client): Response
    {
        $this->authorize('clients.view', $client);

        return Inertia::render('Admin/Radius/Client/Show', [
            'data' => $this->clientService->show($client),
            'can' => [
                'update' => $request->user()->can('clients.update'),
                'delete' => $request->user()->can('clients.delete'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Client $client
     * @return Response
     * @throws AuthorizationException
     */
    public function edit(Client $client): Response
    {
        $this->authorize('clients.update', $client);

        return Inertia::render('Admin/Radius/Client/Edit', [
            'client' => $client->load(['client_bond']),
            'bonds' => ClientBond::getForSelect(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateClientRequest $request
     * @param Client $client
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $this->authorize('clients.update', $client);

        try {
            // if ($client->client_bond->id != $request->client_bond_id) {
            //     Radusergroup::updateOrCreate(
            //         ['username' => $request->registry],
            //         [
            //             'groupname' => $client->client_bond->radgroupcheck->groupname,
            //             'priority' => $client->client_bond->priority
            //         ]
            //     );
            // }

            // if ($client->registry != $request->registry) {
            //     $radcheck = Radcheck::where('username', $client->registry)->first();
            //     $radcheck->username = $request->registry;
            //     $radcheck->save();
            // }

            // $client->update($request->validated());

            // return redirect()->route('clients.show', $client)->with('flash', ['status' => 'success', 'message' => 'Registro atualizado com sucesso.']);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('clients.index')->with('flash', ['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Client $client
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Client $client): RedirectResponse
    {
        $this->authorize('clients.delete', $client);

        try {
            // $userGroup = Radusergroup::where('username', $client->registry)->firstOrFail();
            // $userGroup->delete();
            // $user = Radcheck::where('username', $client->registry)->firstOrFail();
            // $user->delete();
            // $client->delete();
            // return redirect()->route('clients.index')->with('flash', ['status' => 'success', 'message' => 'Registro apagado com sucesso.']);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('clients.index')->with('flash', ['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    public function uploadIndex(Request $request): Response
    {
        $this->authorize('clients.upload', Client::class);

        return Inertia::render('Admin/Radius/Client/UploadIndex', array_merge(ClientUpload::search($request), [
            'can' => [
                'create' => $request->user()->can('clients.upload'),
                'view' => $request->user()->can('clients.upload'),
            ],
        ]));
    }

    public function uploadCreate(): Response
    {
        $this->authorize('clients.upload', Client::class);

        return Inertia::render('Admin/Radius/Client/Upload', [
            'bonds' => ClientBond::getForSelect()
        ]);
    }

    public function uploadStore(StoreClientsUploadRequest $request): RedirectResponse
    {
        $file = $request->file('file');
        $fileName = sprintf('%s.%s', Str::random(32), $file->getClientOriginalExtension());
        $file->storeAs('uploads', $fileName);

        $this->clientService->createUpload($request, $fileName);

        return to_route('clients.uploadIndex')->with('flash', ['status' => 'info', 'message' => 'Clientes adicionados a fila de cadastro.']);
    }
}
