import React from "react";
import { Head, useForm } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import Panel from "@/Components/Dashboard/Panel";
import Form from "./Components/Form";

function Edit({ nas }) {
    const { data, setData, put, processing, errors } = useForm({
        nasname: nas.nasname,
        shortname: nas.shortname,
        ports: nas.ports,
        secret: nas.secret,
        server: nas.server,
        community: nas.community,
        description: nas.description,
    });

    const onHandleChange = (event) => {
        setData(event.target.name, event.target.value);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        put(route('nas.update', nas.id), {data});
    };

    return (
        <>
            <Head title="Editar Dispositivo (AP)" />
            <AuthenticatedLayout titleChildren={'Editar Dispositivo (AP)'} breadcrumbs={[{ label: 'Dispositivo (AP)', url: route('nas.index') }, { label: nas.description, url: route('nas.show', nas.id) }, { label: 'Editar'}]}>
                <Panel>
                    <Form data={data} errors={errors} processing={processing} onHandleChange={onHandleChange} handleSubmit={handleSubmit} />
                </Panel>
            </AuthenticatedLayout>
        </>
    )
}

export default Edit;

