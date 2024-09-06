import React, { useEffect, useState } from "react";
import { Link, router } from "@inertiajs/react";
import Pagination from "@/Components/Dashboard/Pagination";
import Panel from "@/Components/Dashboard/Panel";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import Button from "@/Components/Form/Button";

function Index({ data, count, page, termSearch, can }) {
    const [term, setTerm] = useState(termSearch?? '');
    const [currentPage, setCurrentPage] = useState(page);

    useEffect(() => {
        const debounce = setTimeout(() => {
            setCurrentPage(1);
            router.visit(route(route().current()), {data: {term: term, page: currentPage}, preserveState: true, replace: true});
        }, 300);

        return () => clearTimeout(debounce);
    }, [term]);

    const table = data.data.map((item, index) => {
        return (
            <tr key={index} className={"border-t transition hover:bg-neutral-100 " + (index % 2 == 0? 'bg-neutral-50': '')}>
                <td className="px-1 py-3 font-light">
                    <Link href={can.view? route('clients.show', item.id): route('clients.index', {term: term, page: currentPage})}>{item.name}</Link>
                </td>
                <td className="px-1 py-3 font-light">
                    <Link href={can.view? route('clients.show', item.id): route('clients.index', {term: term, page: currentPage})}>{item.registry}</Link>
                </td>
                <td className="px-1 py-3 font-light">
                    <Link href={can.view? route('clients.show', item.id): route('clients.index', {term: term, page: currentPage})}>{item.cpf}</Link>
                </td>
                <td className="flex justify-end py-3 pr-2 text-neutral-400">
                    <Link href={can.view? route('clients.show', item.id): route('clients.index', {term: term, page: currentPage})}>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" className="w-5 h-5" viewBox="0 0 16 16">
                            <path fillRule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </Link>
                </td>
            </tr>
        );
    });

    return (
        <>
            <AuthenticatedLayout titleChildren={'Gerenciamento de Usuários'} breadcrumbs={[{ label: 'Usuários', url: route('clients.index') }]}>
                <div className="flex gap-2 md:flex-row md:gap-4">
                    {can.create && <Panel className={'inline-flex'}>
                        <Link href={route('clients.create')} className="inline-flex items-center justify-between gap-2 px-3 py-2 font-light text-white transition bg-blue-500 border border-transparent rounded-md focus:ring hover:bg-blue-600 focus:ring-sky-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" className="w-5 h-5" viewBox="0 0 16 16">
                                <path fillRule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z" />
                            </svg>
                            <span>Nova</span>
                        </Link>
                    </Panel>}
                    <Panel className={'flex-1 relative'}>
                        <input type="search" value={term} onChange={e => setTerm(e.target.value)} className="w-full border rounded-md focus:ring focus:ring-green-200 focus:border-green" placeholder="Faça sua pesquisa" />
                        <span className="absolute z-10 flex items-center p-2 top-4 right-2 md:right-4 h-7 md:h-10">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" className="w-4 h-4" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg>
                        </span>
                    </Panel>
                </div>
                <Panel className="">
                    <table className="w-full table-auto text-neutral-600">
                        <thead>
                            <tr className="border-b">
                                <th className="px-1 pt-3 font-semibold text-left">Usuário</th>
                                <th className="px-1 pt-3 font-semibold text-left">Matricula</th>
                                <th className="px-1 pt-3 font-semibold text-left">C.P.F.</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            {table}
                        </tbody>
                    </table>
                    <Pagination data={data} count={count}>
                        <Button color={'green'} href={route('clients.uploadIndex')} className={'flex items-center justify-center gap-2 font-light'}>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" className="h-5 w-5" viewBox="0 0 16 16">
                                <path fillRule="evenodd" d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383"/>
                                <path fillRule="evenodd" d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708z"/>
                            </svg>
                            <span>Upload de Clientes</span>
                        </Button>
                    </Pagination>
                </Panel>
            </AuthenticatedLayout>
        </>
    )
}

export default Index;
