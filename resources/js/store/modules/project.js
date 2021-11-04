import { isFlowDeclaration } from '@babel/types'
import axios from 'axios'
import * as types from '../mutation-types'
import Cookies from 'js-cookie'

export const state = {
  projectList: getCookie('projectList', []),
  projectName: getCookie('projectName', ""),
  isFDP : getCookie('isFDP', "1"),
  isCondensate: getCookie('isCondensate', "1"),
  isEconomics: getCookie('isEconomics', true),
  isSeparatorOptimizer: getCookie('isSeparatorOptimizer', false),
  sep : getCookie('sep', {}),
  drygas : getCookie('drygas', {}),
  gascondensate : getCookie('gascondensate', {}),
  relPerm: getCookie('relPerm', {}),
  resKGKO: getCookie('resKGKO', []),
  resOPT: getCookie('resOPT', [])
}

function getCookie(name, defaultValue) {
  let value = Cookies.get(name)
  if (value === "true") 
    return true
  else if (value === "false")
    return false
  else if (value === undefined)
    return defaultValue
  else
    return value
}

export const getters = {
  projectName: state => state.projectName,
  isFDP : state => state.isFDP,
  isCondensate : state => state.isCondensate,
  isEconomics: state => state.isEconomics,
  isSeparatorOptimizer: state => state.isSeparatorOptimizer,
  sep : state => state.sep,
  drygas : state => state.drygas,
  gascondensate : state => state.gascondensate,
  relPerm: state => state.relPerm,
  resKGKO: state => state.resKGKO,
  resOPT: state => state.resOPT
}

export const mutations = {
  [types.SAVE_PROJECT_LIST] (state, projectList) {
    state.projectList = projectList
    Cookies.set('projectList', projectList, { expires: 1 })
  },
  [types.LOAD_PROJECT] (state, {projectName, payload}) {
    console.log('LOAD_PROJECT')
    state.projectName = projectName
    state.isFDP = payload.fastplan.isFDP
    state.isCondensate = payload.fastplan.isCondensate
    state.isEconomics = payload.fastplan.isEconomics
    state.isSeparatorOptimizer = payload.fastplan.isSeparatorOptimizer
    state.sep = payload.sep
    state.drygas = payload.drygas
    state.gascondensate = payload.gascondensate
    state.relPerm = payload.relPerm
    state.resKGKO = payload.resKGKO
    state.resOPT = payload.resOPT

    Cookies.set('projectName', projectName, { expires: 1 })
    Cookies.set('isFDP', payload.fastplan.isFDP, { expires: 1 })
    Cookies.set('isCondensate', payload.fastplan.isCondensate, { expires: 1 })
    Cookies.set('isEconomics', payload.fastplan.isEconomics, { expires: 1 })
    Cookies.set('isSeparatorOptimizer', payload.fastplan.isSeparatorOptimizer, { expires: 1 })
    Cookies.set('sep', payload.sep, { expires: 1 })
    Cookies.set('drygas', payload.drygas, { expires: 1 })
    Cookies.set('gascondensate', payload.gascondensate, { expires: 1 })
    Cookies.set('relPerm', payload.relPerm, {expires: 1})
    Cookies.set('resKGKO', payload.resKGKO, {expires: 1})
    Cookies.set('resOPT', payload.resOPT, {expires: 1})
  },
  [types.SAVE_PROJECT_TYPE] (state, {isFDP, isCondensate, isEconomics, isSeparatorOptimizer}) {
    state.isFDP = isFDP
    state.isCondensate = isCondensate
    state.isEconomics = isEconomics
    state.isSeparatorOptimizer = isSeparatorOptimizer

    Cookies.set('isFDP', isFDP, { expires: 1 })
    Cookies.set('isCondensate', isCondensate, { expires: 1 })
    Cookies.set('isEconomics', isEconomics, { expires: 1 })
    Cookies.set('isSeparatorOptimizer', isSeparatorOptimizer, { expires: 1 })
  },
  [types.SAVE_SEP] (state, sep) {
    state.sep = sep
    Cookies.set('sep', sep, { expires: 1 })
  },
  [types.SAVE_DRY_GAS] (state, drygas) {
    state.drygas = drygas
    Cookies.set('drygas', drygas, { expires: 1 })
  },
  [types.SAVE_GAS_CONDENSATE] (state, gascondensate) {
    state.gascondensate = gascondensate
    Cookies.set('gascondensate', gascondensate, { expires: 1 })
  },
  [types.SAVE_REL_PERM] (state, relPerm) {
    state.relPerm = relPerm
    Cookies.set('relPerm', relPerm, {expires: 1})
  },
  [types.SAVE_RES_KGKO] (state, resKGKO) {
    state.resKGKO = resKGKO
    Cookies.set('resKGKO', resKGKO, {expires: 1})
  },
  [types.SAVE_RES_OPTIMIZER] (state, resOPT) {
    state.resOPT = resOPT
    Cookies.set('resOPT', resOPT, {expires: 1})
  }
}

export const actions = {
  async listProjects ({commit}) {
    const { data } = await axios.post('/api/listProjects')

    if (typeof(data) == 'string') {
      let payload = JSON.parse(data)
      commit(types.SAVE_PROJECT_LIST, payload)
    }
    else {
      let payload = data
      commit(types.SAVE_PROJECT_LIST, payload)
    }
  },
  async createProject ({commit}, projectName) {
    const { data } = await axios.post('/api/createProject', {'project': projectName})

    if (typeof(data) == 'string') {
      let payload = JSON.parse(data)
      commit(types.LOAD_PROJECT, {projectName, payload})
    }
    else {
      let payload = data
      commit(types.LOAD_PROJECT, {projectName, payload})
    }

  },
  async openProject({commit}, projectName) {
    const { data } = await axios.post('/api/openProject', {'project': projectName})

    if (typeof(data) == 'string') {
      let payload = JSON.parse(data)
      commit(types.LOAD_PROJECT, {projectName, payload})      
    }
    else {
      let payload = data
      commit(types.LOAD_PROJECT, {projectName, payload})
    }

  },
  saveFastPlan ({commit}, payload) {
    const isFDP = payload.isFDP
    const isCondensate = payload.isCondensate
    const isEconomics = payload.isEconomics
    const isSeparatorOptimizer = payload.isSeparatorOptimizer
    commit(types.SAVE_PROJECT_TYPE, {isFDP, isCondensate, isEconomics, isSeparatorOptimizer})
  },
  async saveDryGas({commit}, dryGas) {
    commit(types.SAVE_DRY_GAS, dryGas)
  },
  async saveGasCondensate({commit}, gascondensate) {
    commit(types.SAVE_GAS_CONDENSATE, gascondensate)
  },
  async fetchSEP ({commit}, sep) {
    commit(types.SAVE_SEP, sep)
    const { data } = await axios.post('/api/requestOPT', sep)

    if (typeof (data) == 'string') {
      commit(types.SAVE_RES_OPTIMIZER, JSON.parse(data))
    }
    else {
      commit(types.SAVE_RES_OPTIMIZER, data)
    }
  },
  async fetchKGKO({commit}, relPerm) {
    commit(types.SAVE_REL_PERM, relPerm)
    const { data } = await axios.post('/api/requestKGKO', relPerm)

    if (typeof (data) == 'string') {
      commit(types.SAVE_RES_KGKO, JSON.parse(data))      
    }
    else {
      commit(types.SAVE_RES_KGKO, data)
    }
    
  }
}