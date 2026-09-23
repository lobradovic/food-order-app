import { defineStore } from 'pinia'

interface CartItem {
  jeloId: number
  nazivJela: string
  cena: number
  kolicina: number
}

const STORAGE_KEY = 'ketering-korpa'

function loadFromStorage(): CartItem[] {
  try {
    const raw = localStorage.getItem(STORAGE_KEY)
    return raw ? JSON.parse(raw) : []
  } catch {
    return []
  }
}

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: loadFromStorage() as CartItem[],
  }),
  getters: {
    brojStavki: (state) => state.items.reduce((sum, i) => sum + i.kolicina, 0),
    ukupnaCena: (state) => state.items.reduce((sum, i) => sum + i.cena * i.kolicina, 0),
  },
  actions: {
    dodaj(jelo: { id: number; naziv_jela: string; cena: number }) {
      const postojece = this.items.find((i) => i.jeloId === jelo.id)
      if (postojece) {
        postojece.kolicina++
      } else {
        this.items.push({ jeloId: jelo.id, nazivJela: jelo.naziv_jela, cena: jelo.cena, kolicina: 1 })
      }
      this.persist()
    },
    ukloni(jeloId: number) {
      this.items = this.items.filter((i) => i.jeloId !== jeloId)
      this.persist()
    },
    setKolicina(jeloId: number, kolicina: number) {
      const item = this.items.find((i) => i.jeloId === jeloId)
      if (item) {
        item.kolicina = Math.max(1, kolicina)
        this.persist()
      }
    },
    isprazni() {
      this.items = []
      this.persist()
    },
    persist() {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(this.items))
    },
  },
})