import { isFlowDeclaration } from '@babel/types'
import axios from 'axios'
import * as types from '../mutation-types'

export const state = {  
  projectName: "",
  isFDP : "1",
  isCondensate: "1",
  isEconomics: "1",  
}

export const getters = {
  projectName: state => state.projectName,
  isFDP : state => state.isFDP,
  isCondensate : state => state.isCondensate,
  isEconomics: state => state.isEconomics
}

export const mutations = {
  [types.SAVE_PROJECT_NAME] (state, projectName) {
    state.projectName = projectName
  },
  [types.SAVE_PROJECT_TYPE] (state, {isFDP, isCondensate, isEconomics}) {
    state.isFDP = isFDP
    state.isCondensate = isCondensate
    state.isEconomics = isEconomics
  },  
}

export const actions = {
  saveProjectName ({commit}, projectName) {
    commit(types.SAVE_PROJECT_NAME, projectName)
  },
  saveProjectType ({commit}, payload) {
    const isFDP = payload.isFDP
    const isCondensate = payload.isCondensate
    const isEconomics = payload.isEconomics
    commit(types.SAVE_PROJECT_TYPE, {isFDP, isCondensate, isEconomics})
  }
}