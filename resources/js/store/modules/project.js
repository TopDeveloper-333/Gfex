import { isFlowDeclaration } from '@babel/types'
import axios from 'axios'
import * as types from '../mutation-types'

export const state = {  
  isFDP : true,
  isCondensate: true,
  isEconomics: true,  
}

export const getters = {
  isFDP : state => state.isFDP,
  isCondensate : state => state.isCondensate,
  isEconomics: state => state.isEconomics
}

export const mutations = {
  [types.SAVE_PROJECT_TYPE] (state, {isFDP, isCondensate, isEconomics}) {
    state.isFDP = isFDP
    state.isCondensate = isCondensate
    state.isEconomics = isEconomics
  },
}

export const actions = {
  saveProjectType ({commit}, payload) {
    const isFDP = payload.isFDP
    const isCondensate = payload.isCondensate
    const isEconomics = payload.isEconomics
    commit(types.SAVE_PROJECT_TYPE, {isFDP, isCondensate, isEconomics})
  },
}