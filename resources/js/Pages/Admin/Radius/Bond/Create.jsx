import React from "react";
import { Head, useForm } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import Panel from "@/Components/Dashboard/Panel";
import Form from "./Components/Form";

function Create() {
    const { data, setData, post, processing, errors } = useForm({
        description: "",
        value: "",
        priority: 1,
    });

    const onHandleChange = (event) => {
        setData(event.target.name, event.target.value);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('bonds.store'), {data});
    };

    return (
        <>
            <Head title="Novo Grupo" />
            <AuthenticatedLayout
                titleChildren={'Cadastro de novo Grupo'}
                breadcrumbs={[
                    { label: 'Radius', url: route('bonds.index') },
                    { label: 'Grupos', url: route('bonds.index') },
                    { label: 'Novo', url: route('bonds.create') }
                ]}
            >
                <Panel>
                    <Form
                        data={data}
                        errors={errors}
                        processing={processing}
                        onHandleChange={onHandleChange}
                        handleSubmit={handleSubmit}
                    />
                </Panel>
            </AuthenticatedLayout>
        </>
    )
}

export default Create;

