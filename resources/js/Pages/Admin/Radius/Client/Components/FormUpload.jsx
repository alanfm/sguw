import React, { useEffect, useState } from "react";
import Input from "@/Components/Form/Input";
import InputError from "@/Components/InputError";
import Button from "@/Components/Form/Button";
import SelectOnly from "@/Components/Form/SelectOnly";
import Textarea from "@/Components/Form/Textarea";

export default function FormUpload({data, errors, handleSubmit, onHandleChange, processing, bonds }) {
    const [fileName, setFileName] = useState(null);

    useEffect(() => {
        if (data.file != null)
            setFileName(data.file.name);
    }, [data.file])

    return (
        <form onSubmit={handleSubmit} autoComplete="off">
            <SelectOnly
                label={'Vínculo'}
                onChange={onHandleChange}
                error={errors.bond}
                name={'client_bond_id'}
                data={bonds}
                value={data.client_bond_id}
            />
            <SelectOnly
                label={'Manter clientes?'}
                onChange={onHandleChange}
                error={errors.keep_clients}
                name={'keep_clients'}
                data={[{ id: 1, name: 'Sim' },{ id: 2, name: 'Não' }]}
                value={data.keep_clients}
            />
            <div className="mb-4 flex flex-col">
                <span className="font-light block">Arquivo</span>
                <div className="flex">
                    <label htmlFor="file" className="border border-neutral-400 p-2 rounded-l-md bg-neutral-200 cursor-pointer">Selecionar arquivo</label>
                    <span className="flex-1 font-light border-b border-t border-r border-neutral-400 p-2 rounded-r-md select-none">
                        {fileName != null? fileName: 'Nenhum arquivo selecionado'}
                    </span>
                    <Input type={'file'} name={'file'} className={'hidden'} handleChange={onHandleChange} />
                </div>
                <InputError message={errors.file} />
            </div>
            <div className="mb-4">
                <label htmlFor="observation" className="font-light">Observações</label>
                <Textarea value={data.observations} name={'observations'} handleChange={onHandleChange} required={false} placeholder="Observações sobre o upload" />
                <InputError message={errors.observation} />
            </div>
            <div className="flex items-center justify-center gap-4 mt-6">
                <Button type={'submit'} processing={processing} color={'green'} className={"gap-2"}>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" className="w-5 h-5" viewBox="0 0 16 16">
                        <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z"/>
                    </svg>
                    <span>Enviar</span>
                </Button>
                <Button href={route('clients.uploadIndex')} className={'gap-2'}>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" className="w-5 h-5" viewBox="0 0 16 16">
                        <path fillgroup="evenodd" d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5z"/>
                    </svg>
                    <span>Voltar</span>
                </Button>
            </div>
        </form>
    )
}

