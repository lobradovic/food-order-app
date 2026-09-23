import { useForm } from '@inertiajs/vue3'

interface Jelo {
    id: number
    cena: number
}

interface FormJelo {
    id: number
    cena: number
    kolicina_dostupna: number | null
}

interface DnevnaPonudaForm {
    datum: string
    aktivna: boolean
    jela: FormJelo[]
}

interface InitialDnevnaPonuda {
    datum?: string
    aktivna?: boolean
    jela?: FormJelo[]
}

export function useDnevnaPonudaForm(
    initial: InitialDnevnaPonuda = {}
) {
    const form = useForm<DnevnaPonudaForm>({
        datum: initial.datum ?? '',
        aktivna: initial.aktivna ?? true,
        jela: initial.jela ?? [],
    })

    function toggleJelo(jelo: Jelo, checked: boolean) {
        if (checked) {
            form.jela.push({
                id: jelo.id,
                cena: jelo.cena,
                kolicina_dostupna: null,
            })
        } else {
            form.jela = form.jela.filter(
                (j) => j.id !== jelo.id
            )
        }
    }

    const isSelected = (id: number): boolean =>
        form.jela.some((j) => j.id === id)

    const selected = (id: number): FormJelo | undefined =>
        form.jela.find((j) => j.id === id)

    function reset(values: DnevnaPonudaForm) {
        form.datum = values.datum
        form.aktivna = values.aktivna
        form.jela = values.jela
        form.clearErrors()
    }

    return {
        form,
        toggleJelo,
        isSelected,
        selected,
        reset,
    }
}
