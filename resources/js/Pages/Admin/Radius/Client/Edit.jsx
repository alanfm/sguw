import React from "react";
import { Head, useForm } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import Panel from "@/Components/Dashboard/Panel";
import Form from "./Components/Form";

function Edit({ client, bonds }) {
    const { data, setData, put, processing, errors } = useForm({
        name: client.name,
        registry: client.registry,
        cpf: client.cpf,
        birth: client.birth,
        client_bond_id: client.client_bond.id,
        pass: "",
        observations: client.observations,
        email: client.email
    });

    const onHandleChange = (event) => {
        setData(event.target.name, event.target.value);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        put(route('clients.update', client.id), {data});
    };

    return (
        <>
            <Head title="Editar Cliente" />
            <AuthenticatedLayout
                titleChildren={'Editar Cliente'}
                breadcrumbs={[
                    { label: 'Clientes', url: route('clients.index') },
                    { label: client.name, url: route('clients.show', client.id) },
                    { label: 'Editar'}
                ]}
            >
                <Panel>
                    <Form
                        data={data}
                        bonds={bonds}
                        errors={errors}
                        processing={processing}
                        onHandleChange={onHandleChange}
                        handleSubmit={handleSubmit}
                        edit={true}
                    />
                </Panel>
            </AuthenticatedLayout>
        </>
    )
}

export default Edit;

