import React from "react";
import { Head, useForm } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import Panel from "@/Components/Dashboard/Panel";
import Form from "./Components/Form";

function Create() {
    const { data, setData, post, processing, errors } = useForm({
        nasname: "",
        shortname: "",
        ports: 1812,
        secret: "",
        server: "",
        community: "",
        description: "RADIUS Client",
    });

    const onHandleChange = (event) => {
        setData(event.target.name, event.target.value);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('nas.store'), {data});
    };

    return (
        <>
            <Head title="Novo dispositivo (AP)" />
            <AuthenticatedLayout titleChildren={'Cadastro de novo Dispositivo (AP)'} breadcrumbs={[{ label: 'Dispositivos', url: route('nas.index') }, { label: 'Novo', url: route('nas.create') }]}>
                <Panel>
                    <Form data={data} errors={errors} processing={processing} onHandleChange={onHandleChange} handleSubmit={handleSubmit} />
                </Panel>
            </AuthenticatedLayout>
        </>
    )
}

export default Create;

