import { isFlowDeclaration } from '@babel/types'
import axios from 'axios'
import * as types from '../mutation-types'
import Cookies from 'js-cookie'

export const state = {  
  projectName: Cookies.get('projectName') == undefined ? "" : Cookies.get('projectName'),
  isFDP : Cookies.get('isFDP') == undefined ? "1" : Cookies.get('isFDP') ,
  isCondensate: Cookies.get('isCondensate') == undefined ? "1" : Cookies.get('isCondensate') ,
  isEconomics: Cookies.get('isEconomics') == undefined ? "1" : Cookies.get('isEconomics') ,
  isSeparatorOptimizer: Cookies.get('isSeparatorOptimizer') == undefined ? false : (Cookies.get('isSeparatorOptimizer') === 'true') ,
  sep : Cookies.get('sep') == undefined ? {} : Cookies.get('sep') ,
  drygas : Cookies.get('drygas') == undefined ? {} : Cookies.get('drygas'),
  gascondensate : Cookies.get('gascondensate') == undefined ? {} : Cookies.get('gascondensate'),
}

export const getters = {
  projectName: state => state.projectName,
  isFDP : state => state.isFDP,
  isCondensate : state => state.isCondensate,
  isEconomics: state => state.isEconomics,
  isSeparatorOptimizer: state => state.isSeparatorOptimizer,
  sep : state => state.sep,
  drygas : state => state.drygas,
  gascondensate : state => state.gascondensate
}

export const mutations = {
  [types.SAVE_PROJECT_NAME] (state, projectName) {
    state.projectName = projectName
    Cookies.set('projectName', projectName, { expires: 1 })
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
  }
}

export const actions = {
  saveProjectName ({commit}, projectName) {
    commit(types.SAVE_PROJECT_NAME, projectName)
  },
  saveProjectType ({commit}, payload) {
    const isFDP = payload.isFDP
    const isCondensate = payload.isCondensate
    const isEconomics = payload.isEconomics
    const isSeparatorOptimizer = payload.isSeparatorOptimizer
    commit(types.SAVE_PROJECT_TYPE, {isFDP, isCondensate, isEconomics, isSeparatorOptimizer})
  },
  async saveSEP ({commit}, sep) {
    commit(types.SAVE_SEP, sep)
  },
  async saveDryGas({commit}, dryGas) {
    commit(types.SAVE_DRY_GAS, dryGas)
  },
  async saveGasCondensate({commit}, gascondensate) {
    commit(types.SAVE_GAS_CONDENSATE, gascondensate)
  }
}