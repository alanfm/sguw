import React from "react";
import { Head, useForm } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import Panel from "@/Components/Dashboard/Panel";
import Form from "./Components/Form";

function Create({ bonds }) {
    const { data, setData, post, processing, errors } = useForm({
        name: "",
        registry: "",
        cpf: "",
        birth: "",
        client_bond_id: "",
        pass: "",
        observation: "",
        email: '',
    });

    const onHandleChange = (event) => {
        setData(event.target.name, event.target.value);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('clients.store'), {data});
    };

    return (
        <>
            <Head title="Novo Cliente" />
            <AuthenticatedLayout titleChildren={'Cadastro de novo Cliente'} breadcrumbs={[{ label: 'Clientes', url: route('clients.index') }, { label: 'Novo', url: route('clients.create') }]}>
                <Panel>
                    <Form data={data} bonds={bonds} errors={errors} processing={processing} onHandleChange={onHandleChange} handleSubmit={handleSubmit} edit={false} />
                </Panel>
            </AuthenticatedLayout>
        </>
    )
}

export default Create;

