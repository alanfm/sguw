import React from "react";
import { Head, useForm } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import Panel from "@/Components/Dashboard/Panel";
import FormUpload from "./Components/FormUpload";

function Create({ bonds, uploads }) {
    const { data, setData, post, processing, errors } = useForm({
        keep_clients: null,
        client_bond_id: null,
        file: null,
        observations: null
    });

    const onHandleChange = (event) => {
        if (event.target.name == 'file') {
            setData(event.target.name, event.target.files[0])
        }
        else {
            setData(event.target.name, event.target.value);
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('clients.uploadStore'), {data});
    };

    return (
        <>
            <Head title="Upload de Clientes" />
            <AuthenticatedLayout
                titleChildren={'Upload de Clientes'}
                breadcrumbs={[
                    { label: 'Clientes', url: route('clients.index') },
                    { label: 'Upload', url: route('clients.uploadCreate') }
                ]}
            >
                <Panel className={'flex flex-col gap-2 text-center font-light text-lg'}>
                    <h2 className="text-2xl font-normal text-neutral-600 uppercase">Atenção!</h2>
                    <p>Antes de prosseguir com o upload do arquivo, verifique os passos a seguir para </p>
                    <p>
                        Sistema para cadastro de usuários na rede Wi-Fi por carregamento de planilhas.
                        A planilha deverá ser preenchida de acordo o <strong>modelo.xls</strong>.
                    </p>
                    <p>
                        Lembre-se de alterar todos os campos para (Tipo = Texto) para evitar erros.Exclua a primeira linha do cabeçalho da planilha. Pois esta linha é somente para instruções de como colar os dados na planilha.
                    </p>
                    <p>
                        Lembrando que este procedimento demora em torno de 5 a 10 min. Favor deixar o navegador aberto somente com esta página.
                    </p>
                </Panel>
                <Panel>
                    <FormUpload data={data} bonds={bonds} errors={errors} processing={processing} onHandleChange={onHandleChange} handleSubmit={handleSubmit} />
                </Panel>
            </AuthenticatedLayout>
        </>
    )
}

export default Create;

