import { isFlowDeclaration } from '@babel/types'
import axios from 'axios'
import * as types from '../mutation-types'
import Cookies from 'js-cookie'

export const state = {
  projectList: getCookie('projectList', []),
  projectName: getCookie('projectName', ""),
  projectId : getCookie('projectId', -1),
  isFDP : getCookie('isFDP', "1"),
  isCondensate: getCookie('isCondensate', "1"),
  isEconomics: getCookie('isEconomics', true),
  isSeparatorOptimizer: getCookie('isSeparatorOptimizer', false),
  sep : getCookie('sep', {}),
  drygas : getCookie('drygas', {}),
  surface: getCookie('surface', {}),
  reservoir: getCookie('reservoir', {}),
  wellhistory: getCookie('wellhistory', {}),
  economics: getCookie('economics', {}),
  operations: getCookie('operations', {}),
  gascondensate : getCookie('gascondensate', {}),
  relPerm: getCookie('relPerm', {}),
  resKGKO: getCookie('resKGKO', []),
  resOPT: getCookie('resOPT', []),
  resRawDryGas: {}
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
  projectId: state => state.projectId,
  isFDP : state => state.isFDP,
  isCondensate : state => state.isCondensate,
  isEconomics: state => state.isEconomics,
  isSeparatorOptimizer: state => state.isSeparatorOptimizer,
  sep : state => state.sep,
  drygas : state => state.drygas,
  surface: state => state.surface,
  reservoir: state => state.reservoir,
  wellhistory: state => state.wellhistory,
  economics: state => state.economics,
  operations: state => state.operations,
  gascondensate : state => state.gascondensate,
  relPerm: state => state.relPerm,
  resKGKO: state => state.resKGKO,
  resOPT: state => state.resOPT,
  resRawDryGas: state => state.resRawDryGas
}

export const mutations = {
  [types.SAVE_PROJECT_LIST] (state, projectList) {
    state.projectList = projectList
    Cookies.set('projectList', projectList, { expires: 1 })
  },
  [types.LOAD_PROJECT] (state, {projectName, payload}) {
    console.log('LOAD_PROJECT')
    state.projectName = projectName
    state.projectId = payload.id
    state.isFDP = payload.fastplan.isFDP
    state.isCondensate = payload.fastplan.isCondensate
    state.isEconomics = payload.fastplan.isEconomics
    state.isSeparatorOptimizer = payload.fastplan.isSeparatorOptimizer
    state.sep = payload.sep
    state.drygas = payload.drygas
    state.surface = payload.surface
    state.reservoir = payload.reservoir
    state.wellhistory = payload.wellhistory
    state.economics = payload.economics
    state.operations = payload.operations
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
    Cookies.set('surface', payload.surface, { expires: 1})
    Cookies.set('reservoir', payload.reservoir, { expires: 1})
    Cookies.set('wellhistory', payload.wellhistory, { expires: 1}) 
    Cookies.set('economics', payload.economics, {expires: 1})
    Cookies.set('operations', payload.operations, {expires: 1})
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
  [types.SAVE_SURFACE] (state, surface) {
    state.surface = surface
    Cookies.set('surface', surface, { expires: 1 })
  },
  [types.SAVE_RESERVOIR] (state, reservoir) {
    state.reservoir = reservoir
    Cookies.set('reservoir', reservoir, { expires: 1}) 
  },
  [types.SAVE_WELLHISTORY] (state, wellhistory) {
    state.wellhistory = wellhistory
    Cookies.set('wellhistory', wellhistory, { expires: 1})
  },
  [types.SAVE_ECONOMICS] (state, economics) {
    state.economics = economics
    Cookies.set('economics', economics, {expires: 1})
  },
  [types.SAVE_OPERATIONS] (state, operations) {
    state.operations = operations
    Cookies.set('operations', operations, { expires: 1})
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
  },
  [types.SAVE_RES_DRYGAS] (state, resRawDryGas) {
    state.resRawDryGas = resRawDryGas
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

    let payload = JSON.parse(data.content)
    payload.id = data.id
    commit(types.LOAD_PROJECT, {projectName, payload})

  },
  async openProject({commit}, project) {
    let id = project.id
    let projectName = project.name

    const { data } = await axios.post('/api/openProject', { 'id': id, 'project': projectName})

    let payload = JSON.parse(data.content)
    payload.id = data.id
    commit(types.LOAD_PROJECT, {projectName, payload})

  },
  async saveProject({commit}, payload) {
    const { data } = await axios.post('api/saveProject', payload)    
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
  async saveSurface({commit}, surface) {
    commit(types.SAVE_SURFACE, surface)
  },
  async saveReservoir({commit}, reservoir) {
    commit(types.SAVE_RESERVOIR, reservoir)
  },
  async saveWellHistory({commit}, wellhistory) {
    commit(types.SAVE_WELLHISTORY, wellhistory)
  },
  async saveEconomics({commit}, economics) {
    commit(types.SAVE_ECONOMICS, economics)
  },
  async saveOperations({commit}, operations) {
    commit(types.SAVE_OPERATIONS, operations)
  },
  async saveGasCondensate({commit}, gascondensate) {
    commit(types.SAVE_GAS_CONDENSATE, gascondensate)
  },
  async saveSEP({commit}, sep) {
    commit(types.SAVE_SEP, sep)
  },
  async fetchSEP ({commit}, sep) {
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
  },
  async runDryGasProject({commit}, payload) {
    const { data } = await axios.post('/api/runDryGas', payload)
    if (typeof (data) == 'string') {
      commit(types.SAVE_RES_DRYGAS, JSON.parse(data))      
    }
    else {
      commit(types.SAVE_RES_DRYGAS, data)
    }
  },
  async runGasCondensateProject({commit}, payload) {

  }

}