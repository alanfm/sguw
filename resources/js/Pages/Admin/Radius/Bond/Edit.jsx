import React from "react";
import { Head, useForm } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import Panel from "@/Components/Dashboard/Panel";
import Form from "./Components/Form";

function Edit({ bond }) {
    const { data, setData, put, processing, errors } = useForm({
        description: bond.description,
        priority: bond.priority,
        value: bond.radgroupcheck.value,
    });

    const onHandleChange = (event) => {
        setData(event.target.name, event.target.value);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        put(route('bonds.update', bond.id), {data});
    };

    return (
        <>
            <Head title="Editar Grupo" />
            <AuthenticatedLayout
                titleChildren={'Editar Grupo'}
                breadcrumbs={[
                    { label: 'Radius', url: route('bonds.index') },
                    { label: 'Grupos', url: route('bonds.index') },
                    { label: bond.description, url: route('bonds.show', bond.id) },
                    { label: 'Editar'}
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

export default Edit;

